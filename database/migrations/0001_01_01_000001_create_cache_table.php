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
        // Cache is stored using the file driver, so no database tables are needed.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
