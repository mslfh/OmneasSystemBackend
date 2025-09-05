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
        Schema::create('product_customizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            $table->integer('type')->default(0)
                ->comment('customization type: 0-sync with product item or 1-customize');
            $table->string('mode')->default('fixed')
                ->comment('replaceable_variable,replaceable,variable,fixed, etc.');
            $table->string('replacement_list')->nullable()
                ->comment('Json list of item id to replace');
            $table->string('replacement_diff')->nullable()
                ->comment('Json list of extra cost when replacing into this item');
            $table->string('replacement_extra')->nullable()
                ->comment('Json list of extra cost when add more quantity after replacing');
            $table->string('quantity_price')->nullable()
                ->comment('Json of cost when adding or reducing quantity in variable mode');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_customizations');
    }
};
