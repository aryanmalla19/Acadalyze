# Use a specific PHP version with Apache
FROM php:8.2-apache

# Set the working directory to /var/www/html
WORKDIR /var/www/html

# Install required system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Copy composer.json and composer.lock (if present) to install dependencies
COPY composer.json composer.lock* ./

# Install PHP dependencies using Composer
RUN composer install --no-scripts --no-interaction --optimize-autoloader

# Copy the rest of the backend code to the container
COPY . /var/www/html

# Set permissions for Apache
RUN chown -R www-data:www-data /var/www/html \
    && find /var/www/html -type d -exec chmod 755 {} \; \
    && find /var/www/html -type f -exec chmod 644 {} \;

# Copy custom Apache virtual host configuration to serve /public as the document root
COPY docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# Enable Apache modules if needed (e.g., rewrite)
RUN a2enmod rewrite

# Expose port 80 (inside the container)
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]