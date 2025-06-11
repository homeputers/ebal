FROM nginx:alpine

# Install gettext for envsubst
RUN apk add --no-cache gettext

# Copy the Nginx config template (your existing default.conf)
COPY default.conf /etc/nginx/templates/default.conf.template

# Copy the entrypoint script
COPY entrypoint.sh /docker-entrypoint.sh
RUN chmod +x /docker-entrypoint.sh

ENTRYPOINT ["/docker-entrypoint.sh"]
# The default CMD for nginx:alpine is `nginx -g 'daemon off;'`, which will be executed by the entrypoint script.
CMD ["nginx", "-g", "daemon off;"]
