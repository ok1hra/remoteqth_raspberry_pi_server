#!/bin/bash
# 2000:telnet:600:/dev/ttyS0:9600 8DATABITS NONE 1STOPBIT banner
# 1000:raw:600:/dev/ttyUSB2:4800 8DATABITS NONE 1STOPBIT

DIR=$(cat /etc/remoteqth-path)
COMS=$(cat $DIR/cfg/s-ser2net-coms)
ROTS=$(cat $DIR/cfg/s-rot-rots)

echo "# Rotators $ROTS"
for (( r=1; r<=$ROTS; r++ )); do
	echo "9${r}:raw:1:/dev/ttyUSB.rot${r}:9600 8DATABITS NONE 1STOPBIT"
done

echo -e "\n# coms $COMS"
for (( c=1; c<=$COMS; c++ )); do
	DEV=$(cat $DIR/cfg/s-ser2net-c${c}dev)
	if [ $DEV == 0 ]; then
		echo "$(cat $DIR/cfg/s-ser2net-c${c}port):raw:600:/dev/ttyUSB.com${c}:$(cat $DIR/cfg/s-ser2net-c${c}baud) $(cat $DIR/cfg/s-ser2net-c${c}data) $(cat $DIR/cfg/s-ser2net-c${c}parity) $(cat $DIR/cfg/s-ser2net-c${c}stop)"
	elif [ $DEV == 1 ]; then
		DEVPATHS=$(cat $DIR/cfg/s-ser2net-c${c}devpath)
		echo "$(cat $DIR/cfg/s-ser2net-c${c}port):raw:600:/dev/${DEVPATHS}:$(cat $DIR/cfg/s-ser2net-c${c}baud) $(cat $DIR/cfg/s-ser2net-c${c}data) $(cat $DIR/cfg/s-ser2net-c${c}parity) $(cat $DIR/cfg/s-ser2net-c${c}stop)"
	fi
done

exit 0
