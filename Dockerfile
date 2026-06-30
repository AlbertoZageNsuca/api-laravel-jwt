FROM php:8.2-fpm

# Instala dependências do sistema e extensões PHP necessárias
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    nginx

RUN docker-php-ext-install pdo_pgsql

# Configura o diretório de trabalho
WORKDIR /var/www

# Copia o projeto
COPY . /var/www

# Instala o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Configura permissões
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Expõe a porta e inicia o servidor
CMD php artisan serve --host=0.0.0.0 --port=$PORT
