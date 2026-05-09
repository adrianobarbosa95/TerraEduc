<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('slug')->unique()->nullable();
            $table->text('bio')->nullable();
            $table->string('photo')->nullable();

            $table->string('linkedin')->nullable();
            $table->string('github')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'slug',
                'bio',
                'photo',
                'linkedin',
                'github'
            ]);
        });
    }
};