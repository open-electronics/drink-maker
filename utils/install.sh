#!/bin/bash

#check if root
if [ "$EUID" -ne 0 ]
  then echo "Please run as root"
  exit
fi

if [ $# -eq 0 ]
  then
    echo "No arguments supplied"
    echo "Run this script like sudo bash install.sh TOKEN"
    echo "Where TOKEN is your GitHub personal access token"
    echo "An access token is needed to perform automatic operations and link them to an account."
    echo ""
    echo "If you don't know how to create an access token follow these simple steps:"
    echo "1- Login or create your GitHub account."
    echo "2- In the top right corner of any page of GitHub.com, click your profile photo, then click Settings."
    echo "3- In the user settings sidebar, click Personal access tokens."
    echo "4- Click Generate new token."
    echo "5- Give your token a descriptive name."
    echo "6- Select the scopes you wish to grant to this token."
    echo "The default scopes allow you to interact with public and private repositories, user data, and gists."
    echo "7- Click Generate token."
    echo "Run this script like sudo bash install.sh TOKEN"
    exit 1
fi

echo "Cleaning environment"

echo "Removing GUI"
#sudo apt-get purge xserver.* x11.* xarchiver xauth xkb-data console-setup xinit lightdm lxde.* python-tk python3-tk scratch gtk.* libgtk.* openbox libxt.* lxpanel gnome.* libqt.* libxcb.* libxfont.* lxmenu.* gvfs.* xdg-.* desktop.* tcl.* shared-mime-info penguinspuzzle omxplayer gsfonts 
#sudo apt-get --yes autoremove 
echo "Removing games and others"
sudo rm -rf /usr/share/doc/* /opt/vc/src/hello_pi/hello_video/test.h264 /home/pi/python_games 
find /usr/share/locale/* -maxdepth 0 -type d |grep -v en |xargs sudo rm -rf 
find /usr/share/man/* -maxdepth 0 -type d |grep -Pv 'man\d' |xargs sudo rm -rf 
echo "Removing MySQL"
sudo apt-get remove mysql-server mysql-client mysql-common 
sudo apt-get purge mysql-server mysql-client mysql-common 
sudo apt-get autoremove
echo "Removing Apache"
sudo service apache2 stop
sudo apt-get purge apache2 apache2-utils apache2.2-bin apache2-common
sudo apt-get autoremove


echo "Updating"
sudo apt-get update -qq >/dev/null
echo "Upgrading"
sudo apt-get upgrade -y >> /dev/null

echo "Installing dependencies"
echo "Nginx and curl"
sudo apt-get install nginx curl -y >/dev/null	 
echo "PHP5"
sudo apt-get install php5-fpm php5-cgi php5-cli php5-common php5-mcrypt -y >/dev/null
echo "sqlite"
sudo apt-get install sqlite sqlite3 libsqlite3-dev php5-sqlite -y >/dev/null
echo "Wireless tools"
sudo apt-get install wireless-tools -y > /dev/null
echo "avahi-daemon"
sudo apt-get install avahi-daemon -y > /dev/null


echo "Changing host"
sudo sed -i '$ d' /etc/hosts
echo "127.0.1.1	drink" | sudo tee --append /etc/hosts

echo "Changing hostname"
sudo sed -i '$ d' /etc/hostname
echo "drink" | sudo tee --append /etc/hostname

echo "Creating usergroup www-data"
sudo useradd www-data 
sudo groupadd www-data 
sudo usermod -g www-data www-data 
sudo mkdir -p /var/www 
sudo chmod 775 /var/www -R 
sudo chown www-data:www-data /var/www 

echo "Setting up Nginx"
sudo rm /etc/nginx/sites-available/default
sudo cp install/default /etc/nginx/sites-available/default
sudo ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

sudo rm /etc/php5/fpm/pool.d/www.conf
sudo cp install/www.conf /etc/php5/fpm/pool.d/www.conf

sudo rm /etc/php5/fpm/php.ini
sudo cp install/php.ini /etc/php5/fpm/php.ini

echo "Restarting services and enabling mcrypt"
sudo php5enmod mcrypt
sudo service php5-fpm restart
sudo service nginx restart

echo "Installing composer"
cd install
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
cd ..

echo "Changing environment"
sudo rm /home/pi/drink-maker/.env
sudo cp /home/pi/drink-maker/.env.deploy /home/pi/drink-maker/.env

echo "Moving all in /var/www"
sudo mv /home/pi/drink-maker /var/www

echo "Saving the access token"
sudo bash /var/www/drink-maker/utils/setToken.sh $1

echo "Assigning folders to www-data"
sudo chown www-data:www-data /var/www/drink-maker -R
sudo chmod 0775 /var/www/drink-maker/storage -R

echo "Installing composer dependencies"
cd /var/www/drink-maker
sudo -u www-data -H touch storage/database.sqlite
sudo -u www-data -H composer install

echo "Migrating and seeding"
sudo -u www-data -H php artisan migrate --seed --force

echo "Adding python to startup"
sudo cp /var/www/drink-maker/utils/drink-makerController.sh /etc/init.d/drink-maker.sh
sudo chmod +x /etc/init.d/drink-maker.sh
sudo update-rc.d drink-maker.sh defaults

echo "Installing RandA"
cp /var/www/drink-maker/utils/install/RandAinstall.sh /home/pi/RandAinstall.sh
cp /var/www/drink-maker/utils/install/RandAinstall.tar.gz /home/pi/RandAinstall.tar.gz
cd /home/pi
chmod 777 /home/pi/RandAinstall.sh
sudo bash /home/pi/RandAinstall.sh

echo "Rebooting!"

sudo reboot
