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
        Schema::create('audits', function (Blueprint $table) {
            $table->id();

            // Usuário que executou a ação (sem FK por segurança)
            $table->unsignedBigInteger('user_id')->nullable();

            // Ação executada
            $table->string('action', 50); // create | update | delete | login | logout

            // Tabela afetada
            $table->string('table_name');

            // ID do registro afetado
            $table->unsignedBigInteger('record_id')->nullable();

            // Dados antes e depois
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();

            // Contexto da requisição
            $table->ipAddress('ip')->nullable();
            $table->string('user_agent')->nullable();

            $table->timestamps();

            // Índices (performance e relatórios)
            $table->index('user_id');
            $table->index('action');
            $table->index(['table_name', 'record_id']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audits');
    }
};