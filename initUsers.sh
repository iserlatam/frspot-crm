#!/bin/bash

php artisan migrate --path=database/migrations/2014_10_12_000000_create_users_table.php
php artisan migrate --path=database/migrations/2014_10_12_100000_create_password_reset_tokens_table.php
php artisan migrate --path=database/migrations/2019_08_19_000000_create_failed_jobs_table.php
php artisan migrate --path=database/migrations/2019_12_14_000001_create_personal_access_tokens_table.php
php artisan migrate --path=database/migrations/2024_10_29_230833_create_permission_tables.php
# pa migrate --path=
php artisan permission:create-role cliente
