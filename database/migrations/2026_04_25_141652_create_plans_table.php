<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('discipline_id')->constrained()->cascadeOnDelete();
            $table->foreignId('classroom_id')->constrained('classrooms')->cascadeOnDelete();

            $table->date('date');

            $table->text('content')->nullable();
            $table->string('slide')->nullable();
            $table->text('activity')->nullable();

            $table->timestamps();

            // evita duplicidade (muito importante)
            $table->unique(['discipline_id', 'classroom_id', 'date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('plans');
    }
};