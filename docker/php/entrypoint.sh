#!/bin/sh
set -e

# Only prime caches when this container is about to serve as the
# long-running php-fpm process — never for one-off utility invocations
# (`docker compose run app php artisan migrate`, `key:generate --show`,
# etc.), which would otherwise get config:cache/route:cache/view:cache
# output (or errors) mixed into whatever the actual command's own
# stdout was meant to be.
if [ "$1" = "php-fpm" ]; then
    php artisan storage:link --force 2>/dev/null || true
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

exec "$@"
