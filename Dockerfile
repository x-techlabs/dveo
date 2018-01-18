FROM php:5-fpm

RUN apt-get update -qq && \
    apt-get install -y \
        libpq-dev libmcrypt-dev libxml2-dev \
        nginx nodejs npm cron git

# install composer and zip
RUN docker-php-ext-install zip && \
    docker-php-ext-install pdo_mysql && \
    docker-php-ext-install mcrypt && \
    docker-php-ext-install soap && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
#    pear install channel://pear.php.net/XML_Serializer-0.21.0

RUN npm install -g n \
    && n stable \
    && npm install -g npm
#    && npm install -g webpack

ADD . /var/www

ADD ./docker/scripts /scripts
RUN chmod 755 /scripts/*.sh

WORKDIR /var/www

RUN composer install \
    && npm install

# RUN npm run production

#copy config files
ADD docker/nginx/default.conf /etc/nginx/sites-available/default
ADD docker/nginx/nginx.conf /etc/nginx/

#copy cron
ADD docker/cron /etc/cron.d/laravel
RUN /usr/bin/crontab /etc/cron.d/laravel

#show logs in a console
RUN ln -sf /dev/stdout /var/log/nginx/access.log \
	&& ln -sf /dev/stderr /var/log/nginx/error.log

EXPOSE 80
CMD bash -C '/scripts/start.sh';'bash'
