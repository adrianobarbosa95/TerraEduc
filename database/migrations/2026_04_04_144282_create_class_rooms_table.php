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
        Schema::create('classrooms', function (Blueprint $table) {
           $table->id();
          $table->string('name'); // nome completo
$table->string('modality'); // PROEJA, SUBSEQUENTE, PROEI, INTEGRADO
$table->integer('year'); // ano letivo
$table->integer('units'); // 2 ou 3
$table->string('period')->nullable(); 
// ex: "M1", "M2" para semestral
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_rooms');
    }
};
