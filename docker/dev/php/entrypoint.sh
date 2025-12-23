#!/bin/sh
set -e

# run default entrypoint first
/usr/local/bin/docker-php-entrypoint

# Ensure composer dependencies are installed before running any artisan commands
echo "Installing composer dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader

# Fix file ownership and permissions using the passed UID and GID
echo "Fixing file permissions with UID=${USER_ID} and GID=${GROUP_ID}..."
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache || echo "Some files could not be changed"

# Ensure writable permissions
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Ensure Laravel writable directories exist and are writable by php-fpm (www-data)
echo "Ensuring writable directories and permissions for Laravel..."
mkdir -p \
  /var/www/storage \
  /var/www/storage/logs \
  /var/www/storage/framework \
  /var/www/storage/framework/cache \
  /var/www/storage/framework/sessions \
  /var/www/storage/framework/views \
  /var/www/bootstrap/cache

# Give php-fpm user (www-data) ownership of storage and cache directories
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache || true
chmod -R 775 /var/www/storage /var/www/bootstrap/cache || true

if ! grep -q '^APP_KEY=' /var/www/.env || [ -z "$(grep '^APP_KEY=' /var/www/.env | cut -d '=' -f2)" ]; then
    echo "Generating APP_KEY..."
    php artisan key:generate
fi

echo "Running migrations and seeders..."
php artisan migrate --force
php artisan db:seed --force

echo "Clearing configurations..."
php artisan optimize:clear

php artisan storage:link

exec "$@"
