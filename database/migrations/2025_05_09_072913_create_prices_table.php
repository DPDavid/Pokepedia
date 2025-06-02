<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        //Crea la tabla con los siguientes campos
        Schema::table('pokemons', function (Blueprint $table) {
            $table->decimal('price_low', 8, 2)->nullable()->after('image_large');
            $table->decimal('price_high', 8, 2)->nullable()->after('price_low');
            $table->string('tcgplayer_url')->nullable()->after('price_high');
        });
    }

    public function down(): void
    {
        //Elimina la tabla de precios
        Schema::table('pokemons', function (Blueprint $table) {
            $table->dropColumn(['price_low', 'price_high', 'tcgplayer_url']);
        });
    }
};
