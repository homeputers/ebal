#!/bin/sh
set -e

# Ensure ENV_SERVER_NAME is available for envsubst
# If ENV_SERVER_NAME is not set, Nginx will use 'default_server' from the config.
# You might want to add error handling here if ENV_SERVER_NAME is mandatory.
export ENV_SERVER_NAME

# Substitute the environment variable into the Nginx config template.
# Only $ENV_SERVER_NAME will be substituted.
envsubst '$ENV_SERVER_NAME' < /etc/nginx/templates/default.conf.template > /etc/nginx/conf.d/default.conf

# Execute the CMD passed to the entrypoint (e.g., nginx -g 'daemon off;')
exec "$@"
