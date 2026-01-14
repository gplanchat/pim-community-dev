# Multi-stage Dockerfile for Akeneo PIM Migration
# This file contains ONLY the current migration step versions
# Update PHP and Node versions sequentially as migration progresses
#
# Current migration step: Phase 2.2 - PHP 8.2 → 8.3
# Current PHP version: 8.3
# Current Node version: 18
#
# Note: FrankenPHP migration planned for Phase 6 (PHP 8.4 → 8.5)
# See: https://frankenphp.dev/fr/

FROM httpd:2.4-bullseye AS base

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
    sh -c 'echo "deb https://packages.sury.org/php/ bullseye main" > /etc/apt/sources.list.d/php.list' &&\
    apt-get update && \
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
    ln -s /usr/sbin/php-fpm8.3 /usr/local/sbin/php-fpm && \
    usermod --uid 1000 www-data && groupmod --gid 1000 www-data && \
    mkdir /srv/pim && \
    sed -i "s#listen = /run/php/php8.3-fpm.sock#listen = 9000#g" /etc/php/8.3/fpm/pool.d/www.conf && \
    mkdir -p /run/php

COPY docker/build/akeneo.ini /etc/php/8.3/cli/conf.d/99-akeneo.ini
COPY docker/build/akeneo.ini /etc/php/8.3/fpm/conf.d/99-akeneo.ini

CMD ["/usr/bin/supervisord", "-c", "docker/supervisord.conf"]

FROM base as dev

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

# ============================================================================
# Node Stage
# ============================================================================
FROM debian:bullseye-slim AS node

RUN groupadd --gid 1000 node \
    && useradd --uid 1000 --gid node --shell /bin/bash --create-home node

RUN echo 'path-exclude=/usr/share/man/*' > /etc/dpkg/dpkg.cfg.d/path_exclusions && \
    echo 'path-exclude=/usr/share/doc/*' >> /etc/dpkg/dpkg.cfg.d/path_exclusions && \
    apt-get update && \
    apt-get --no-install-recommends --no-install-suggests -y -q install \
    wget apt-transport-https ca-certificates gnupg && \
    apt-get clean && apt-get --yes --quiet autoremove --purge && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# NodeJS 18 and Yarn
RUN sh -c 'wget -q -O - https://deb.nodesource.com/gpgkey/nodesource.gpg.key | APT_KEY_DONT_WARN_ON_DANGEROUS_USAGE=DontWarn apt-key add -' && \
    sh -c 'echo "deb https://deb.nodesource.com/node_18.x bullseye main" > /etc/apt/sources.list.d/nodesource.list' && \
    sh -c 'wget -q -O - https://dl.yarnpkg.com/debian/pubkey.gpg | APT_KEY_DONT_WARN_ON_DANGEROUS_USAGE=DontWarn apt-key add -' && \
    sh -c 'echo "deb https://dl.yarnpkg.com/debian/ stable main" > /etc/apt/sources.list.d/yarn.list' && \
    apt-get update && \
    apt-get install -y nodejs yarn \
    && apt-get clean && apt-get -y -q autoremove --purge \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Install latest chrome dev package and fonts to support major charsets
RUN apt-get update \
    && apt-get --no-install-recommends --no-install-suggests -y -q install \
            ca-certificates fonts-liberation gconf-service gnupg libasound2 libatk1.0-0 libcairo2 libcups2 \
            libdbus-1-3 libexpat1 libfontconfig1 libgconf-2-4 libgdk-pixbuf2.0-0 libglib2.0-0 libgtk-3-0 \
            libnspr4 libpango-1.0-0 libpangocairo-1.0-0 libx11-6 libx11-xcb1 libxcb1 libxcomposite1 \
            libxcursor1 libxdamage1 libxext6 libxfixes3 libxi6 libxrandr2 libxrender1 libxss1 libxtst6 \
            libnss3 lsb-release wget xdg-utils \
    && wget https://dl-ssl.google.com/linux/linux_signing_key.pub \
    && apt-key add linux_signing_key.pub \
    && rm linux_signing_key.pub \
    && sh -c 'echo "deb [arch=amd64] http://dl.google.com/linux/chrome/deb/ stable main" >> /etc/apt/sources.list.d/google.list' \
    && apt-get update \
    && apt-get --no-install-recommends --no-install-suggests --yes --quiet install \
            google-chrome-stable fonts-ipafont-gothic fonts-wqy-zenhei fonts-thai-tlwg fonts-kacst fonts-freefont-ttf \
    && apt-get clean && apt-get --yes --quiet autoremove --purge \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# It's a good idea to use dumb-init to help prevent zombie chrome processes.
ADD https://github.com/Yelp/dumb-init/releases/download/v1.2.5/dumb-init_1.2.5_x86_64 /usr/local/bin/dumb-init
RUN chmod +x /usr/local/bin/dumb-init

USER node

ENTRYPOINT ["dumb-init", "--"]
