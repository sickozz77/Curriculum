<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

// Importar TODOS os comandos que você usa
use App\Console\Commands\TestarLogDatabase;
use App\Console\Commands\ListarLogs;
use App\Console\Commands\VerificarAmbienteWord;

class Kernel extends ConsoleKernel
{
    /**
     * Registrar comandos Artisan manualmente
     */
    protected $commands = [
        TestarLogDatabase::class,
        ListarLogs::class,
        VerificarAmbienteWord::class, // novo comando enviado
    ];

    /**
     * Registrar comandos automáticos
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
    }

    /**
     * Agenda de tarefas
     */
    protected function schedule(Schedule $schedule): void
    {
        // Você pode agendar comandos aqui
        // ex: $schedule->command('logs:limpar')->daily();
    }
}