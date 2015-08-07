#! /bin/sh
# /etc/init.d/example
 
case "$1" in
  start)
    echo "Checking for updates"
    cd /var/www/drink-maker
    OUTPUT="$(sudo -u www-data -H git pull origin master)"
    if [[ "$OUTPUT" == *"files changed"* ]]
    then
      echo "Updates found"
    elif [[ "$OUTPUT" == *"Already up-to-date."* ]]
    then
      echo "Already up-to-date."
    else
      echo "Updates disabled"
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