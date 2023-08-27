FROM php:8.2.9-apache-bullseye

ENV APACHE_DOCUMENT_ROOT /var/www/html/public
ENV COMPOSER_ALLOW_SUPERUSER=1

RUN a2enmod rewrite

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN apt-get update && apt install -y acl zip unzip libpq-dev

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-install pdo pdo_pgsql
RUN docker-php-ext-enable pdo pdo_pgsql

#RUN docker-php-ext-install zlib; exit 0
#RUN cp /usr/src/php/ext/zlib/config0.m4 /usr/src/php/ext/zlib/config.m4
#And try to install extension again, this time it works
#RUN docker-php-ext-install zlib

#RUN docker-php-ext-install zlib
#RUN docker-php-ext-enable zlib zip

RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash
RUN apt install -y symfony-cli

#RUN cd /var/www/html && composer install
#RUN composer install

#RUN symfony new . --version="6.3.*"

#RUN HTTPDUSER=$(ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1) \
#    && setfacl -dR -m u:"$HTTPDUSER":rwX -m u:$(whoami):rwX /var/www/html/var \
#    && setfacl -R -m u:"$HTTPDUSER":rwX -m u:$(whoami):rwX /var/www/html/var

#COPY init-project.sh /usr/local/bin/init-project.sh
#RUN chmod +x /usr/local/bin/init-project.sh
#
#ENTRYPOINT /usr/local/bin/init-project.sh

WORKDIR /var/www/html