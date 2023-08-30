FROM php:8.2.9-apache-bullseye

ENV APACHE_DOCUMENT_ROOT /var/www/html/public
ENV COMPOSER_ALLOW_SUPERUSER=1
ARG SERVICE_NAME

RUN a2enmod rewrite

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN apt-get update && apt install -y acl zip unzip libpq-dev librabbitmq-dev

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-install pdo pdo_pgsql
RUN docker-php-ext-enable pdo pdo_pgsql

RUN pecl install amqp \
	&& docker-php-ext-enable amqp

RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash
RUN apt install -y symfony-cli

COPY init-project.sh /usr/local/bin/init-project.sh
RUN chmod +x /usr/local/bin/init-project.sh

COPY ${SERVICE_NAME}-workers.sh /usr/local/bin/${SERVICE_NAME}-workers.sh
RUN chmod +x /usr/local/bin/${SERVICE_NAME}-workers.sh


WORKDIR /var/www/html

CMD init-project.sh; ${SERVICE_NAME}-workers.sh; apache2-foreground