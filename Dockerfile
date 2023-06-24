# Base image
FROM php:7.4-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy project files
COPY . .

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && a2enmod rewrite

# Copy vhost.conf file
COPY docker/apache/vhost.conf /etc/apache2/sites-available/000-default.conf

# Install project dependencies
RUN composer install --no-interaction --optimize-autoloader --no-scripts

# Generate application key
RUN php artisan key:generate

# Build assets
RUN php artisan mix --env=production

# Expose port
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]


