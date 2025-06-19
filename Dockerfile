# Stage 1: Build React frontend
FROM node:20-alpine AS frontend
WORKDIR /app/frontend
COPY frontend/package.json ./
RUN corepack enable && yarn install
COPY frontend/ .
RUN yarn build

# Stage 2: Install Yii backend dependencies
FROM php:8.1-cli-alpine AS backend
WORKDIR /app/backend
RUN apk add --no-cache git unzip curl
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY backend/ .
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Stage 3: Final runtime image
FROM php:8.1-cli-alpine

# Install only required packages (MySQL extension + netcat)
RUN apk add --no-cache mysql-client \
    && docker-php-ext-install pdo_mysql

WORKDIR /var/www/html

# Copy Yii backend (with vendor folder from backend stage)
COPY --from=backend /app/backend .

# Copy React frontend build into web/ directory
COPY --from=frontend /app/frontend/dist ./web/

# Copy entrypoint script
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 9000

ENTRYPOINT ["/entrypoint.sh"]
# Use web/index.php as the router so pretty URLs work with the built-in server
CMD ["php", "-S", "0.0.0.0:9000", "-t", "web", "web/index.php"]
