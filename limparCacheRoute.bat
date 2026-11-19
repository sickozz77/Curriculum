@echo off
cd /d %~dp0

echo Limpando rotas...
php artisan route:clear

echo Limpando configuração...
php artisan config:clear

echo Limpando cache...
php artisan cache:clear

echo Operação concluída.
pause
