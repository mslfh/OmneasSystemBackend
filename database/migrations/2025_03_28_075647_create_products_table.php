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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('title')
            ->comment('Title of the product');

            $table->string('second_title')->nullable()
                ->comment('Alternative title for the product');
            $table->string('acronym')->nullable()
                ->comment('Short form of the product title');
            $table->string('description')->nullable()
                ->comment('Detailed description of the product');
            $table->string('tip')->nullable()
                ->comment('Helpful tip for the product');
            $table->double('price')->comment('Base price of the product');
            $table->double('discount')->default(0)
                ->comment('Discount amount for the product');
            $table->double('selling_price')->default(0)
                ->comment('Selling price of the product');
            $table->integer('stock')->nullable()
                ->comment('Available stock quantity, null is unlimited');
            $table->string('status')->default('active')
                ->comment('active, inactive, draft');
            $table->string('image')->nullable()
                ->comment('Main image of the product');
            $table->text('image_list')->nullable()
                ->comment('List of additional product images');
            $table->string('tag')->nullable()
                ->comment('Comma-separated list of tags for the product');

            $table->integer('sort')->nullable()
                ->comment('Sort order for display, lower numbers appear first');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
