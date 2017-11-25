#!/bin/bash
# /etc/init.d/server-start
#
# Do not know what I am doing, copied from:
# https://unix.stackexchange.com/questions/56957/how-to-start-an-application-automatically-on-boot

touch /var/lock/server-start

# Carry out specific functions when asked to by the system
case "$1" in
  start)
    echo "Starting server init script"
    /usr/local/bin/noip2
    /home/pi/keep_online.sh
    ;;
  stop)
    echo "Turning off script"
    killall noip2
    ;;
  *)
    echo "Usage: /etc/init.d/server-start {start|stop}"
    exit 1
    ;;
esac

exit 0
