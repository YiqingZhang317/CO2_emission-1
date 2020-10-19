FROM php:7.2-apache

RUN curl -sS https://getcomposer.org/installer | \
    php -- --install-dir=/usr/bin/ --filename=composer
COPY composer.json ./

RUN composer install --no-scripts --no-autoloader
COPY . ./
RUN composer dump-autoload --optimize && \
	composer run-script post-install-cmd

