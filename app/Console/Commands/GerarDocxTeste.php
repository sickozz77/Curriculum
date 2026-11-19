<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpOffice\PhpWord\PhpWord;
use Illuminate\Support\Facades\Storage;

class GerarDocxTeste extends Command
{
    protected $signature = 'gerar:docx-teste';
    protected $description = 'Gera um arquivo DOCX de teste com conteúdo formatado';

    public function handle()
    {
        $this->info('📝 Gerando arquivo DOCX de teste...');

        // Cria uma instância do documento
        $phpWord = new PhpWord();

        $section = $phpWord->addSection();

        $section->addTitle('Documento de Teste', 1);
        $section->addText('Este é um parágrafo de texto simples.');

        $section->addText('Este texto contém ', [], 'default');
        $section->addText('negrito', ['bold' => true]);
        $section->addText(', ', []);
        $section->addText('itálico', ['italic' => true]);
        $section->addText(' e uma lista:', []);

        $section->addListItem('Primeiro item');
        $section->addListItem('Segundo item');

        $section->addTextBreak(1);
        $section->addText('Gerado automaticamente via Laravel + PHPWord.', ['color' => '666666']);

        // Cria diretório se não existir
        Storage::makeDirectory('testes');

        // Salva o arquivo
        $filePath = storage_path('app/testes/exemplo.docx');
        $phpWord->save($filePath, 'Word2007');

        $this->info('✅ Arquivo gerado com sucesso em:');
        $this->line($filePath);

        return Command::SUCCESS;
    }
}
