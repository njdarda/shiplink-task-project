FROM php:7.4-fpm-alpine

ARG user_name=docker_user
ARG uid=1000
ARG gid=1000
ARG workdir=/var/www/html

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions

# install packages
RUN apk add --no-cache \
        acl \
        bash \
        fcgi \
        file \
        gettext \
        git \
        sudo \
    ;

# install extensions
RUN set -eux; \
    install-php-extensions \
        apcu \
        intl \
        opcache \
        zip \
        pdo_pgsql \
    ;

# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

# install Symfony console
RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.alpine.sh' | bash
RUN apk add symfony-cli

# set up user
RUN adduser --disabled-password --gecos "" --home "/home/${user_name}" --uid "${uid}" "${user_name}"
RUN echo '%wheel ALL=(ALL) NOPASSWD:ALL' > /etc/sudoers.d/wheel
RUN adduser ${user_name} wheel

## PHP config
RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

USER ${uid}:${gid}

RUN git config --global user.email "docker_user@example.com"
RUN git config --global user.name "Docker User"
RUN git config --global --add safe.directory /var/www/html

# add /bin locations to PATH
ENV PATH "$PATH:${workdir}/bin:${workdir}/vendor/bin"

COPY --chown=${uid}:${gid} . ${workdir}
RUN composer install || true

RUN mkdir -p ${workdir}/var
WORKDIR ${workdir}
