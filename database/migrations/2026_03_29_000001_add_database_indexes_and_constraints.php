<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Add critical indexes and constraints for performance
     */
    public function up(): void
    {
        /**
         * ===== PEMINJAMAN TABLE INDEXES =====
         * These queries are executed frequently in BookingValidationService
         */
        Schema::table('peminjaman', function (Blueprint $table) {
            // Index for status filtering (most common: whereIn(['pending', 'booked', 'in_use']))
            $table->index('status');
            
            // Composite index for booking validation queries
            // Used in: isAlatAvailable() + hasBufferTime()
            $table->index(['id_alat', 'status']);
            
            // Index for date range queries
            $table->index('tgl_peminjaman');
            $table->index('tgl_kembali');
            
            // Index for user's own borrowings
            $table->index('id_user');
            
            // Index for activity tracking (status updates by user)
            $table->index('approved_by');
            $table->index('status_updated_by');
        });

        /**
         * ===== ALAT TABLE INDEXES =====
         */
        Schema::table('alat', function (Blueprint $table) {
            // Index for status filtering (filtering available/booked/maintenance)
            $table->index('status_alat');
            
            // Index for category filtering
            $table->index('id_kategori');
            
            // Index for SKU searches
            $table->index('sku');
        });

        /**
         * ===== USERS TABLE INDEXES =====
         */
        Schema::table('users', function (Blueprint $table) {
            // Index for login queries
            $table->index('username');
            
            // Index for role-based queries
            $table->index('role');
        });

        /**
         * ===== CHECK CONSTRAINTS =====
         * Ensure data integrity for stock quantities
         */
        
        // Stock cannot be negative
        DB::statement('ALTER TABLE alat ADD CONSTRAINT check_stok_positive CHECK (stok >= 0)');
        
        // Dipinjam (borrowed count) cannot be negative
        DB::statement('ALTER TABLE alat ADD CONSTRAINT check_dipinjam_positive CHECK (dipinjam >= 0)');
        
        // Dipinjam cannot exceed stok
        DB::statement('ALTER TABLE alat ADD CONSTRAINT check_dipinjam_not_exceed_stok CHECK (dipinjam <= stok)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop constraints
        Schema::table('alat', function (Blueprint $table) {
            try {
                DB::statement('ALTER TABLE alat DROP CONSTRAINT check_stok_positive');
                DB::statement('ALTER TABLE alat DROP CONSTRAINT check_dipinjam_positive');
                DB::statement('ALTER TABLE alat DROP CONSTRAINT check_dipinjam_not_exceed_stok');
            } catch (\Exception $e) {
                // Ignore if constraints don't exist
            }
        });

        // Drop indexes
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['id_alat', 'status']);
            $table->dropIndex(['tgl_peminjaman']);
            $table->dropIndex(['tgl_kembali']);
            $table->dropIndex(['id_user']);
            $table->dropIndex(['approved_by']);
            $table->dropIndex(['status_updated_by']);
        });

        Schema::table('alat', function (Blueprint $table) {
            $table->dropIndex(['status_alat']);
            $table->dropIndex(['id_kategori']);
            $table->dropIndex(['sku']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['username']);
            $table->dropIndex(['role']);
        });
    }
};
