FROM php:5.6-fpm
RUN apt-get update && \
    apt-get -y install libmcrypt-dev librabbitmq-dev zlib1g-dev && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*
RUN docker-php-ext-install mbstring pcntl bcmath mcrypt
RUN pecl install channel://pecl.php.net/amqp-1.6.0beta2
RUN echo "extension=amqp.so" >> /usr/local/etc/php/php.ini && \
    echo "extension=raphf.so" >> /usr/local/etc/php/php.ini && \
    echo "extension=propro.so" >> /usr/local/etc/php/php.ini && \
    echo "extension=http.so" >> /usr/local/etc/php/php.ini && \
    echo "extension=apfd.so" >> /usr/local/etc/php/php.ini && \
    echo "extension=json_post.so" >> /usr/local/etc/php/php.ini && \
    echo "variables_order=EGPCS" >> /usr/local/etc/php/php.ini
RUN pecl install raphf && \
    pecl install propro && \
    pecl install apfd && \
    pecl install json_post && \
    pecl install pecl_http
WORKDIR /code
ADD . /code
