#!/bin/sh

echo "⏳ Waiting for MySQL to be ready..."
until mysqladmin ping -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" --silent; do
  echo "⏳ Still waiting for MySQL..."
  sleep 5
done

echo "✅ MySQL is ready. Ensuring database exists..."
mysql -h$DB_HOST -u$DB_USERNAME -p$DB_PASSWORD -e "CREATE DATABASE IF NOT EXISTS $DB_DATABASE;"

echo "✅ Running Laravel setup..."
composer install
php artisan config:clear
php artisan key:generate --force
php artisan migrate --force

php artisan serve --host=0.0.0.0 --port=8000
