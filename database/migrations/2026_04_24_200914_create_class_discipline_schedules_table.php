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
       Schema::create('class_discipline_schedules', function (Blueprint $table) {
    $table->id();

    $table->foreignId('classroom_id')->constrained()->onDelete('cascade');
    $table->foreignId('discipline_id')->constrained()->onDelete('cascade');

    $table->integer('day'); // 1=Seg, 2=Ter...
    $table->enum('shift', ['M','T','N']); // turno
    $table->string('slots'); // ex: 12, 345

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_discipline_schedules');
    }
};
