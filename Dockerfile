# Use PHP 8.2 with Apache
FROM php:8.2-apache

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libonig-dev libxml2-dev zip curl \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Enable Apache rewrite module
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Copy project files into container
COPY . /var/www/html

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Update Apache config to serve Laravel's public folder
RUN sed -i 's#/var/www/html#/var/www/html/public#g' /etc/apache2/sites-available/000-default.conf

# 🧩 Fix permissions for Laravel
RUN chmod -R 755 /var/www/html && \
    chown -R www-data:www-data /var/www/html && \
    chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]

# Generate application key automatically
RUN php artisan key:generate

