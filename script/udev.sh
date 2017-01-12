#!/bin/bash

DIR=$(cat /etc/remoteqth-path)
COMS=$(cat $DIR/cfg/s-ser2net-coms)
ROTS=$(cat $DIR/cfg/s-rot-rots)
RIG=$(cat $DIR/cfg/s-rigctld-on)
RIGDEV=$(cat $DIR/cfg/s-rigctld-dev)
RIGDEVPATH=$(cat $DIR/cfg/s-rigctld-devpath)
CWD=$(cat $DIR/cfg/s-cw-cwd)
FSK=$(cat $DIR/cfg/s-fsk)

echo "# USB device by path"
echo 'ACTION=="add", KERNEL=="ttyUSB[0-9]*", PROGRAM="'$DIR'/script/usb-parse-devpath.pm %p", SYMLINK+="ttyUSB.%c"'

if [ $CWD == 0 ]; then
	echo -e "\n# cw off"
elif [ $CWD == 1 ]; then
	echo -e "\n# cwdaemon"
	echo 'SUBSYSTEM=="usb", ATTRS{idVendor}=="'$(cat $DIR/cfg/s-cw-idv)'", ATTRS{idProduct}=="'$(cat $DIR/cfg/s-cw-idp)'", ATTRS{serial}=="'$(cat $DIR/cfg/s-cw-sn)'", SYMLINK+="ttyUSB.cw", RUN+="/etc/init.d/cwdaemon restart"'
elif [ $CWD == 2 ]; then
	echo -e "\n# Arduino k3ng CLI"
	CWCLIPORT=$(cat $DIR/cfg/s-cw-cwcli)
	for (( a=1 ; $a-$COMS-1 ; a=$a+1 )); do
		PORT=$(cat $DIR/cfg/s-ser2net-c${a}port)
		if [ $PORT == $CWCLIPORT ]; then
			echo 'SUBSYSTEM=="usb", ATTRS{idVendor}=="'$(cat $DIR/cfg/s-ser2net-c${a}idv)'", ATTRS{idProduct}=="'$(cat $DIR/cfg/s-ser2net-c${a}idp)'", ATTRS{serial}=="'$(cat $DIR/cfg/s-ser2net-c${a}sn)'", TAG+="systemd", ENV{SYSTEMD_WANTS}="systemctl restart cwudp.service"'
#	RUN+="systemctl --no-block restart cwudp.service"'
#	TAG+="systemd", ENV{SYSTEMD_WANTS}+="cwudp.service"'
		fi
	done
fi

if [ $FSK == 0 ]; then
	echo -e "\n# fsk off"
elif [ $FSK == 1 ]; then
	echo -e "\n# FSK to serial"
	FSKPORT=$(cat $DIR/cfg/s-fsk2serial)
	for (( a=1 ; $a-$COMS-1 ; a=$a+1 )); do
		PORT=$(cat $DIR/cfg/s-ser2net-c${a}port)
		if [ $PORT == $FSKPORT ]; then
			echo 'SUBSYSTEM=="usb", ATTRS{idVendor}=="'$(cat $DIR/cfg/s-ser2net-c${a}idv)'", ATTRS{idProduct}=="'$(cat $DIR/cfg/s-ser2net-c${a}idp)'", ATTRS{serial}=="'$(cat $DIR/cfg/s-ser2net-c${a}sn)'", TAG+="systemd", ENV{SYSTEMD_WANTS}="systemctl restart fskudp.service"'
		fi
	done
fi

echo -e "\n# RIG"
if [ $RIG == 1 ]; then
	if [ $RIGDEV == 0 ]; then
		echo 'SUBSYSTEM=="tty", ATTRS{idVendor}=="'$(cat $DIR/cfg/s-rigctld-idv)'", ATTRS{idProduct}=="'$(cat $DIR/cfg/s-rigctld-idp)'", ATTRS{serial}=="'$(cat $DIR/cfg/s-rigctld-sn)'", SYMLINK+="ttyUSB.rig", TAG+="systemd", ENV{SYSTEMD_WANTS}="systemctl restart rig.service"'
	elif [ $RIGDEV == 1 ]; then
		echo 'SUBSYSTEM=="tty", SYMLINK=="'$RIGDEVPATH'", SYMLINK+="ttyUSB.rig", TAG+="systemd", ENV{SYSTEMD_WANTS}="systemctl restart rig.service"'
# RUN+="'$DIR'/script/rig.sh restart"'
	fi
fi

echo -e "\n# Rotators $ROTS"
for (( r=1; r<=$ROTS; r++ )); do
	echo 'SUBSYSTEM=="tty", ATTRS{idVendor}=="'$(cat $DIR/cfg/s-rot-r${r}idv)'", ATTRS{idProduct}=="'$(cat $DIR/cfg/s-rot-r${r}idp)'", ATTRS{serial}=="'$(cat $DIR/cfg/s-rot-r${r}sn)'", SYMLINK+="ttyUSB.rot'${r}'"'
done

echo -e "\n# ser2net coms $COMS"
for (( c=1; c<=$COMS; c++ )); do
	DEV=$(cat $DIR/cfg/s-ser2net-c${c}dev)
	if [ $DEV == 0 ]; then
		echo 'SUBSYSTEM=="tty", ATTRS{idVendor}=="'$(cat $DIR/cfg/s-ser2net-c${c}idv)'", ATTRS{idProduct}=="'$(cat $DIR/cfg/s-ser2net-c${c}idp)'", ATTRS{serial}=="'$(cat $DIR/cfg/s-ser2net-c${c}sn)'", SYMLINK+="ttyUSB.com'${c}'"'
	fi
done

exit 0
