#!/bin/bash
echo "Updating"
sudo apt-get update -qq
sudo apt-get upgrade -y >> /dev/null

echo "Installing dependencies"
sudo apt-get install nginx curl php5-fpm php5-cgi php5-cli php5-common php5-mcrypt sqlite sqlite3 libsqlite3-dev php5-sqlite -y
sudo useradd www-data 
sudo groupadd www-data 
sudo usermod -g www-data www-data 
sudo mkdir -p /var/www 
sudo chmod 775 /var/www -R 
sudo chown www-data:www-data /var/www 

echo "Nginx setup"
sudo rm /etc/nginx/sites-available/default
sudo cp install/default /etc/nginx/sites-available/default
sudo ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

sudo rm /etc/php5/fpm/pool.d/www.conf
sudo cp install/www.conf /etc/php5/fpm/pool.d/www.conf

sudo rm /etc/php5/fpm/php.ini
sudo cp install/php.ini /etc/php5/fpm/php.ini

echo "Restart services and enable mcrypt"
sudo php5enmod mcrypt
sudo service php5-fpm restart
sudo service nginx restart

echo "Install composer"
cd install
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
cd ..

echo "Change environment"
sudo rm ../.env
sudo cp ../.env.deploy ../.env

echo "Move all"
sudo cp -R ../* /var/www/barobot
sudo chown www-data:www-data /var/www/barobot -R
sudo chmod 0775 /var/www/barobot/storage -R

echo "Delete from install path"
sudo rm -rf ../../barobot

echo "Install dependencies"
cd /var/www/barobot
sudo -u www-data -H touch storage/database.sqlite
sudo -u www-data -H composer install

echo "Migrate and seed"
sudo -u www-data -H php artisan migrate --seed --force

echo "Enjoy!"