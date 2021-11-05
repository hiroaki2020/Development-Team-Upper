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
  COMPOSER_ALLOW_SUPERUSER=1 \
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
  composer config -g process-timeout 3600 && \
  composer config -g repos.packagist composer https://packagist.org

COPY ./infra/docker/php/php-fpm.d/zzz-www.conf /usr/local/etc/php-fpm.d/zzz-www.conf
COPY ./infra/docker/php/php.ini /usr/local/etc/php/php.ini

RUN addgroup --system --gid 555 dtuj
RUN adduser --system --disabled-login --ingroup dtuj --no-create-home --home /nonexistent --gecos "dtuj" --shell /bin/false --uid 555 dtuj
#RUN addgroup --system --gid 101 nginx
#RUN adduser --system --disabled-login --ingroup nginx --no-create-home --home /var/cache/nginx --gecos "nginx" --shell /sbin/nologin --uid 101 --add_extra_groups dtuj nginx
#RUN umask 0027

WORKDIR /work/backend
RUN chown dtuj:dtuj /work/backend
RUN chmod 750 /work/backend
#RUN chmod 777 /var/run/php-fpm
USER dtuj