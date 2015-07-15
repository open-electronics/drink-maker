cd /var/www/drink-maker
git reset --HARD
php artisan migrate:rollback
php artisan migrate --seed
