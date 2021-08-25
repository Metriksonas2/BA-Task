FROM php:8.0-apache
RUN apt-get update && apt-get upgrade -y
RUN docker-php-ext-install pdo_mysql bcmath
RUN a2enmod rewrite
RUN pecl install xdebug
COPY Docker/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN docker-php-ext-enable xdebug

ENV APACHE_DOCUMENT_ROOT /var/www/html/public
ENV APACHE_LOG_DIR /var/logs

COPY Docker/apache.conf /etc/apache2/sites-available/000-default.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html
COPY ./application /var/www/html/
RUN chown www-data:www-data -R .

EXPOSE 80
