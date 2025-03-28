FROM ubuntu:latest

# Install dependencies
RUN apt-get update && apt-get install -y \
    php \
    php-cli \
    php-mbstring \
    php-xml \
    php-bcmath \
    php-curl \
    php-zip \
    php-mysql \
    unzip \
    curl \
    git \
    && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . /var/www/html

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set permissions for Laravel
RUN chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose application port
EXPOSE 9000

# Start Laravel Server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=9000"]

