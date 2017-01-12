#!/bin/bash
# http://patrakov.blogspot.cz/2011/01/writing-systemd-service-files.html

DIR=$(cat /etc/remoteqth-path)
CWD=$(cat $DIR/cfg/s-cw-cwd)
CWCLIPORT=$(cat $DIR/cfg/s-cw-cwcli)

PROGDIR="/usr/bin/"
PROGNAME="socat"

COMMAND="$PROGDIR$PROGNAME UDP4-RECVFROM:7890,fork TCP4:localhost:$CWCLIPORT"

if [ $CWD == 2 ]; then
PATH=/bin:/usr/bin:/sbin:/usr/sbin

	case "$1" in
	  start)
	    echo "Starting $COMMAND."
	    $COMMAND &
	    ps aux | grep "[s]ocat UDP4-RECVFROM:7890,fork TCP4:localhost:$CWCLIPORT" | awk '{ print $2 }' > /var/run/cwudp.pid
	    ;;
	  stop)
	    echo "Stoping $COMMAND."
		#PID=$(ps aux | grep "[s]ocat UDP4-RECVFROM:7890,fork TCP4:localhost:$CWCLIPORT" | awk '{ print $2 }')
		PID=$(cat /var/run/cwudp.pid)
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
