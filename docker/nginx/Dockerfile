FROM nginx:mainline-alpine

ARG user_name=docker_user
ARG uid=1000
ARG gid=1000

COPY ./conf /etc/nginx
COPY ./public /var/www/html/public

# set up user
RUN adduser --disabled-password --gecos "" --home "/home/${user_name}" --uid "${uid}" "${user_name}"

