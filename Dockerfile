FROM php:8.2.3-fpm

RUN apt-get clean
RUN apt-get update

# mysqli pdo_mysql
RUN docker-php-ext-install mysqli pdo_mysql

# Zip
RUN apt-get install -y \
        libzip-dev \
        zip \
  && docker-php-ext-install zip

# Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Scripts auxiliares para ejecutar tests
RUN echo '#!/bin/bash\nphp artisan test' > /usr/bin/run-test && \
    chmod +x /usr/bin/run-test

RUN echo '#!/bin/bash\nXDEBUG_MODE=coverage php artisan test --coverage' > /usr/bin/run-test-coverage && \
    chmod +x /usr/bin/run-test-coverage

RUN echo '#!/bin/bash\nXDEBUG_MODE=coverage php artisan test --coverage-html test_reports' > /usr/bin/run-test-coverage-html && \
    chmod +x /usr/bin/run-test-coverage-html

# instalar composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /app
CMD composer install --ignore-platform-reqs --no-scripts \
    && php artisan migrate:refresh --seed \
    && php artisan serve --host 0.0.0.0 --port 8005
    