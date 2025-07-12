#!/bin/sh
set -e

cp .env.example .env

# Install Composer and dependencies
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-dev --optimize-autoloader --no-interaction --no-progress --prefer-dist

# Check if $UID and $GID are set, else fallback to default (1000:1000)
# USER_ID=${UID:-1000}
# GROUP_ID=${GID:-1000}

# chown -R ${USER_ID}:${GROUP_ID} /usr/local/bin

# Initialize storage directory if empty
# -----------------------------------------------------------
# If the storage directory is empty, copy the initial contents
# and set the correct permissions.
# -----------------------------------------------------------
if [ ! "$(ls -A /var/www/storage)" ]; then
  echo "Initializing storage directory..."
  cp -R /var/www/storage-init/. /var/www/storage
fi

php artisan key:generate

# Fix file ownership and permissions using the passed UID and GID
# echo "Fixing file permissions with UID=${USER_ID} and GID=${GROUP_ID}..."
# chown -R ${USER_ID}:${GROUP_ID} /var/www || echo "Some files could not be changed"

php artisan migrate --force

# Clear configurations to avoid caching issues in development
echo "Clearing configurations..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Run the default command (e.g., php-fpm or bash)
exec "$@"