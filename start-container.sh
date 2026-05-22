#!/bin/bash

set -e

if [ "$IS_LARAVEL" = "true" ]; then
    if [ "$RAILPACK_SKIP_MIGRATIONS" != "true" ]; then
        echo "Running migrations..."
        php artisan migrate --force --no-interaction
    fi

    php artisan storage:link || true
    php artisan optimize:clear
    php artisan optimize

    echo "Starting Laravel server..."
fi

exec docker-php-entrypoint --config /Caddyfile --adapter caddyfile 2>&1
