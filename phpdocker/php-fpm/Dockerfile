FROM phpdockerio/php71-fpm:latest
WORKDIR "/application"

ARG GITHUB_TOKEN=local
ENV GITHUB_TOKEN ${GITHUB_TOKEN}

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install wget curl php-memcached php7.1-mbstring php7.1-dom php7.1-mcrypt php7.1-curl php7.1-memcache php7.1-mysql php-xdebug php7.1-bcmath php7.1-gd php7.1-gmp php7.1-intl php7.1-phpdbg php7.1-xsl php-ssh2 php-yaml \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN cd /root  \
    && mkdir .composer \
    && cd .composer \
    && echo "{\"github-oauth\": {\"github.com\": \"${GITHUB_TOKEN}\"}}" > auth.json
