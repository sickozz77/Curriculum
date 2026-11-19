<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('textos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome', 70)-> nullable(false);
            $table->integer('area_id')-> nullable(false) -> default(0);
            $table->enum('status', ['A', 'I'])-> default('A');
            $table->timestamps();
            $table->longText('obs')-> nullable(true);
            $table->longText('texto')-> default('Texto Modelo');
            $table->string('avatar', 100)-> default('texto.png');
            $table->string('nmtexto', 100)-> nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('textos');
    }
};
