# Multi-stage Dockerfile for PHP versions
# Supports PHP 8.1, 8.2, 8.3, 8.4, 8.5
# Usage: docker build --target php81-dev -t akeneo/pim-php-dev:8.1 -f Dockerfile.php .
#        docker build --target php84-dev -t akeneo/pim-php-dev:8.4 -f Dockerfile.php .

# Base stage template - will be overridden by specific versions
FROM httpd:2.4-bullseye AS base-template

ENV PHP_CONF_DATE_TIMEZONE=UTC \
    PHP_CONF_MAX_EXECUTION_TIME=60 \
    PHP_CONF_MEMORY_LIMIT=512M \
    PHP_CONF_OPCACHE_VALIDATE_TIMESTAMP=0 \
    PHP_CONF_MAX_INPUT_VARS=1000 \
    PHP_CONF_UPLOAD_LIMIT=40M \
    PHP_CONF_MAX_POST_SIZE=40M

RUN echo 'APT::Install-Recommends "0" ; APT::Install-Suggests "0" ;' > /etc/apt/apt.conf.d/01-no-recommended && \
    echo 'path-exclude=/usr/share/man/*' > /etc/dpkg/dpkg.cfg.d/path_exclusions && \
    echo 'path-exclude=/usr/share/doc/*' >> /etc/dpkg/dpkg.cfg.d/path_exclusions && \
    apt-get update && \
    apt-get --yes install \
        apt-transport-https \
        ca-certificates \
        curl \
        supervisor \
        wget &&\
    wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg &&\
    sh -c 'echo "deb https://packages.sury.org/php/ bullseye main" > /etc/apt/sources.list.d/php.list'

# PHP 8.1 Base
FROM base-template AS base-php81
RUN apt-get update && \
    apt-get --yes install imagemagick \
        libmagickcore-6.q16-6-extra \
        ghostscript \
        php8.1-fpm \
        php8.1-cli \
        php8.1-intl \
        php8.1-opcache \
        php8.1-mysql \
        php8.1-zip \
        php8.1-xml \
        php8.1-gd \
        php8.1-grpc \
        php8.1-curl \
        php8.1-mbstring \
        php8.1-bcmath \
        php8.1-imagick \
        php8.1-apcu \
        php8.1-exif \
        php8.1-memcached \
        openssh-client \
        aspell \
        aspell-en aspell-es aspell-de aspell-fr && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* && \
    ln -sf /usr/sbin/php-fpm8.1 /usr/local/sbin/php-fpm && \
    usermod --uid 1000 www-data && groupmod --gid 1000 www-data && \
    mkdir /srv/pim && \
    sed -i "s#listen = /run/php/php8.1-fpm.sock#listen = 9000#g" /etc/php/8.1/fpm/pool.d/www.conf && \
    mkdir -p /run/php

COPY docker/build/akeneo.ini /etc/php/8.1/cli/conf.d/99-akeneo.ini
COPY docker/build/akeneo.ini /etc/php/8.1/fpm/conf.d/99-akeneo.ini

# PHP 8.2 Base
FROM base-template AS base-php82
RUN apt-get update && \
    apt-get --yes install imagemagick \
        libmagickcore-6.q16-6-extra \
        ghostscript \
        php8.2-fpm \
        php8.2-cli \
        php8.2-intl \
        php8.2-opcache \
        php8.2-mysql \
        php8.2-zip \
        php8.2-xml \
        php8.2-gd \
        php8.2-grpc \
        php8.2-curl \
        php8.2-mbstring \
        php8.2-bcmath \
        php8.2-imagick \
        php8.2-apcu \
        php8.2-exif \
        php8.2-memcached \
        openssh-client \
        aspell \
        aspell-en aspell-es aspell-de aspell-fr && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* && \
    ln -sf /usr/sbin/php-fpm8.2 /usr/local/sbin/php-fpm && \
    usermod --uid 1000 www-data && groupmod --gid 1000 www-data && \
    mkdir /srv/pim && \
    sed -i "s#listen = /run/php/php8.2-fpm.sock#listen = 9000#g" /etc/php/8.2/fpm/pool.d/www.conf && \
    mkdir -p /run/php

COPY docker/build/akeneo.ini /etc/php/8.2/cli/conf.d/99-akeneo.ini
COPY docker/build/akeneo.ini /etc/php/8.2/fpm/conf.d/99-akeneo.ini

# PHP 8.3 Base
FROM base-template AS base-php83
RUN apt-get update && \
    apt-get --yes install imagemagick \
        libmagickcore-6.q16-6-extra \
        ghostscript \
        php8.3-fpm \
        php8.3-cli \
        php8.3-intl \
        php8.3-opcache \
        php8.3-mysql \
        php8.3-zip \
        php8.3-xml \
        php8.3-gd \
        php8.3-grpc \
        php8.3-curl \
        php8.3-mbstring \
        php8.3-bcmath \
        php8.3-imagick \
        php8.3-apcu \
        php8.3-exif \
        php8.3-memcached \
        openssh-client \
        aspell \
        aspell-en aspell-es aspell-de aspell-fr && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* && \
    ln -sf /usr/sbin/php-fpm8.3 /usr/local/sbin/php-fpm && \
    usermod --uid 1000 www-data && groupmod --gid 1000 www-data && \
    mkdir /srv/pim && \
    sed -i "s#listen = /run/php/php8.3-fpm.sock#listen = 9000#g" /etc/php/8.3/fpm/pool.d/www.conf && \
    mkdir -p /run/php

COPY docker/build/akeneo.ini /etc/php/8.3/cli/conf.d/99-akeneo.ini
COPY docker/build/akeneo.ini /etc/php/8.3/fpm/conf.d/99-akeneo.ini

# PHP 8.4 Base
FROM base-template AS base-php84
RUN apt-get update && \
    apt-get --yes install imagemagick \
        libmagickcore-6.q16-6-extra \
        ghostscript \
        php8.4-fpm \
        php8.4-cli \
        php8.4-intl \
        php8.4-opcache \
        php8.4-mysql \
        php8.4-zip \
        php8.4-xml \
        php8.4-gd \
        php8.4-grpc \
        php8.4-curl \
        php8.4-mbstring \
        php8.4-bcmath \
        php8.4-imagick \
        php8.4-apcu \
        php8.4-exif \
        php8.4-memcached \
        openssh-client \
        aspell \
        aspell-en aspell-es aspell-de aspell-fr && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* && \
    ln -sf /usr/sbin/php-fpm8.4 /usr/local/sbin/php-fpm && \
    usermod --uid 1000 www-data && groupmod --gid 1000 www-data && \
    mkdir /srv/pim && \
    sed -i "s#listen = /run/php/php8.4-fpm.sock#listen = 9000#g" /etc/php/8.4/fpm/pool.d/www.conf && \
    mkdir -p /run/php

COPY docker/build/akeneo.ini /etc/php/8.4/cli/conf.d/99-akeneo.ini
COPY docker/build/akeneo.ini /etc/php/8.4/fpm/conf.d/99-akeneo.ini

# PHP 8.5 Base (if available)
FROM base-template AS base-php85
RUN apt-get update && \
    apt-get --yes install imagemagick \
        libmagickcore-6.q16-6-extra \
        ghostscript \
        php8.5-fpm \
        php8.5-cli \
        php8.5-intl \
        php8.5-opcache \
        php8.5-mysql \
        php8.5-zip \
        php8.5-xml \
        php8.5-gd \
        php8.5-grpc \
        php8.5-curl \
        php8.5-mbstring \
        php8.5-bcmath \
        php8.5-imagick \
        php8.5-apcu \
        php8.5-exif \
        php8.5-memcached \
        openssh-client \
        aspell \
        aspell-en aspell-es aspell-de aspell-fr && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* && \
    ln -sf /usr/sbin/php-fpm8.5 /usr/local/sbin/php-fpm && \
    usermod --uid 1000 www-data && groupmod --gid 1000 www-data && \
    mkdir /srv/pim && \
    sed -i "s#listen = /run/php/php8.5-fpm.sock#listen = 9000#g" /etc/php/8.5/fpm/pool.d/www.conf && \
    mkdir -p /run/php

COPY docker/build/akeneo.ini /etc/php/8.5/cli/conf.d/99-akeneo.ini
COPY docker/build/akeneo.ini /etc/php/8.5/fpm/conf.d/99-akeneo.ini

CMD ["/usr/bin/supervisord", "-c", "docker/supervisord.conf"]

# Development targets with Xdebug and Blackfire
FROM base-php81 AS dev-php81
ENV PHP_CONF_OPCACHE_VALIDATE_TIMESTAMP=1
ENV COMPOSER_MEMORY_LIMIT=4G
RUN apt-get update && \
    apt-get --yes install gnupg &&\
    sh -c 'wget -q -O - https://packages.blackfire.io/gpg.key |APT_KEY_DONT_WARN_ON_DANGEROUS_USAGE=DontWarn apt-key add -' &&\
    sh -c 'echo "deb http://packages.blackfire.io/debian any main" >  /etc/apt/sources.list.d/blackfire.list' &&\
    apt-get update && \
    apt-get --yes install \
        blackfire \
        blackfire-php \
        curl \
        default-mysql-client \
        git \
        perceptualdiff \
        php8.1-xdebug \
        procps \
        unzip &&\
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*
COPY docker/build/xdebug.ini /etc/php/8.1/cli/conf.d/99-akeneo-xdebug.ini
COPY docker/build/xdebug.ini /etc/php/8.1/fpm/conf.d/99-akeneo-xdebug.ini
COPY docker/build/blackfire.ini /etc/php/8.1/cli/conf.d/99-akeneo-blackfire.ini
COPY docker/build/blackfire.ini /etc/php/8.1/fpm/conf.d/99-akeneo-blackfire.ini
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
RUN chmod +x /usr/local/bin/composer
RUN mkdir -p /var/www/.composer && chown www-data:www-data /var/www/.composer
RUN mkdir -p /var/www/.cache && chown www-data:www-data /var/www/.cache
VOLUME /srv/pim

FROM base-php82 AS dev-php82
ENV PHP_CONF_OPCACHE_VALIDATE_TIMESTAMP=1
ENV COMPOSER_MEMORY_LIMIT=4G
RUN apt-get update && \
    apt-get --yes install gnupg &&\
    sh -c 'wget -q -O - https://packages.blackfire.io/gpg.key |APT_KEY_DONT_WARN_ON_DANGEROUS_USAGE=DontWarn apt-key add -' &&\
    sh -c 'echo "deb http://packages.blackfire.io/debian any main" >  /etc/apt/sources.list.d/blackfire.list' &&\
    apt-get update && \
    apt-get --yes install \
        blackfire \
        blackfire-php \
        curl \
        default-mysql-client \
        git \
        perceptualdiff \
        php8.2-xdebug \
        procps \
        unzip &&\
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*
COPY docker/build/xdebug.ini /etc/php/8.2/cli/conf.d/99-akeneo-xdebug.ini
COPY docker/build/xdebug.ini /etc/php/8.2/fpm/conf.d/99-akeneo-xdebug.ini
COPY docker/build/blackfire.ini /etc/php/8.2/cli/conf.d/99-akeneo-blackfire.ini
COPY docker/build/blackfire.ini /etc/php/8.2/fpm/conf.d/99-akeneo-blackfire.ini
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
RUN chmod +x /usr/local/bin/composer
RUN mkdir -p /var/www/.composer && chown www-data:www-data /var/www/.composer
RUN mkdir -p /var/www/.cache && chown www-data:www-data /var/www/.cache
VOLUME /srv/pim

FROM base-php83 AS dev-php83
ENV PHP_CONF_OPCACHE_VALIDATE_TIMESTAMP=1
ENV COMPOSER_MEMORY_LIMIT=4G
RUN apt-get update && \
    apt-get --yes install gnupg &&\
    sh -c 'wget -q -O - https://packages.blackfire.io/gpg.key |APT_KEY_DONT_WARN_ON_DANGEROUS_USAGE=DontWarn apt-key add -' &&\
    sh -c 'echo "deb http://packages.blackfire.io/debian any main" >  /etc/apt/sources.list.d/blackfire.list' &&\
    apt-get update && \
    apt-get --yes install \
        blackfire \
        blackfire-php \
        curl \
        default-mysql-client \
        git \
        perceptualdiff \
        php8.3-xdebug \
        procps \
        unzip &&\
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*
COPY docker/build/xdebug.ini /etc/php/8.3/cli/conf.d/99-akeneo-xdebug.ini
COPY docker/build/xdebug.ini /etc/php/8.3/fpm/conf.d/99-akeneo-xdebug.ini
COPY docker/build/blackfire.ini /etc/php/8.3/cli/conf.d/99-akeneo-blackfire.ini
COPY docker/build/blackfire.ini /etc/php/8.3/fpm/conf.d/99-akeneo-blackfire.ini
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
RUN chmod +x /usr/local/bin/composer
RUN mkdir -p /var/www/.composer && chown www-data:www-data /var/www/.composer
RUN mkdir -p /var/www/.cache && chown www-data:www-data /var/www/.cache
VOLUME /srv/pim

FROM base-php84 AS dev-php84
ENV PHP_CONF_OPCACHE_VALIDATE_TIMESTAMP=1
ENV COMPOSER_MEMORY_LIMIT=4G
RUN apt-get update && \
    apt-get --yes install gnupg &&\
    sh -c 'wget -q -O - https://packages.blackfire.io/gpg.key |APT_KEY_DONT_WARN_ON_DANGEROUS_USAGE=DontWarn apt-key add -' &&\
    sh -c 'echo "deb http://packages.blackfire.io/debian any main" >  /etc/apt/sources.list.d/blackfire.list' &&\
    apt-get update && \
    apt-get --yes install \
        blackfire \
        blackfire-php \
        curl \
        default-mysql-client \
        git \
        perceptualdiff \
        php8.4-xdebug \
        procps \
        unzip &&\
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*
COPY docker/build/xdebug.ini /etc/php/8.4/cli/conf.d/99-akeneo-xdebug.ini
COPY docker/build/xdebug.ini /etc/php/8.4/fpm/conf.d/99-akeneo-xdebug.ini
COPY docker/build/blackfire.ini /etc/php/8.4/cli/conf.d/99-akeneo-blackfire.ini
COPY docker/build/blackfire.ini /etc/php/8.4/fpm/conf.d/99-akeneo-blackfire.ini
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
RUN chmod +x /usr/local/bin/composer
RUN mkdir -p /var/www/.composer && chown www-data:www-data /var/www/.composer
RUN mkdir -p /var/www/.cache && chown www-data:www-data /var/www/.cache
VOLUME /srv/pim

FROM base-php85 AS dev-php85
ENV PHP_CONF_OPCACHE_VALIDATE_TIMESTAMP=1
ENV COMPOSER_MEMORY_LIMIT=4G
RUN apt-get update && \
    apt-get --yes install gnupg &&\
    sh -c 'wget -q -O - https://packages.blackfire.io/gpg.key |APT_KEY_DONT_WARN_ON_DANGEROUS_USAGE=DontWarn apt-key add -' &&\
    sh -c 'echo "deb http://packages.blackfire.io/debian any main" >  /etc/apt/sources.list.d/blackfire.list' &&\
    apt-get update && \
    apt-get --yes install \
        blackfire \
        blackfire-php \
        curl \
        default-mysql-client \
        git \
        perceptualdiff \
        php8.5-xdebug \
        procps \
        unzip &&\
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*
COPY docker/build/xdebug.ini /etc/php/8.5/cli/conf.d/99-akeneo-xdebug.ini
COPY docker/build/xdebug.ini /etc/php/8.5/fpm/conf.d/99-akeneo-xdebug.ini
COPY docker/build/blackfire.ini /etc/php/8.5/cli/conf.d/99-akeneo-blackfire.ini
COPY docker/build/blackfire.ini /etc/php/8.5/fpm/conf.d/99-akeneo-blackfire.ini
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
RUN chmod +x /usr/local/bin/composer
RUN mkdir -p /var/www/.composer && chown www-data:www-data /var/www/.composer
RUN mkdir -p /var/www/.cache && chown www-data:www-data /var/www/.cache
VOLUME /srv/pim

# Named targets for easy reference
FROM dev-php81 AS php81-dev
FROM dev-php82 AS php82-dev
FROM dev-php83 AS php83-dev
FROM dev-php84 AS php84-dev
FROM dev-php85 AS php85-dev

# Default target (PHP 8.1 for backward compatibility)
FROM php81-dev AS default
