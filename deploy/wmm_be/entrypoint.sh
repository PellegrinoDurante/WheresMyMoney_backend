#!/usr/bin/env bash
php-fpm -D
nginx -g 'daemon off;'
