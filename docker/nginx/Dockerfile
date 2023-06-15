ARG branch

FROM ghcr.io/njdarda/shiplink-task-project/php-fpm:${branch:-main} as php-fpm

FROM nginx:mainline-alpine

ARG user_name=docker_user
ARG uid=1000
ARG gid=1000

COPY ./conf /etc/nginx
COPY --chown=${uid}:${gid} --from=php-fpm /var/www/html/public /var/www/html/public

# set up user
RUN adduser --disabled-password --gecos "" --home "/home/${user_name}" --uid "${uid}" "${user_name}"

