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
        Schema::create('profiles', function (Blueprint $table) {
            //Profiles for product sets
            $table->id();
            $table->string('name')
                ->comment('Name of the profile');
            $table->string('type')->default('tax')
                ->comment('Type of the profile (e.g., tax, discount, shipping)');
            $table->text('description')
                ->nullable()
                ->comment('Description of the profile');
            $table->string('status')->default('active')
                ->comment('Status of the profile (e.g., active, inactive)');
            $table->string('settings')
                ->nullable()
                ->comment('JSON settings for the profile, like tax rates, discounts, etc.');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
