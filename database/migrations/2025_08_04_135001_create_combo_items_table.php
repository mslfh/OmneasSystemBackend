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
        Schema::create('combo_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('combo_id')->constrained()->onDelete('cascade');
            $table->string('name')
            ->comment('Name of the combo item, e.g., "Choose Drink"');
            $table->text('description')->nullable();
            $table->string('type')->default('fixed')
            ->comment('Type of combo item, e.g., fixed, choice');
            $table->integer('min_select')->default(1)
            ->comment('Minimum quantity of the combo item');
            $table->integer('max_select')->default(1)
            ->comment('Maximum quantity of the combo item');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('combo_items');
    }
};
