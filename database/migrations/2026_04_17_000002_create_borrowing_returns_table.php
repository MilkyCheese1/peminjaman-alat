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
        if (!Schema::hasTable('borrowing_returns')) {
            Schema::create('borrowing_returns', function (Blueprint $table) {
                $table->id('id_return');
                $table->unsignedBigInteger('id_borrowing')->nullable();
                $table->datetime('return_date')->nullable();
                $table->enum('condition', ['good', 'minor_damage', 'major_damage'])->default('good');
                $table->text('condition_notes')->nullable();
                $table->text('damage_notes')->nullable();
                $table->string('photo_after')->nullable();
                $table->boolean('fine_paid')->default(false);
                $table->decimal('fine_amount', 10, 2)->default(0);
                $table->timestamps();

                // Indexes
                $table->index('id_borrowing');
                $table->index('return_date');
                $table->index('created_at');

                // Foreign key
                $table->foreign('id_borrowing')
                    ->references('id_borrowing')
                    ->on('borrowings')
                    ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrowing_returns');
    }
};
