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
            // Drop the price_per_day column if it exists
            if (Schema::hasColumn('equipment', 'price_per_day')) {
                $table->dropColumn('price_per_day');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipment', function (Blueprint $table) {
            // In case we need to rollback
            $table->decimal('price_per_day', 12, 2)->default(0)->nullable();
        });
    }
};
