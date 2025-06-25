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

# Copy existing app files
COPY . .

# Set proper permissions
RUN chown -R appuser:appuser /var/www

# Switch to non-root user before composer install
USER appuser

# Install Laravel dependencies (as non-root)
RUN composer install --no-dev --optimize-autoloader

# Switch back to root if needed later
USER root

# Expose the port Laravel will run on
EXPOSE 10000

# Start Laravel server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]
