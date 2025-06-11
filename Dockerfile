# Stage 1: Frontend - build React app
FROM node:20-alpine AS frontend
WORKDIR /app/frontend
COPY frontend/package.json ./
RUN corepack enable && yarn install
COPY frontend/ .
RUN yarn build

# Stage 2: Backend - install PHP/Yii dependencies
FROM php:8.1-cli-alpine AS backend
WORKDIR /app/backend
RUN apk add --no-cache mysql-client \
    && docker-php-ext-install pdo_mysql
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY backend/ .
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Stage 3: Runtime
FROM php:8.1-cli-alpine
WORKDIR /var/www/html

# Install only the necessary PHP extension
RUN docker-php-ext-install pdo_mysql

# Copy backend and frontend
COPY --from=backend /app/backend .
COPY --from=frontend /app/frontend/dist ./web/

# Entrypoint setup
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 9000
ENTRYPOINT ["/entrypoint.sh"]
CMD ["php", "-S", "0.0.0.0:9000", "-t", "web"]
