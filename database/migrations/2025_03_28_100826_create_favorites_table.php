<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        //Crea la tabla con los siguietnes campos
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('pokemon_id', 50)->nullable();
            $table->string('trainer_id', 50)->nullable();
            $table->string('energy_id', 50)->nullable();

            $table->timestamps();

            //Crear índices primero
            $table->index('pokemon_id');
            $table->index('trainer_id');
            $table->index('energy_id');
        });

        //Añade claves foráneas en una migración separada o después de crear las tablas
        Schema::table('favorites', function (Blueprint $table) {
            //Verificacion de que las tablas y columnas existan antes de crear las claves foraneas
            if (Schema::hasTable('pokemons') && Schema::hasColumn('pokemons', 'pokemon_id')) {
                $table->foreign('pokemon_id')
                    ->references('pokemon_id')->on('pokemons')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            }

            if (Schema::hasTable('trainers') && Schema::hasColumn('trainers', 'trainer_id')) {
                $table->foreign('trainer_id')
                    ->references('trainer_id')->on('trainers')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            }

            if (Schema::hasTable('energies') && Schema::hasColumn('energies', 'energy_id')) {
                $table->foreign('energy_id')
                    ->references('energy_id')->on('energies')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            }
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
