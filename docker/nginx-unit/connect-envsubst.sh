#!/bin/bash
envsubst '${DB_HOST},${DB_NAME},${DB_USER},${DB_PASSWORD}' < /var/www/docker/nginx-unit/connect.php > /var/www/personnalisation/connect.inc.php
