#!/bin/bash

DIR=$(cat /etc/remoteqth-path)
FSK=$(cat $DIR/cfg/s-fsk)
FSKPORT=$(cat $DIR/cfg/s-fsk2serial)

PROGDIR="/usr/bin/"
PROGNAME="socat"

COMMAND="$PROGDIR$PROGNAME UDP4-RECVFROM:7891,fork TCP4:localhost:$FSKPORT"

if [ $FSK == 1 ]; then
PATH=/bin:/usr/bin:/sbin:/usr/sbin

	case "$1" in
	  start)
	    echo "Starting $COMMAND."
	    $COMMAND &
	    ps aux | grep "[s]ocat UDP4-RECVFROM:7891,fork TCP4:localhost:$FSKPORT" | awk '{ print $2 }' > /var/run/fskudp.pid
	    ;;
	  stop)
	    echo "Stoping $COMMAND."
		PID=$(cat /var/run/fskudp.pid)
		if [ $PID ]; then kill $PID; fi
#	    killall $PROGNAME
	    ;;
	  restart)
	    $0 stop
		sleep 3
	    $0 start
	    ;;
	  *)
	    echo "Usage: ... {start|stop|restart}"
	    exit 1
	    ;;
	esac
	exit 0
fi
exit 0
