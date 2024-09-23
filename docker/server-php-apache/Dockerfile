FROM php:8.2-apache

RUN docker-php-ext-install pdo_mysql

COPY docker/server-php-apache/apache.conf /etc/apache2/sites-available/000-default.conf

COPY docker/server-php-apache/server-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/server-entrypoint.sh

WORKDIR /var/www/html/
COPY server/ .

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer

RUN composer install --no-interaction

ENTRYPOINT ["server-entrypoint.sh"]

CMD ["apache2-foreground"]
