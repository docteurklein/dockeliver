FROM php:7-fpm
RUN apt-get update && apt-get install -y git zlib1g-dev
RUN docker-php-ext-install mbstring zip
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
WORKDIR /code
VOLUME ["/code"]
COPY composer.json /code/composer.json
COPY composer.lock /code/composer.lock
RUN composer install --prefer-dist --ignore-platform-reqs
RUN echo "date.timezone=UTC" >> /usr/local/etc/php/php.ini
RUN echo "variables_order=EGPCS" >> /usr/local/etc/php/php.ini
ADD . /code
