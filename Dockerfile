# Use an official PHP runtime as a parent image
FROM php:8.2-apache

# Set the working directory to /var/www/html
WORKDIR /var/www/html

# Copy the current directory contents into the container at /var/www/html
COPY . /var/www/html

# Install dependencies and enable extensions
RUN apt-get update && \
    apt-get install -y \
        git \
        zip \
        unzip \
        libicu-dev \
        zlib1g-dev \
        libzip-dev \
        libxml2-dev \
        curl \
        nodejs \
        npm \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev && \
    docker-php-ext-install pdo_mysql intl zip gd && \
    pecl install redis && \
    docker-php-ext-enable redis && \
    a2enmod rewrite

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Expose port 80 to the outside world
EXPOSE 80

# CMD specifies the command to run on container st
CMD ["apache2-foreground"]
