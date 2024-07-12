#!/bin/bash
mariadb -h "${DB_HOST}" -u "${DB_USER}" --password="${DB_PASSWORD}" -D "${DB_NAME}" -e  "select * from grr_db_version;" \
|| mariadb --default-character-set=utf8mb4 -h "${DB_HOST}" -u "${DB_USER}" --password="${DB_PASSWORD}" -D "${DB_NAME}"  < /var/www/docker/nginx-unit/migrations.sql \
&& echo "done";
