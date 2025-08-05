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
        Schema::create('combo_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('combo_item_id')->constrained()->onDelete('cascade');
            $table->boolean('is_default')
                ->default(false)
                ->comment('Indicates if this product is the default for the combo item');
            $table->decimal('extra_price', 10, 2)
                ->default(0.00)
                ->comment('extra price of the combo product');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('combo_products');
    }
};
