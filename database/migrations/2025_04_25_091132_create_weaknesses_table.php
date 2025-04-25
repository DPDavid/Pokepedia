<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('weaknesses', function (Blueprint $table) {
            $table->id();
            // Asegúrate de que pokemon_id sea de tipo string
            $table->string('pokemon_id'); 
            $table->string('type');
            $table->string('value');
            $table->timestamps();

            // Si quieres agregar la restricción de clave foránea puedes hacerlo así:
            $table->foreign('pokemon_id')->references('pokemon_id')->on('pokemons')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weaknesses');
    }
};