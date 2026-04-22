<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('feedback_entries')) {
            return;
        }

        Schema::create('feedback_entries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama', 100);
            $table->string('email', 120);
            $table->unsignedTinyInteger('stars')->nullable();
            $table->string('pesan', 255);
            $table->unsignedTinyInteger('status')->default(1)->comment('1=Pending,2=Ditampilkan,3=Ditolak');
            $table->timestamp('created_at')->useCurrent();

            $table->index(['status', 'created_at'], 'idx_feedback_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedback_entries');
    }
};
