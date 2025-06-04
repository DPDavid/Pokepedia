<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
    {
        //Crea la tabla con las relaciones directamente
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();

            //Relación con usuarios
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            //Relación con pokemons, trainers y energias
            $table->string('pokemon_id', 255)->nullable();
            $table->string('trainer_id', 255)->nullable();
            $table->string('energy_id', 255)->nullable();

            $table->timestamps();

            //Isndices
            $table->index('pokemon_id');
            $table->index('trainer_id');
            $table->index('energy_id');

            //Claves foráneas directas
            $table->foreign('pokemon_id')
                ->references('pokemon_id')->on('pokemons')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('trainer_id')
                ->references('trainer_id')->on('trainers')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('energy_id')
                ->references('energy_id')->on('energies')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down()
    {
        //Eliminar las claves foráneas primero
        Schema::table('favorites', function (Blueprint $table) {
            $table->dropForeign(['pokemon_id']);
            $table->dropForeign(['trainer_id']);
            $table->dropForeign(['energy_id']);
        });
        
        //Elimina la tabla favoritos
        Schema::dropIfExists('favorites');
    }
};
