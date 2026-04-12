# Use official PHP image
FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libsqlite3-dev

# Install PHP extensions needed by Laravel
RUN docker-php-ext-install pdo pdo_sqlite

# Install Composer (PHP package manager)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory inside container
WORKDIR /app

# Copy all project files into container
COPY . .

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# Create SQLite database file
RUN mkdir -p database && touch database/database.sqlite

# Start Laravel server (Render will provide PORT)
CMD php artisan serve --host=0.0.0.0 --port=$PORT