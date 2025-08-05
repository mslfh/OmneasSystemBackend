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
        Schema::create('product_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                ->constrained('products')
                ->onDelete('cascade')
                ->comment('Foreign key referencing the products table');
            $table->foreignId('profile_id')
                ->constrained('profiles')
                ->onDelete('cascade')
                ->comment('Foreign key referencing the profiles table');
            $table->string('additional_info')
                ->nullable()
                ->comment('Json of Additional information about the product profile');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_profiles');
    }
};
