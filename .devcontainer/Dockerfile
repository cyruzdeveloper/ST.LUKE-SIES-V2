FROM mcr.microsoft.com/vscode/devcontainers/php:8.1

# Install mysql client, unzip and git, enable xdebug and install composer
RUN apt-get update && export DEBIAN_FRONTEND=noninteractive \  
  && apt-get -y install --no-install-recommends default-mysql-client unzip git \  
  && pecl install xdebug || true \  
  && docker-php-ext-enable xdebug || true \  
  && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \  
  && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \  
  && php -r "unlink('composer-setup.php');"

ENV XDEBUG_MODE=off

# Set working directory
WORKDIR /workspace

# Default user
USER vscode
