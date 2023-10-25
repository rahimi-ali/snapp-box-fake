FROM php:8.2-fpm as base
# Install system dependencies
RUN apt-get update && apt-get install -y \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
# Install PHP extensions
RUN docker-php-ext-install mbstring exif pcntl bcmath gd zip pdo_mysql
# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
# Install node
ENV NVM_DIR /usr/local/nvm
ENV NODE_VERSION 18.18.0
RUN mkdir $NVM_DIR
RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.5/install.sh | bash \
    && . $NVM_DIR/nvm.sh \
   && nvm install $NODE_VERSION \
   && nvm alias default $NODE_VERSION \
   && nvm use default
ENV NODE_PATH $NVM_DIR/v$NODE_VERSION/lib/node_modules
ENV PATH      $NVM_DIR/v$NODE_VERSION/bin:$PATH




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
