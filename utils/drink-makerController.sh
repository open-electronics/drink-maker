#! /bin/sh
# /etc/init.d/example
 
case "$1" in
  start)
    echo "Starting the controller"
    # run application you want to start
    python3 /var/www/drink-maker/utils/controller.py &
    ;;
  stop)
    echo "Stopping the controller"
    # kill application you want to stop
    killall python
    ;;
  *)
    echo "Usage: /etc/init.d/example{start|stop}"
    exit 1
    ;;
esac
 
exit 0