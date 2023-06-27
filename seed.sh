#!/usr/bin/env bash
./clear.sh
php artisan migrate:fresh
php artisan db:seed
#php artisan db:seed --class=RelSeeder
