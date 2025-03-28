<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('pokemon_id');
            $table->timestamps();

            $table->foreign('pokemon_id')->references('pokemon_id')->on('pokemons')->onDelete('cascade');
            $table->unique(['user_id', 'pokemon_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('favorites');
    }
};
