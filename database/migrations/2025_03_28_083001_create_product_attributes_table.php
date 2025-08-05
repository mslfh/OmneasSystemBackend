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
        Schema::create('product_attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Name of the product attribute, e.g., only meats, extra source, etc.');
            $table->string('type')->nullable()
            ->comment('Type of the product attribute, e.g., extra, only, etc.');
             $table->decimal('extra_cost', 10, 2)->default(0)
            ->comment('Additional cost for the product attribute');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_attributes');
    }
};
