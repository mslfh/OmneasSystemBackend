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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')
                ->constrained('orders')
                ->onDelete('cascade')
                ->comment('Foreign key referencing the orders table');
            $table->foreignId('product_id')
                ->constrained('products')
                ->onDelete('cascade')
                ->comment('Foreign key referencing the products table');
            $table->integer('quantity')
                ->default(1)
                ->comment('Quantity of the product in the order');
            $table->boolean('is_combo')
                ->default(false)
                ->comment('Indicates if the product is a combo item');
            $table->integer('combo_id')
                ->nullable()
                ->comment('ID of the combo if this item is part of a combo');
            $table->string('combo_item_name')
                ->nullable()
                ->comment('Name of the combo item if this item is part of a combo');
            $table->boolean('is_customization')
                ->default(false)
                ->comment('Indicates if the product is a customization');
            $table->string('product_title')
                ->comment('Title of the product');
            $table->string('product_second_title')
                ->comment('Second title of the product');
            $table->string('product_items')->nullable()
                ->comment('Items of the product, if applicable like customizations');

            $table->decimal('product_price', 10, 2)
                ->comment('Price of the product at the time of the order');
            $table->decimal('product_discount', 10, 2)
                ->default(0)
                ->comment('Discount applied to the product at the time of the order');
            $table->decimal('product_selling_price', 10, 2)
                ->default(0)
                ->comment('Selling price of the product at the time of the order');
            $table->decimal('final_amount', 10, 2)
                ->comment('Final amount for the order item');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
