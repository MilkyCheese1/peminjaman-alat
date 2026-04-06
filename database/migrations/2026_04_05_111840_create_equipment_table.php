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
        Schema::create('equipment', function (Blueprint $table) {
            $table->id('id_equipment');
            $table->unsignedBigInteger('id_category')->nullable();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->integer('quantity')->default(0);
            $table->string('condition', 50)->default('good'); // good, fair, poor
            $table->string('photo')->nullable();
            $table->boolean('is_available')->default(true);
            $table->timestamps();
            
            $table->foreign('id_category')->references('id_category')->on('categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
