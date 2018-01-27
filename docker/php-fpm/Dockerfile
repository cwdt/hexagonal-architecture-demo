FROM phpdockerio/php71-fpm:latest

RUN apt-get update \
    && apt-get -y --no-install-recommends install \
        php7.1-mbstring \
        php7.1-mysql

WORKDIR "/app"