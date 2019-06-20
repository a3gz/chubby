FROM php:7.1-apache

# Make the environment variable's value available to all layers
ENV APP_HOME /var/www/html

RUN apt-get update && apt-get install -y \
      libicu-dev \
      libpq-dev \
      libmcrypt-dev \
      git \
      zlib1g-dev \
      unzip \
    && rm -r /var/lib/apt/lists/* \
    && docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd \
    && docker-php-ext-install \
      intl \
      mbstring \
      mcrypt \
      pcntl \
      pdo_mysql \
      pdo_pgsql \
      pgsql \
      zip \
      opcache \
      bcmath

RUN curl --silent --show-error https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

#change the web_root to $APP_HOME folder
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

RUN sed -i -e "s/html/html/g" /etc/apache2/sites-enabled/000-default.conf

RUN a2enmod rewrite

COPY . $APP_HOME

RUN composer install -d $APP_HOME/src --no-interaction

RUN chown -R www-data:www-data $APP_HOME
