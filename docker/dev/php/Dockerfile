# Use the official PHP image as the base
FROM php:8.4-fpm

# Set environment variables for user and group ID
ARG UID=1000
ARG GID=1000

# Install system dependencies and build libraries
RUN apt-get update && apt-get install -y --no-install-recommends \
    curl \
    unzip \
    libpq-dev \
    libonig-dev \
    libssl-dev \
    libxml2-dev \
    libcurl4-openssl-dev \
    libicu-dev \
    libzip-dev \
    default-mysql-client \
    && docker-php-ext-install -j$(nproc) \
    pdo_pgsql \
    pgsql \
    pdo_mysql \
    mysqli \
    opcache \
    intl \
    zip \
    bcmath \
    soap \
    mbstring \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && apt-get autoremove -y && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Set the working directory
WORKDIR /var/www

# Copy ini file to container
COPY ./docker/dev/php/php.ini /usr/local/etc/php/conf.d/php.ini

# Copy the entrypoint script
COPY ./docker/dev/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Change the default command to run the entrypoint script
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
