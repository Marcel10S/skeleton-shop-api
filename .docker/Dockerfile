FROM php:8.3-apache

# Set the timezone
ARG TIMEZONE=Europe/Warsaw
RUN ln -fs /usr/share/zoneinfo/${TIMEZONE} /etc/localtime \
    && dpkg-reconfigure --frontend noninteractive tzdata

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    wget \
    zlib1g-dev \
    g++ \
    git \
    libpq-dev \
    unzip \
    libicu-dev \
    libonig-dev \
    libzip-dev \
    libsodium-dev \
    libfreetype6-dev \
    libpng-dev \
    libjpeg-dev \
    libxslt1-dev \
    librabbitmq-dev \
    libssh-dev \
    libglib2.0-0 \
    libnss3 \
    libx11-6 \
    libfontconfig1 \
    libxcomposite1 \
    libxcursor1 \
    libxdamage1 \
    libxext6 \
    libxfixes3 \
    libxi6 \
    libxrandr2 \
    libxrender1 \
    libxss1 \
    libxtst6 \
    nano \
    logrotate \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        intl \
        mbstring \
        zip \
        opcache \
        gd \
        sodium \
    && pecl install apcu \
    && pecl install xdebug \
    && pecl install amqp \
    && docker-php-ext-enable \
        apcu \
        xdebug \
        amqp \
    && docker-php-ext-configure zip \
    && echo "xdebug.start_with_request=yes;" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal;" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_port=9003;" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.discover_client_host=0;" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "enable_post_data_reading = On;" >> /usr/local/etc/php/conf.d/docker-fpm.ini \
    && echo "post_max_size=9999999G;" >> /usr/local/etc/php/conf.d/docker-fpm.ini \
    && echo "upload_max_filesize=9999999G;" >> /usr/local/etc/php/conf.d/docker-fpm.ini \
    && echo "max_input_vars=9999999" >> /usr/local/etc/php/conf.d/docker-fpm.ini

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set environment variable to allow Composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER=1

# Set working directory
WORKDIR /var/www/html

# Copy existing application directory contents
COPY . .

# Install PHP dependencies
RUN composer install