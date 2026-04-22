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
    Schema::create('evaluations', function (Blueprint $table) {
        $table->id();

        $table->foreignId('classroom_id')->constrained()->cascadeOnDelete();
        $table->foreignId('discipline_id')->constrained()->cascadeOnDelete();

        $table->integer('unit');

        $table->string('name');
        $table->text('description')->nullable();
        $table->date('date')->nullable();

        $table->decimal('value', 5,2);

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
