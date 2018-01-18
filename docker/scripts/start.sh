#!/bin/bash

php artisan queue:listen > /dev/stdout 2>&1 &
cron -f > /dev/stdout 2>&1 &
php-fpm &
service nginx start