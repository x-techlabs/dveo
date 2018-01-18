#!/bin/bash

cd /var/www
composer install
npm i
npm run production

php artisan migrate
php artisan db:seed

chown -R www-data /var/www
