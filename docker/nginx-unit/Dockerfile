FROM docker.io/library/composer:2.7.1 AS composer
 
COPY composer.json /app/
 
RUN set -xe \
    && composer install --no-dev --no-scripts --no-suggest --no-interaction --prefer-dist --optimize-autoloader --ignore-platform-req=ext-gd
RUN composer dump-autoload --no-dev --optimize --classmap-authoritative

FROM docker.io/unit:1.32.1-php8.2

RUN apt-get update \
      && apt-get install --no-install-recommends -y \
	 gettext-base \
         libonig-dev \
	 libpng16-16 \
         libpng-dev \
         libxml2-dev \
	 libzip4 \
         libzip-dev \
	 mariadb-client \
      && docker-php-ext-install \
         gd \
         mbstring \
         mysqli\
         opcache \
         pdo \
         pdo_mysql \
         xml \
         zip \
      && apt-get autoremove --purge -y \
         libonig-dev \
         libpng-dev \
         libxml2-dev \
         libzip-dev \
      && apt-get clean \
      && rm -rf /var/lib/apt/lists/*

COPY docker/nginx-unit/configuration.json docker/nginx-unit/connect-envsubst.sh /docker-entrypoint.d/
COPY docker/nginx-unit/migrations.sql /
COPY docker/nginx-unit/prometheus.php /metrics/
COPY docker/nginx-unit/healthz.php /var/www/healthz/
COPY . /var/www/
RUN rm -rf /var/www/installation
COPY --from=composer /app/vendor /var/www/vendor/

RUN chown -R unit:root /var/www/ /docker-entrypoint.d/ /metrics \
    && chmod -R ug+rwX /var/www/ /docker-entrypoint.d/ /metrics \
    && chown unit:root /var/lib/unit/ /run/ \
    && chmod ug+rwX /var/lib/unit/ /run/
WORKDIR /var/www/
USER unit
