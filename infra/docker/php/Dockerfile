FROM php:8.0.2-fpm-buster
LABEL maintainer="hiroaki"
SHELL ["/bin/bash", "-oeux", "pipefail", "-c"]

# timezone environment
ENV TZ=Asia/Tokyo \
  # locale
  LANG=ja_JP.UTF-8 \
  LANGUAGE=ja_JP:ja \
  LC_ALL=ja_JP.UTF-8 \
  # composer environment
  #COMPOSER_ALLOW_SUPERUSER=1 \
  COMPOSER_HOME=/composer

COPY --from=composer:2.0.11 /usr/bin/composer /usr/bin/composer

RUN apt-get update && \
  apt-get -y install git libicu-dev libonig-dev libzip-dev unzip locales libfreetype6-dev libjpeg62-turbo-dev libpng-dev && \
  apt-get clean && \
  rm -rf /var/lib/apt/lists/* && \
  locale-gen ja_JP.UTF-8 && \
  localedef -f UTF-8 -i ja_JP ja_JP.UTF-8 && \
  mkdir /var/run/php-fpm && \
  docker-php-ext-configure gd --with-freetype --with-jpeg && \
  docker-php-ext-install intl pdo_mysql zip bcmath gd exif && \
  addgroup --system --gid 555 dtuj && \
  adduser --system --disabled-login --ingroup dtuj --no-create-home --home /nonexistent --gecos dtuj --shell /bin/false --uid 555 dtuj && \
  addgroup --system --gid 102 nginx && \
  adduser --system --disabled-login --ingroup nginx --no-create-home --home /var/cache/nginx --gecos nginx --shell /sbin/nologin --uid 102 nginx && \
  addgroup nginx dtuj && \
  delgroup sudo && \
  addgroup --system --gid 27 mysql && \
  adduser --system --disabled-login --ingroup mysql --home /var/lib/mysql --gecos 'MySQL Server' --shell /bin/false --uid 27 mysql && \
  adduser dtuj mysql && \
  umask 0027 && \
  mkdir /work /composer && \
  chown dtuj:dtuj /var/run/php-fpm /composer && \
  chmod 750 /var/run/php-fpm && \
  chown dtuj:root /work && \
  chmod 500 /work

COPY ./infra/docker/php/php-fpm.d/zzz-www.conf /usr/local/etc/php-fpm.d/zzz-www.conf
COPY ./infra/docker/php/php.ini /usr/local/etc/php/php.ini

WORKDIR /work/backend
USER dtuj
RUN composer config -g process-timeout 3600 && \
  composer config -g repos.packagist composer https://packagist.org
# Uncomment the line below in circleci env
#COPY ./backend /work/backend