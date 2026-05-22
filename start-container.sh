#!/bin/bash

set -e

if [ "$IS_LARAVEL" = "true" ]; then
    php artisan storage:link || true
    php artisan optimize:clear
    php artisan optimize

    echo "Starting Laravel server..."
fi

exec docker-php-entrypoint --config /Caddyfile --adapter caddyfile 2>&1
