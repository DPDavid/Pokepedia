<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('retreat_costs', function (Blueprint $table) {
            $table->id();
            $table->string('pokemon_id');
            $table->string('type');
            $table->timestamps();

            $table->foreign('pokemon_id')->references('pokemon_id')->on('pokemons')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retreat');
    }
};
