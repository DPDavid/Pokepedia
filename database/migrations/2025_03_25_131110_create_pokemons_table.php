<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        //Crea la tabla con los siguientes campos
        Schema::create('pokemons', function (Blueprint $table) {
            $table->id(); // ID autoincremental
            $table->string('pokemon_id')->unique();
            $table->string('name');
            $table->string('supertype');
            $table->string('level')->nullable();
            $table->integer('hp');
            $table->string('type')->nullable();
            $table->string('evolves_from')->nullable();
            $table->text('flavor_text')->nullable();
            $table->string('rarity')->nullable();
            $table->integer('national_pokedex_number')->nullable();
            $table->string('image_small')->nullable();
            $table->string('image_large')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        //Elimina la tabla pokemons
        Schema::dropIfExists('pokemons');
    }
};
