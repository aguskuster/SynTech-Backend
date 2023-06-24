# Base image
FROM php:7.4-apache

# Set working directory
WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo_mysql zip

# Enable Apache modules and configure
RUN a2enmod rewrite
COPY docker/apache/vhost.conf /etc/apache2/sites-available/000-default.conf

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy project files
COPY . .

# Install project dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Generate application key
RUN php artisan key:generate

# Set file permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Expose port
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]



