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
            $table->string('mode')->default('fixed')
                ->comment('replaceable_variable,replaceable,variable,fixed, etc.');
            $table->string('replacement_list')->nullable()
                ->comment('Json list of item id to replace');
            $table->string('replacement_diff')->nullable()
                ->comment('Json list of extra cost when replacing into this item');
            $table->string('replacement_extra')->nullable()
                ->comment('Json list of extra cost when add more quantity after replacing');
            $table->decimal('extra_price', 10, 2)->nullable()
                ->comment('extra cost when adding more quantity in variable mode');
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
