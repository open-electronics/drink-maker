#! /bin/sh
# /etc/init.d/example
 
case "$1" in
  start)
    sudo bash /var/www/drink-maker/utils/update.sh
    echo "Starting the controller"
    # run application you want to start
    sudo python3 /var/www/drink-maker/utils/controller.py &
    sudo bash /var/www/drink-maker/utils/check-wifi.sh &
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