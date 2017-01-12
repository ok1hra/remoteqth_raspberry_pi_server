#!/bin/bash

DIR=$(cat /etc/remoteqth-path)
PROGNAME="band-decoder.sh"
ON=$(cat $DIR/cfg/s-band-on)

#COMMAND="flock -n /tmp/band-decoder.lock -c `nohup $DIR/script/$PROGNAME &`"
COMMAND="$DIR/script/$PROGNAME"

PATH=/bin:/usr/bin:/sbin:/usr/sbin
#if [ $ON -eq 0 ]; then
#	exit 0
#fi

	case "$1" in
	  start)
	    echo "Starting $COMMAND."
	    $COMMAND &
	    ps aux | grep "[b]and-decoder.sh" | awk '{ print $2 }' > /var/run/bd.pid
	    ;;
	  stop)
	    echo "Stoping $PROGNAME."
		PID=$(cat /var/run/bd.pid)
		if [ $PID ]; then kill $PID; fi
#	    killall $PROGNAME
	    ;;
	  restart)
	    $0 stop
	    $0 start
	    ;;
	  *)
	    echo "Usage: ... {start|stop|restart}"
	    exit 1
	    ;;
	esac
	exit 0

