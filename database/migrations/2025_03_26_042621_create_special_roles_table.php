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
        Schema::create('special_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
         // Insert default special roles
         $adminId = \DB::table('users')->where('email', env('ADMIN_EMAIL'))->value('id');
         $deskId = \DB::table('users')->where('email', env('DESK_EMAIL'))->value('id');

         \DB::table('special_roles')->insert([
             [
                 'name' => 'Admin',
                 'description' => 'Administrator Role',
                 'is_active' => true,
                 'user_id' => $adminId,
                 'created_at' => now(),
                 'updated_at' => now(),
             ],
             [
                 'name' => 'Desk',
                 'description' => 'Front Desk Role',
                 'is_active' => true,
                 'user_id' => $deskId,
                 'created_at' => now(),
                 'updated_at' => now(),
             ],
         ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('special_roles');
    }
};
