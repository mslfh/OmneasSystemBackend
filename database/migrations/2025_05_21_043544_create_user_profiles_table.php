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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();

            $table->string('first_name') ;
            $table->string('last_name') ;
            $table->string('phone') ;
            $table->string('gender')->nullable();
            $table->date('date_of_birth')->nullable();

            $table->string('status')->default('active')->comment(
                'active, inactive, blocked, deleted'
            );
            $table->string('type')->nullable();
            $table->string('address')->nullable();
            $table->string('visit_reason')->nullable();

            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();

            $table->string('private_health_fund_provider')->nullable();

            $table->string('pain_points')->nullable();
            $table->string('areas_of_soreness')->nullable();

            $table->string('medical_history')->nullable();
            $table->text('medical_attachment_path')->nullable();
            $table->string('others')->nullable()->comment(
                'Pregnant or period, or any other relevant information'
            );
            $table->string('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
