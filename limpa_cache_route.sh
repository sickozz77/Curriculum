
# dar direito aooo arquivo chmod +x limpar_cache_laravel.sh
#!/bin/bash

# Navega até o diretório onde está o script
cd "$(dirname "$0")"

echo "⚠️  Este script irá limpar: rotas, configuração e cache do Laravel."
read -p "Deseja continuar? (s/n): " CONFIRMA

if [[ "$CONFIRMA" == "s" || "$CONFIRMA" == "S" ]]; then
    echo ""
    echo "🔄 Limpando rotas Laravel..."
    php artisan route:clear

    echo "🧹 Limpando configuração Laravel..."
    php artisan config:clear

	echo "🧼 Otimizar cache Laravel..."
	php artisan optimize:clear

    echo "🧼 Limpando cache Laravel..."
    php artisan cache:clear
	
	echo ""
    echo "✅ Finalizado com sucesso."
else
    echo "❌ Operação cancelada pelo usuário."
fi