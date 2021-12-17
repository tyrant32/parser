#!/bin/bash

cd {path_to_project}
composer install
php artisan test
