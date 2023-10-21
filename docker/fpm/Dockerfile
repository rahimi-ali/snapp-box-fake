FROM php:8.2-fpm as base
# Install system dependencies
RUN apt-get update && apt-get install -y \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip
# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
# Install PHP extensions
RUN docker-php-ext-install mbstring exif pcntl bcmath gd zip pdo_mysql
# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer




FROM base as app
# Arguments defined in docker-compose.yml
ARG user
ARG uid
# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user \
    && mkdir -p /home/$user/.composer \
    && chown -R $user:$user /home/$user
# Set working directory
WORKDIR /var/www
USER $user




FROM base as worker
# Arguments defined in docker-compose.yml
ARG user
ARG uid
# Create system user to run Composer and Artisan Commands
RUN apt-get update && apt-get install -y supervisor && apt-get clean && rm -rf /var/lib/apt/lists/*
RUN mkdir -p /etc/supervisor/logs
RUN useradd -G www-data,root -u $uid -d /home/$user $user \
    && mkdir -p /home/$user/.composer \
    && chown -R $user:$user /home/$user \
    && chown -R $user:$user /etc/supervisor/logs
# Set working directory
WORKDIR /var/www
USER $user
CMD ["/usr/bin/supervisord", "-n", "-c",  "/etc/supervisor/supervisord.conf"]
