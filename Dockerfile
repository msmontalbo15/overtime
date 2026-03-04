FROM php:8.1-cli

# Install mysqli, curl, openssl, mbstring, gd
RUN docker-php-ext-install mysqli
RUN apt-get update && apt-get install -y \
    libcurl4-openssl-dev \
    libssl-dev \
    libpng-dev \
    libonig-dev \
    && docker-php-ext-install curl mbstring gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Copy php.ini to suppress deprecation warnings
COPY php.ini /usr/local/etc/php/conf.d/overtime.ini

# Copy app
WORKDIR /app
COPY . .

# Expose port and serve
EXPOSE 8080
CMD ["php", "-S", "0.0.0.0:8080", "-t", "/app"]
