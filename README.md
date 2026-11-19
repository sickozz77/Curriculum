# CurriculumZR

Aplicação Laravel com autenticação, cadastro de pessoas e processos, permissões,
edição de documentos e integração em tempo real via Reverb.

## Requisitos

- PHP 8.2 ou superior
- Composer
- Node.js e npm
- MySQL/MariaDB

No Windows, o XAMPP pode fornecer o PHP e o MySQL. Ative o MySQL no painel do
XAMPP antes de executar as migrações.

## Instalação

Clone o projeto e entre na pasta:

```bash
git clone <url-do-repositorio>
cd curriculumZR
```

Instale as dependências PHP:

```bash
composer install
```

Copie o arquivo `.env.example`, renomeie a cópia para `.env` e altere nela as
informações do seu ambiente, principalmente:

```dotenv
DB_DATABASE=nome_do_banco
DB_USERNAME=root
DB_PASSWORD=
```

O banco informado precisa existir no MySQL. Se estiver vazio, execute as
migrações:

```bash
php artisan migrate
```

Instale as dependências JavaScript:

```bash
npm install
```

## Executar localmente em desenvolvimento

Use dois terminais. No primeiro:

```bash
npm run dev
```

No segundo:

```bash
php artisan serve
```

Acesse <http://127.0.0.1:8000>.

O `npm run dev` recompila CSS e JavaScript enquanto os arquivos são alterados.

## Executar sem o Vite

Se não for alterar CSS ou JavaScript, gere os arquivos frontend uma vez:

```bash
npm run build
```

Depois, basta executar:

```bash
php artisan serve
```

## Usando o Apache do XAMPP

Aponte o DocumentRoot do Apache para a pasta `public` do projeto:

```text
curriculumZR/public
```

Antes de abrir pelo Apache, gere os arquivos frontend:

```bash
npm run build
```

Não é necessário executar `php artisan serve` ao mesmo tempo que o Apache.

## Comandos úteis

```bash
php artisan optimize:clear
php artisan migrate
php artisan route:list
```

Os logs da aplicação ficam em `storage/logs/laravel.log`.

## Envio de alterações dos estudantes

Os estudantes devem trabalhar a partir deste projeto otimizado e enviar apenas
os arquivos que realmente modificaram. Exemplos:

- `resources/views/site/quemsomos.blade.php`
- `resources/views/site/curriculum/eddie.blade.php`
- imagens novas usadas pelas páginas

Não envie cópias completas do projeto nem estas pastas ou arquivos:

- `vendor/`
- `node_modules/`
- `public/build/`
- `storage/logs/`
- arquivos temporários ou caches

Cada estudante deve criar seu próprio `.env` a partir do `.env.example` e
informar nele os dados do banco local. O `.env` nunca deve ser enviado, pois
contém configurações e credenciais específicas do ambiente.

O ideal é enviar as alterações por Git, pull request ou um arquivo compactado
contendo somente as views e imagens modificadas, mantendo suas pastas originais.

## Tecnologias

- Laravel
- Livewire e Jetstream
- MySQL/MariaDB
- Vite
- Tailwind CSS
- Alpine.js
- Laravel Echo e Reverb

## Licença

O framework Laravel é distribuído sob a [licença MIT](https://opensource.org/licenses/MIT).
