#!/bin/sh
set -e

if [ -n "$DB_HOST" ]; then
  echo "⏳ Waiting for MySQL at $DB_HOST..."
  while ! nc -z "$DB_HOST" 3306; do
    sleep 2
  done
  echo "✅ MySQL is available."
fi

echo "🚀 Running migrations..."
php yii migrate --interactive=0 || echo "⚠️ Migration failed or already applied"

exec "$@"
