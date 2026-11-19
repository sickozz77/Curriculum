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
        Schema::create('documentos', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('processo_id')->unsigned();
            $table->string('descricao', 100) -> nullable(false);
            $table->string('tipo', 70) -> nullable(false);
            $table->date('dt_disponibilizacao')->nullable()->after('descricao');
            $table->date('dt_publicacao')->nullable()->after('dt_disponibilizacao');
            $table->string('identificador_externo', 50)->nullable();
            $table->integer('pagina')->nullable();
            $table->string('fonte_publicacao', 100)->nullable(); // Ex: DJEN
            $table->text('url_validacao')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos');
    }
};
