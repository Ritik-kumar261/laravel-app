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
        Schema::create('users_table', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('roll_number')->nullable();
            $table->string('phone_number')->nullable(); // Make phone number nullable if it's not required
            $table->enum('role', ['student', 'admin'])->default('student');
            $table->string('password');
            $table->string('url')->nullable(); // Add the URL column, nullable if not required

            // Use integer for whole numbers
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_table');
    }
};
