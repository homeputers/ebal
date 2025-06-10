#!/bin/sh
set -e

# Wait for MySQL if DB_HOST is set
if [ -n "$DB_HOST" ]; then
  echo "⏳ Waiting for MySQL at $DB_HOST..."
  until mysqladmin ping -h"$DB_HOST" --silent; do
    sleep 2
  done
  echo "✅ MySQL is available."
fi

# Run Yii migrations
echo "🚀 Running migrations..."
php yii migrate --interactive=0 || echo "⚠️ Migration failed or already applied"

# Run CMD from Dockerfile (e.g. php -S ...)
exec "$@"
