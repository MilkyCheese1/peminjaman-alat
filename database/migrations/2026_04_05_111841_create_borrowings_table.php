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
        Schema::create('borrowings', function (Blueprint $table) {
            $table->id('id_borrowing');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_equipment');
            $table->integer('quantity')->default(1);
            $table->dateTime('borrow_date');
            $table->dateTime('planned_return_date');
            $table->dateTime('actual_return_date')->nullable();
            $table->string('status', 50)->default('applied'); // applied, approved, ready_for_pickup, picked_up, returned, rejected, cancelled, overdue
            $table->string('pickup_code', 20)->nullable()->unique();
            $table->dateTime('pickup_code_generated_at')->nullable();
            $table->dateTime('pickup_verified_at')->nullable();
            $table->string('pickup_photo_before')->nullable();
            $table->decimal('fine_amount', 12, 2)->default(0);
            $table->boolean('fine_paid')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
            $table->foreign('id_equipment')->references('id_equipment')->on('equipment')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};
