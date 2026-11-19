# Etapa 1: Composer para instalar dependências
FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --prefer-dist

# Etapa 2: Laravel rodando com Apache
FROM php:8.2-apache

# Instala dependências do sistema
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libpng-dev libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip unzip curl git \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Ativa mod_rewrite para Laravel
RUN a2enmod rewrite

# Define diretório público do Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Ajusta o DocumentRoot no Apache
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf

# Copia o projeto Laravel
COPY . /var/www/html

# Copia os vendors da etapa 1
COPY --from=vendor /app/vendor /var/www/html/vendor

# Ajusta permissões
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

WORKDIR /var/www/html

EXPOSE 80
