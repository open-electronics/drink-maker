cd /var/www/drink-maker
git reset --hard
php artisan migrate:rollback
php artisan migrate --seed
sudo reboot
