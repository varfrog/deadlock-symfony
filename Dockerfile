FROM php:7.4-fpm-alpine

RUN apk add --update --no-cache \
    coreutils

RUN docker-php-ext-install pdo_mysql

RUN echo "$(curl -sS https://composer.github.io/installer.sig) -" > composer-setup.php.sig \
        && curl -sS https://getcomposer.org/installer | tee composer-setup.php | sha384sum -c composer-setup.php.sig \
        && php composer-setup.php && rm composer-setup.php* \
        && chmod +x composer.phar && mv composer.phar /usr/bin/composer

COPY . /usr/src/app
WORKDIR /usr/src/app
