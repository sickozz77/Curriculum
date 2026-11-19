<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Log;

class ListarLogs extends Command
{
    /**
     * Nome do comando Artisan.
     *
     * @var string
     */
    protected $signature = 'listar:logs';

    /**
     * Descrição do comando.
     *
     * @var string
     */
    protected $description = 'Lista os últimos logs salvos no banco de dados';

    /**
     * Executa o comando.
     *
     * @return int
     */
    public function handle()
    {
        $logs = Log::orderBy('id', 'desc')->limit(10)->get();

        if ($logs->isEmpty()) {
            $this->info('Nenhum log encontrado.');
            return Command::SUCCESS;
        }

        foreach ($logs as $log) {
            $this->info("======================================================================");
            $this->line("ID: " . $log->id);
            $this->line("Nível: " . $log->level);
            $this->line("Mensagem: " . $log->message);
            $this->line("Canal: " . $log->channel);
            $this->line("Data: " . $log->datetime);

            $ip = is_numeric($log->ip_address)
                ? long2ip((int) $log->ip_address)
                : $log->ip_address;
            $this->line("IP: " . $ip);
        }
        return Command::SUCCESS;
    }
}
