FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libwebp-dev \
    libjpeg62-turbo-dev \
    libxpm-dev \
    libfreetype6-dev \
    libonig-dev \
    libpq-dev \
    curl

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Configure extensions
RUN docker-php-ext-configure zip;
RUN docker-php-ext-configure gd \
    --prefix=/usr \
    --with-jpeg \
    --with-webp \
    --with-xpm \
    --with-freetype;

# Install extensions
RUN docker-php-ext-install zip \
    mbstring \
    exif \
    pcntl \
    pdo_mysql \
    pdo_pgsql \
    gd;

RUN php -r 'var_dump(gd_info());'

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add user for php application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Configure xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Change current user to www
USER www

# Expose port 9000 and start php-fpm server
EXPOSE 9000

CMD ["php-fpm"]
