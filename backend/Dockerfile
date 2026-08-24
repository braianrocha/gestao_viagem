FROM php:8.2-cli

# Dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
 && docker-php-ext-install pdo pdo_mysql

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copia o projeto
COPY . .

# Instala dependências PHP
RUN composer install --no-interaction --prefer-dist

RUN chmod +x docker/entrypoint.sh

# Porta da API
EXPOSE 8000

# Aguarda o banco, roda migrations e sobe o Laravel
ENTRYPOINT ["docker/entrypoint.sh"]
