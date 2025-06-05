# Stage 1 - build frontend
FROM node:20 AS frontend
WORKDIR /app/frontend
COPY frontend/package.json ./
RUN corepack enable && yarn install
COPY frontend/ .
RUN yarn build

# Stage 2 - install backend dependencies
FROM php:8.1-cli AS backend
ENV PATH="/usr/local/bin:$PATH"
WORKDIR /app/backend
RUN apt-get update && apt-get install -y git unzip curl \
    && rm -rf /var/lib/apt/lists/*
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY backend/ .
RUN composer install --no-interaction --prefer-dist

# Stage 3 - runtime image
FROM php:8.1-cli
RUN apt-get update && apt-get install -y default-mysql-client \
    && docker-php-ext-install pdo_mysql \
    && rm -rf /var/lib/apt/lists/*
WORKDIR /var/www/html
COPY --from=backend /app/backend .
COPY --from=frontend /app/frontend/dist ./web/
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
EXPOSE 9000
ENTRYPOINT ["/entrypoint.sh"]
CMD ["php", "-S", "0.0.0.0:9000", "-t", "web"]
