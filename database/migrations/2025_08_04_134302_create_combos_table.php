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
        Schema::create('combos', function (Blueprint $table) {
            $table->id();
            $table->string('name')
                ->comment('Name of the combo');
            $table->text('description')
                ->nullable()
                ->comment('Description of the combo');
            $table->string('image')
                ->nullable()
                ->comment('Image URL of the combo');
            $table->decimal('price', 10, 2)
                ->comment('Price of the combo');
            $table->decimal('original_price', 10, 2)
                ->comment('Original price of the combo');
            $table->decimal('discount', 10, 2)
                ->default(0.00)
                ->comment('Discount amount for the combo');
            $table->decimal('tax_rate', 5, 2)
                ->default(10.00)
                ->comment('Tax rate applied to the combo (e.g., GST)');
            $table->decimal('tax_amount', 10, 2)
                ->default(0.00)
                ->comment('Tax amount applied to the combo (e.g., GST)');
            $table->boolean('is_active')
                ->default(true)
                ->comment('Indicates if the combo is currently active');
            $table->boolean('is_featured')
                ->default(false)
                ->comment('Indicates if the combo is featured');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('combos');
    }
};
