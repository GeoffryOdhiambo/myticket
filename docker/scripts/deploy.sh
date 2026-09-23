#!/bin/bash
# Run from /opt/apps/tiko on the server (GitHub Actions does this on every
# push to master). Pulls, rebuilds, restarts, migrates, health-checks.
set -euo pipefail

cd "$(dirname "$0")/../.."

BRANCH=master

echo "==> Pulling latest code"
git fetch origin "$BRANCH"
git reset --hard "origin/$BRANCH"

local_head="$(git rev-parse HEAD)"
echo "==> On commit $local_head"

echo "==> Building images"
docker compose build --build-arg GIT_SHA="$local_head"

echo "==> Recreating containers"
docker compose up -d --remove-orphans

echo "==> Waiting for mysql to be healthy"
until [ "$(docker inspect -f '{{.State.Health.Status}}' tiko_mysql 2>/dev/null)" = "healthy" ]; do
    sleep 2
done

echo "==> Running migrations"
for attempt in $(seq 1 10); do
    if docker compose exec -T app php artisan migrate --force; then
        break
    fi
    if [ "$attempt" -eq 10 ]; then
        echo "==> FAILED: migrations did not succeed after 10 attempts"
        exit 1
    fi
    echo "==> Database not ready yet, retrying in 3s ($attempt/10)"
    sleep 3
done

echo "==> Restarting queue worker and web"
docker compose restart queue web

echo "==> Health check"
health_ok=0
for attempt in $(seq 1 15); do
    if docker compose exec -T web wget -q -O /dev/null http://127.0.0.1/up; then
        health_ok=1
        break
    fi
    sleep 2
done

if [ "$health_ok" -ne 1 ]; then
    echo "==> FAILED: site did not respond after deploy. Recent logs:"
    docker compose logs --tail=40 app web
    exit 1
fi
echo "==> Site responded OK"

echo "==> Cleaning up dangling images"
docker image prune -f >/dev/null

echo "==> Done."
