#!/bin/bash

DIR=$(cat /etc/remoteqth-path)
RIG=$(cat $DIR/cfg/s-rigctld-on)
RIGMODEL=$(cat $DIR/cfg/s-rigctld-model)
RIGBAUD=$(cat $DIR/cfg/s-rigctld-baud)
RIGCIV=$(cat $DIR/cfg/s-rigctld-civ)

PROGDIR="/usr/bin/"
PROGNAME="rigctld"

# Icom models
if [ $RIGMODEL -gt 300 ] && [ $RIGMODEL -lt 500 ]; then
	COMMAND="$PROGDIR$PROGNAME -m $RIGMODEL -r /dev/ttyUSB.rig -s $RIGBAUD -c 0x$RIGCIV"
else
	COMMAND="$PROGDIR$PROGNAME -m $RIGMODEL -r /dev/ttyUSB.rig -s $RIGBAUD"
fi

if [ $RIG == 1 ]; then
PATH=/bin:/usr/bin:/sbin:/usr/sbin

	case "$1" in
	  start)
	    echo "Starting $COMMAND"
	    $COMMAND &
	    ps aux | grep "/usr/bin/[r]igctld -m $RIGMODEL" | awk '{ print $2 }' > /var/run/rig.pid
	    ;;
	  stop)
	    echo "Stoping $PROGNAME"
		PID=$(cat /var/run/rig.pid)
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
fi
exit 0
