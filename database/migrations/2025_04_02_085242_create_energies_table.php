<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        //Crea la tabla con los siguientes campos
        Schema::create('energies', function (Blueprint $table) {
            $table->id();
            $table->string('energy_id')->unique();
            $table->string('name');
            $table->string('supertype');
            $table->json('subtypes');
            $table->string('number');
            $table->string('artist');
            $table->json('legalities');
            $table->string('image_small');
            $table->string('image_large');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        //Elimina la tabla energias
        Schema::dropIfExists('energies');
    }
};
