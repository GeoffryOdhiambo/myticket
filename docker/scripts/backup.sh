#!/bin/bash
# Dumps the database and archives the storage volume into ./backups,
# pruning anything older than 14 days. Meant to be cron'd daily.
set -euo pipefail

cd "$(dirname "$0")/../.."
set -a; source .env; set +a

BACKUP_DIR="$(pwd)/backups"
TIMESTAMP="$(date +%Y%m%d-%H%M%S)"
mkdir -p "$BACKUP_DIR"

echo "==> Dumping database"
docker compose exec -T mysql mysqldump \
    -u root -p"${DB_ROOT_PASSWORD}" \
    --single-transaction --routines --triggers \
    "${DB_DATABASE}" | gzip > "${BACKUP_DIR}/db-${TIMESTAMP}.sql.gz"

echo "==> Archiving storage volume"
docker run --rm \
    -v tiko_storage:/data:ro \
    -v "${BACKUP_DIR}":/backup \
    alpine tar czf "/backup/storage-${TIMESTAMP}.tar.gz" -C /data .

echo "==> Pruning backups older than 14 days"
find "$BACKUP_DIR" -type f -mtime +14 -delete

echo "==> Done: db-${TIMESTAMP}.sql.gz, storage-${TIMESTAMP}.tar.gz"
