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
        Schema::create('compromissos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pessoa_id')->constrained()->onDelete('cascade');
            $table->string('titulo');
            $table->smallInteger('endereco_id')         -> nullable(false) -> default(0);
            $table->string('endereco') -> nullable(false);
            $table->smallInteger('nr')  -> nullable(false) ->default(0);
            $table->string('complemento') -> nullable(true);
            $table->smallInteger('esta_ibge')-> nullable(false) ->default(0);
            $table->Integer('muni_ibge')-> nullable(false) ->default(0);
            $table->string('cep')   -> nullable(false) ->default('0');
            $table->string('bairro')-> nullable(false) ->default('Bairro');
            $table->dateTime('data_hora');
            $table->string('local')->nullable();
            $table->text('descricao')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compromissos');
    }
};
