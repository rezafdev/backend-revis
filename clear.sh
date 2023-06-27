#!/usr/bin/env bash
php artisan cache:clear
php artisan config:clear
php artisan optimize:clear
#php artisan config:cache
#composer dump-autoload
