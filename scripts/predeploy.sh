#!/bin/sh
set -e

echo "Pre-deploy: migrations..."
php artisan migrate --force --no-interaction

echo "Pre-deploy: idempotent seed..."
php artisan db:seed --force --no-interaction

echo "Pre-deploy: done."
