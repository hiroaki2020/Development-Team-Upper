FROM php:8.0.2-fpm-buster
LABEL maintainer="hiroaki"
SHELL ["/bin/bash", "-oeux", "pipefail", "-c"]
# This is the line to copy repo into this image for staging and production images
# timezone environment
ENV TZ=Asia/Tokyo \
  # locale
  LANG=ja_JP.UTF-8 \
  LANGUAGE=ja_JP:ja \
  LC_ALL=ja_JP.UTF-8 \
  # composer environment
  COMPOSER_HOME=/composer

COPY --from=composer:2.0.11 /usr/bin/composer /usr/bin/composer

RUN apt-get update && \
  apt-get -y install traceroute iputils-ping netcat git libicu-dev libonig-dev libzip-dev unzip locales libfreetype6-dev libjpeg62-turbo-dev libpng-dev liblz4-dev && \
  #apt-get clean && \
  #rm -rf /var/lib/apt/lists/* && \
  locale-gen ja_JP.UTF-8 && \
  localedef -f UTF-8 -i ja_JP ja_JP.UTF-8 && \
  mkdir /var/run/php-fpm && \
  docker-php-ext-configure gd --with-freetype --with-jpeg && \
  docker-php-ext-install intl pdo_mysql zip bcmath gd exif && \
  pecl install msgpack-2.1.2 && \
  docker-php-ext-enable msgpack && \
  pecl bundle -d /tmp redis-5.3.7 && \
  docker-php-ext-configure /tmp/redis --enable-redis-msgpack --enable-redis-lz4 --with-liblz4 && \
  docker-php-ext-install /tmp/redis && \
  #rm -rf /tmp/redis /tmp/redis-5.3.7.tgz && \
  addgroup --system --gid 555 dtuj && \
  adduser --system --disabled-login --ingroup dtuj --no-create-home --home /nonexistent --gecos dtuj --shell /bin/false --uid 555 dtuj && \
  delgroup sudo && \
  umask 0027 && \
  mkdir -p /work/backend /composer && \
  chown -hR dtuj:dtuj /var/run/php-fpm /composer /work && \
  chmod 750 /var/run/php-fpm && \
  chmod 500 /work

COPY ./infra/docker/php/php-fpm.d/zzz-www.conf /usr/local/etc/php-fpm.d/zzz-www.conf
COPY ./infra/docker/php/php.ini /usr/local/etc/php/php.ini

WORKDIR /work/backend
USER dtuj
RUN composer config -g process-timeout 3600 && \
  composer config -g repos.packagist composer https://packagist.org
