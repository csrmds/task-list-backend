# Imagem base PHP com extensões necessárias
FROM php:8.2-fpm

# Instala dependências do sistema e extensões PHP
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instala o Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Define o diretório de trabalho
WORKDIR /var/www/html

# Copia os arquivos do projeto
COPY . .

# Instala dependências PHP (sem dev, para produção)
RUN composer install --no-dev --optimize-autoloader

# Ajusta permissões do storage e bootstrap/cache
RUN chown -R www-data:www-data storage bootstrap/cache

# Porta padrão do Laravel (php artisan serve)
EXPOSE 8000

# Comando para rodar a aplicação
CMD php artisan serve --host=0.0.0.0 --port=8000