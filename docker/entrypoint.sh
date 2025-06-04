#!/bin/sh
set -e
if [ -n "$DB_HOST" ]; then
  echo "Waiting for MySQL at $DB_HOST..."
  until mysqladmin ping -h"$DB_HOST" --silent; do
    sleep 2
  done
fi
php yii migrate --interactive=0
exec "$@"
