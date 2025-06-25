FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git \
    libzip-dev \
    libpq-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl pdo_pgsql pgsql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create a non-root user
RUN adduser --disabled-password --gecos "" appuser

# Set working directory
WORKDIR /var/www

# Copy application files
COPY . .

# Copy or create .env file if needed
RUN cp .env.example .env

# Set correct permissions for Laravel
RUN chmod -R 775 storage bootstrap/cache && \
    chown -R appuser:appuser /var/www

# Switch to non-root user
USER appuser

# Install Laravel dependencies without running scripts
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Manually run package discovery (failsafe with || true)
RUN php artisan package:discover --ansi || true

# Switch back to root (if needed)
USER root

# Expose Laravel app port
EXPOSE 10000

# Start Laravel server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]
