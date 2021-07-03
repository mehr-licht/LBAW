#!/bin/bash
set -e
cd /var/www; php artisan config:cache
env >> /var/www/.env

echo "* * * * * cd /var/www && php artisan schedule:run >> /dev/null 2>&1" > cronfile

crontab cronfile

php-fpm7.2 -D
cron -f &
nginx -g "daemon off;"

