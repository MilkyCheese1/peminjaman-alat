<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Queue jobs run synchronously, so these database tables are not needed.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
