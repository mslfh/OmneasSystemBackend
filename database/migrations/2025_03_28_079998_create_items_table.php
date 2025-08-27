<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            // The items that can comprise a product
            $table->id();
            $table->string('name')->comment('Name of the product item');
            $table->string('type')
                ->comment('Type of the product item, e.g., material, ingredient, sauce, taste etc.');
            $table->text('description')->nullable()->comment('Description of the product item');
            $table->decimal('price', 10, 2)->comment('Price of the product item');
            $table->decimal('extra_price', 10, 2)
            ->default(0.00)->comment('Extra price if add this product item');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
