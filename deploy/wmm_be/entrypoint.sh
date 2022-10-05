#!/usr/bin/env bash
php artisan key:generate
php artisan config:cache
service cron start
php-fpm -D
nginx -g 'daemon off;'
