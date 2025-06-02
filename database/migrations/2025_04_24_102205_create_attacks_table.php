<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        //Crea la tabla con los siguientes campos
        Schema::create('attacks', function (Blueprint $table) {
            $table->id();
            $table->string('pokemon_id');
            $table->string('name');
            $table->string('cost')->nullable();
            $table->integer('converted_energy_cost')->nullable();
            $table->string('damage')->nullable();
            $table->text('text')->nullable();
            $table->timestamps();

            //Clave foranea con pokemon_id
            $table->foreign('pokemon_id')->references('pokemon_id')->on('pokemons')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        //Elimina la tabla de ataques
        Schema::dropIfExists('attacks');
    }
};
