<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

// class VerificarAmbienteWord extends Command
class TestarLogDatabase extends Command

{
    protected $signature = 'verificar:word';
    protected $description = 'Verifica se o ambiente está pronto para ler arquivos DOCX com PHPWord';

    public function handle()
    {
        $this->info('🔍 Verificando ambiente...');

        // 1. ZipArchive
        if (!class_exists('ZipArchive')) {
            $this->error('❌ A extensão PHP ZipArchive NÃO está habilitada.');
            return Command::FAILURE;
        }
        $this->info('✅ ZipArchive está habilitada.');

        // 2. PhpWord
        if (!class_exists(PhpWord::class)) {
            $this->error('❌ A biblioteca PhpWord NÃO está instalada.');
            return Command::FAILURE;
        }
        $this->info('✅ PhpOffice\\PhpWord está instalada.');

        // 3. Instanciar PhpWord
        try {
            $phpWord = new PhpWord();
            $this->info('✅ PhpWord instanciado.');
        } catch (\Throwable $e) {
            $this->error('❌ Erro ao instanciar PhpWord: ' . $e->getMessage());
            return Command::FAILURE;
        }

        // 4. Tentar ler um arquivo .docx de teste
        $filePath = storage_path('app/testes/exemplo.docx');
        if (!file_exists($filePath)) {
            $this->warn('⚠️ Arquivo de teste não encontrado em: ' . $filePath);
            $this->line('→ Coloque um arquivo .docx de teste em: storage/app/testes/exemplo.docx');
            return Command::SUCCESS;
        }

        $this->info('📄 Lendo conteúdo do arquivo: exemplo.docx');

        try {
            $doc = IOFactory::load($filePath, 'Word2007');
            $text = '';

            foreach ($doc->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    if (method_exists($element, 'getText')) {
                        $text .= $element->getText() . "\n";
                    }
                }
            }

            $this->info('✅ Conteúdo extraído com sucesso:');
            $this->line('--- INÍCIO ---');
            $this->line($text);
            $this->line('--- FIM ---');

        } catch (\Throwable $e) {
            $this->error('❌ Erro ao ler o .docx: ' . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
