<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('registration')->unique();
            $table->string('password');

            $table->foreignId('classroom_id')
                ->constrained()
                ->onDelete('cascade');

            // 🔐 IMPORTANTE PARA AUTH LARAVEL
            $table->rememberToken();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};