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
        Schema::create('pessoas', function (Blueprint $table) {

            $table->id();
            $table->rememberToken();
            $table->timestamps();

            $table->smallInteger('nivel')->default(1);
            $table->tinyInteger('status')->default(1);

            $table->string('nome', 70);
            $table->string('email', 70)->unique();

            $table->string('rg_ie', 15)->default('0000000000');
            $table->string('cpf_cnpj', 20)->default('00000000000000');
            $table->string('pis', 15)->default('00000000000');

            $table->integer('endereco_id')->default(0);

            $table->date('dt_nascimento')->default('1988-11-05');

            // avatar pode ser null → evita erro no update
            $table->string('avatar', 150)->nullable();

            // tipo de pessoa - correto
            $table->enum('pessoa_tp', ['F', 'J'])->default('F');

            // associado e associado_id
            $table->boolean('associado')->default(false);

            $table->unsignedBigInteger('associado_id')->nullable();

            // last_used_at — default atual via SQL
            $table->timestamp('last_used_at')->useCurrent();

            $table->longText('obs')->nullable();

            $table->tinyInteger('tipo')->default(0);

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pessoas');
    }
};
