<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('equipment', function (Blueprint $table) {
            // Add fine_per_day column if it doesn't exist
            if (!Schema::hasColumn('equipment', 'fine_per_day')) {
                $table->decimal('fine_per_day', 12, 2)->default(50000)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipment', function (Blueprint $table) {
            // Drop the fine_per_day column
            if (Schema::hasColumn('equipment', 'fine_per_day')) {
                $table->dropColumn('fine_per_day');
            }
        });
    }
};
