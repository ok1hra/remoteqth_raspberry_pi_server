#!/bin/bash
echo "$(date +\%F_%H:%M) - update.sh start"
DIR=$(cat /etc/remoteqth-path)
BUILD=$(cat $DIR/build3)
HW=$(cat $DIR/cfg/server-hw)
#LOGIN=$(cat $DIR/cfg/s-login-call)

echo "build:    $BUILD"
echo "hardware: $HW"

if [ -f $DIR/../.htaccess ]; then cp $DIR/../.htaccess $DIR/www/.htaccess; fi

if [ $HW == 'PI' ]; then 
	echo '--set GPIO-PI'
	if [ -r $DIR/script/gpio ]; then
		echo '--update /etc/init.d/gpio startup script'
		cat $DIR/script/gpio > /etc/init.d/gpio
		echo '--and restart'
		/etc/init.d/gpio
		echo '--end'
	fi
	echo '--test cfg/GPIOx'
	        #REV=$(cat /sys/module/bcm2708/parameters/boardrev)
        REV=$(grep Revision /proc/cpuinfo | cut -d ':' -f2 | sed 's/ //g')
		#0002   - Model B Revision 1.0 	256MB 
		#0003   - Model B Revision 1.0 + ECN0001 (no fuses, D14 removed) 	256MB
		#0004-6 - Model B Revision 2.0Mounting holes 	256MB 	
		#0007-9 - Model A Mounting holes 	256MB
		#000d-f - Model B Revision 2.0 Mounting holes 	512MB
		#0010   - Model B+ 	512MB
		#0011   - Compute Module 	512MB
		#0012   - Model A+ 	256MB
		#a01041|a21041 - Pi 2 Model B 	1GB

	if ! [ -L $DIR/cfg/gpio1 ]; then echo --gpio1-non-symlink-create; rm $DIR/cfg/gpio1; /bin/ln -sf /sys/class/gpio/gpio4/value $DIR/cfg/gpio1; fi
	if ! [ -L $DIR/cfg/gpio2 ]; then echo --gpio2-non-symlink-create; rm $DIR/cfg/gpio2; /bin/ln -sf /sys/class/gpio/gpio17/value $DIR/cfg/gpio2; fi
	if ! [ -L $DIR/cfg/gpio3 ]; then echo --gpio3-non-symlink-create; rm $DIR/cfg/gpio3; /bin/ln -sf /sys/class/gpio/gpio18/value $DIR/cfg/gpio3; fi
        #if [ $REV -lt 4 ]; then # GPIO 21/27 set
        if [ $REV = '0002' ] || [ $REV = '0003' ]; then # GPIO 21/27 set
		if ! [ -L $DIR/cfg/gpio4 ]; then echo --gpio4-non-symlink-create; rm $DIR/cfg/gpio4; /bin/ln -sf /sys/class/gpio/gpio21/value $DIR/cfg/gpio4; fi
	else
		if ! [ -L $DIR/cfg/gpio4 ]; then echo --gpio4-non-symlink-create; rm $DIR/cfg/gpio4; /bin/ln -sf /sys/class/gpio/gpio27/value $DIR/cfg/gpio4; fi
	fi
	if ! [ -L $DIR/cfg/gpio5 ]; then echo --gpio5-non-symlink-create; rm $DIR/cfg/gpio5; /bin/ln -sf /sys/class/gpio/gpio22/value $DIR/cfg/gpio5; fi
	if ! [ -L $DIR/cfg/gpio6 ]; then echo --gpio6-non-symlink-create; rm $DIR/cfg/gpio6; /bin/ln -sf /sys/class/gpio/gpio23/value $DIR/cfg/gpio6; fi
	if ! [ -L $DIR/cfg/gpio7 ]; then echo --gpio7-non-symlink-create; rm $DIR/cfg/gpio7; /bin/ln -sf /sys/class/gpio/gpio24/value $DIR/cfg/gpio7; fi
	if ! [ -L $DIR/cfg/gpio8 ]; then echo --gpio8-non-symlink-create; rm $DIR/cfg/gpio8; /bin/ln -sf /sys/class/gpio/gpio25/value $DIR/cfg/gpio8; fi
	if ! [ -L $DIR/cfg/gpio9 ]; then echo --gpio9-non-symlink-create; rm $DIR/cfg/gpio9; /bin/ln -sf /sys/class/gpio/gpio7/value $DIR/cfg/gpio9; fi
	if ! [ -L $DIR/cfg/gpio10 ]; then echo --gpio10-non-symlink-create; rm $DIR/cfg/gpio10; /bin/ln -sf /sys/class/gpio/gpio8/value $DIR/cfg/gpio10; fi
	if ! [ -L $DIR/cfg/gpio11 ]; then echo --gpio11-non-symlink-create; rm $DIR/cfg/gpio11; /bin/ln -sf /sys/class/gpio/gpio9/value $DIR/cfg/gpio11; fi
	if ! [ -L $DIR/cfg/gpio12 ]; then echo --gpio12-non-symlink-create; rm $DIR/cfg/gpio12; /bin/ln -sf /sys/class/gpio/gpio10/value $DIR/cfg/gpio12; fi
	if ! [ -L $DIR/cfg/gpio13 ]; then echo --gpio13-non-symlink-create; rm $DIR/cfg/gpio13; /bin/ln -sf /sys/class/gpio/gpio11/value $DIR/cfg/gpio13; fi
	if ! [ -L $DIR/cfg/gpio14 ]; then echo --gpio14-non-symlink-create; rm $DIR/cfg/gpio14; /bin/ln -sf /sys/class/gpio/gpio14/value $DIR/cfg/gpio14; fi
	if ! [ -L $DIR/cfg/gpio15 ]; then echo --gpio15-non-symlink-create; rm $DIR/cfg/gpio15; /bin/ln -sf /sys/class/gpio/gpio15/value $DIR/cfg/gpio15; fi
        #if [ $REV -gt 15 ]; then # model B+
        if [ $REV == '0010' ] || [ $REV == '0012' ] || [ $REV == 'a01041' ] || [ $REV == 'a21041' ]; then # model B+
		if ! [ -L $DIR/cfg/gpio16 ]; then echo --gpio16-non-symlink-create; rm $DIR/cfg/gpio16; /bin/ln -sf /sys/class/gpio/gpio5/value $DIR/cfg/gpio16; fi
		if ! [ -L $DIR/cfg/gpio17 ]; then echo --gpio17-non-symlink-create; rm $DIR/cfg/gpio17; /bin/ln -sf /sys/class/gpio/gpio6/value $DIR/cfg/gpio17; fi
		if ! [ -L $DIR/cfg/gpio18 ]; then echo --gpio18-non-symlink-create; rm $DIR/cfg/gpio18; /bin/ln -sf /sys/class/gpio/gpio12/value $DIR/cfg/gpio18; fi
		if ! [ -L $DIR/cfg/gpio19 ]; then echo --gpio19-non-symlink-create; rm $DIR/cfg/gpio19; /bin/ln -sf /sys/class/gpio/gpio13/value $DIR/cfg/gpio19; fi
		if ! [ -L $DIR/cfg/gpio20 ]; then echo --gpio20-non-symlink-create; rm $DIR/cfg/gpio20; /bin/ln -sf /sys/class/gpio/gpio16/value $DIR/cfg/gpio20; fi
		if ! [ -L $DIR/cfg/gpio21 ]; then echo --gpio21-non-symlink-create; rm $DIR/cfg/gpio21; /bin/ln -sf /sys/class/gpio/gpio19/value $DIR/cfg/gpio21; fi
		if ! [ -L $DIR/cfg/gpio22 ]; then echo --gpio22-non-symlink-create; rm $DIR/cfg/gpio22; /bin/ln -sf /sys/class/gpio/gpio20/value $DIR/cfg/gpio22; fi
		if ! [ -L $DIR/cfg/gpio23 ]; then echo --gpio23-non-symlink-create; rm $DIR/cfg/gpio23; /bin/ln -sf /sys/class/gpio/gpio21/value $DIR/cfg/gpio23; fi
		if ! [ -L $DIR/cfg/gpio24 ]; then echo --gpio24-non-symlink-create; rm $DIR/cfg/gpio24; /bin/ln -sf /sys/class/gpio/gpio26/value $DIR/cfg/gpio24; fi
        fi
	if ! [ -L $DIR/www/band-decoder-status ]; then echo band-decoder-status-non-symlink-create; /bin/ln -sf /tmp/band-decoder $DIR/www/band-decoder-status; fi
	if ! [ -r /usr/bin/bc ]; then
		echo '--install bc'
		#apt-get update
		apt-get install bc -y
	fi
	if ! [ -r /usr/bin/socat ]; then
		echo '--install bc'
		#apt-get update
		apt-get install socat -y
	fi
elif [ $HW == 'BBB' ]; then
	echo '--set GPIO-BBB'
	if [ -r $DIR/script/gpio-bbb ]; then
		echo '--update /etc/init.d/gpio startup script'
		cat $DIR/script/gpio-bbb > /etc/init.d/gpio
		echo '--and restart'
		/etc/init.d/gpio
		echo '--end'
	fi
	echo '--test cfg/GPIOx'
fi

echo "$(date +\%F_%H:%M) - update.sh end"
exit 0

