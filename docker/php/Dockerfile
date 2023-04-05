from php:8.1-fpm-alpine


ARG UID=1000
ARG GID=1000
ENV UID=${UID}
ENV GID=${GID}

# Install required packages
RUN apk update && apk upgrade \
    && apk add --no-cache linux-headers alpine-sdk curl zip unzip bash icu-dev libxml2-dev libzip-dev git shadow \
    && apk add --no-cache --virtual .build-deps autoconf g++ make \
    && usermod -u $UID www-data && groupmod -g $GID www-data \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && docker-php-ext-install -j$(nproc) bcmath intl pdo_mysql soap \
    && docker-php-ext-install dom \
    && docker-php-ext-install simplexml \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && curl -L https://squizlabs.github.io/PHP_CodeSniffer/phpcs.phar -o phpcs && chmod a+x phpcs && mv phpcs /usr/local/bin/phpcs \
    && curl -L https://squizlabs.github.io/PHP_CodeSniffer/phpcbf.phar -o phpcbf && chmod a+x phpcbf && mv phpcbf /usr/local/bin/phpcbf \
    && mkdir -p /home/www-data/.composer \
    && chown -R www-data:www-data /home/www-data/.composer \

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add Composer to PATH
ENV PATH="/var/www/vendor/bin:${PATH}"
USER www-data

