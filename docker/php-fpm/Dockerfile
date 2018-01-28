FROM phpdockerio/php72-fpm:latest

RUN apt-get update \
    && apt-get -y --no-install-recommends install \
        php7.2-mbstring \
        php7.2-mysql

WORKDIR "/app"