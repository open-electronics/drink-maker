#! /bin/sh
# /etc/init.d/example
 
case "$1" in
  start)
    echo "Checking for updates"
    cd /var/www/drink-maker
    REMOTES="$(sudo -u www-data -H git remote show origin)"
    #ONLY IF UPDATES ARE ENABLED
    if [[ "$REMOTES" == *"Fetch URL: https://github.com/open-electronics/drink-maker.git"* ]]
    then
      sudo -u www-data -H git reset --HARD
      OUTPUT="$(sudo -u www-data -H git pull origin master)"
      if [[ "$OUTPUT" == *"files changed"* ]]
      then
        echo "Updates found"
        if [[ "$OUTPUT" == *"install/default"* ]]
        then
          echo "Copying nginx updated files"
          sudo cp /var/www/drink-maker/install/default /etc/nginx/sites-available/default
          sudo ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default
        fi
        if [[ "$OUTPUT" == *"install/www.conf"* ]]
        then
          echo "Updating www.conf"
          sudo rm /etc/php5/fpm/pool.d/www.conf
          sudo cp /var/www/install/www.conf /etc/php5/fpm/pool.d/www.conf
        fi
        if [[ "$OUTPUT" == *"install/php.ini"* ]]
        then
          echo "Updating php.ini"
          sudo rm /etc/php5/fpm/php.ini
          sudo cp /var/www/install/php.ini /etc/php5/fpm/php.ini
        fi
        echo "Restarting services"
        sudo service php5-fpm restart
        sudo service nginx restart
      elif [[ "$OUTPUT" == *"Already up-to-date."* ]]
      then
        echo "Already up-to-date."
      else
        echo "Updates disabled"
      fi
    fi
    echo "Starting the controller"
    # run application you want to start
    sudo python3 /var/www/drink-maker/utils/controller.py &
    ;;
  stop)
    echo "Stopping the controller"
    # kill application you want to stop
    sudo killall python
    ;;
  *)
    echo "Usage: /etc/init.d/example{start|stop}"
    exit 1
    ;;
esac
 
exit 0