<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tambah role 'owner' ke users role enum
        // Menggunakan raw SQL untuk kompatibilitas database
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'petugas', 'peminjam', 'owner') DEFAULT 'peminjam'");
        
        // 2. Tambah soft delete ke alat table
        Schema::table('alat', function (Blueprint $table) {
            $table->softDeletes()->after('status_alat');
            $table->string('gambar')->nullable()->after('status_alat');
        });

        // 3. Tambah soft delete ke peminjaman table
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->softDeletes()->after('actual_return_date');
            $table->string('qr_code')->nullable()->unique()->after('actual_return_date');
            $table->unsignedInteger('approved_by')->nullable()->after('qr_code');
            $table->unsignedInteger('status_updated_by')->nullable()->after('approved_by');
            $table->timestamp('status_updated_at')->nullable()->after('status_updated_by');
            
            // Foreign key untuk tracking
            $table->foreign('approved_by')->references('id_user')->on('users')->onDelete('set null');
            $table->foreign('status_updated_by')->references('id_user')->on('users')->onDelete('set null');
        });

        // 4. Buat activity_logs table untuk tracking
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_user');
            $table->string('action');
            $table->string('model_type');
            $table->unsignedInteger('model_id');
            $table->text('changes')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamp('created_at')->useCurrent();
            
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
            $table->index('created_at');
            $table->index(['model_type', 'model_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn(['qr_code', 'approved_by', 'status_updated_by', 'status_updated_at']);
        });

        Schema::table('alat', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn('gambar');
        });

        // Revert role enum
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'petugas', 'peminjam') DEFAULT 'peminjam'");
    }
};
