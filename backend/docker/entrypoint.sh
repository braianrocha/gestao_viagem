#!/bin/sh
set -e

if [ ! -f .env ]; then
    cp .env.example .env
fi

echo "Aguardando o banco de dados em ${DB_HOST}:${DB_PORT}..."
until php -r "new PDO('mysql:host=${DB_HOST};port=${DB_PORT}', getenv('DB_USERNAME'), getenv('DB_PASSWORD'));" 2>/dev/null; do
    sleep 1
done

php artisan key:generate --force
php artisan migrate --force

exec php artisan serve --host=0.0.0.0 --port=8000
