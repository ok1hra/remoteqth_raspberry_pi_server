#!/bin/bash
# sun picture locate /home/pi/.xplanet/images/
DIR=$(cat /etc/remoteqth-path)
ROTS=$(cat $DIR/cfg/s-rot-rots)

if [ $ROTS == 0 ]; then
        exit 0
fi

# atchitecture #
# armv6l - RPI
# armv7l - BBB
# i586   - Alix1c
# i686   - ...
ARCH=$(uname -m)

if [ "$ARCH" == 'armv6l' ] ; then
	BIN='armv6l'
elif [ "$ARCH" == 'armv7l' ] ; then
	BIN='armv7l'
elif [ "$ARCH" == 'i686' ] || [ "$ARCH" == 'i586' ] ; then
    	BIN='i686'
else
	BIN='none'
fi

$DIR/script/sun-$BIN  > /tmp/marker
LONLAT=$(cat $DIR/cfg/s-rot-loc | $DIR/script/maiden2lonlat.py)

/usr/bin/xplanet -window -config $DIR/script/geoconfig $LONLAT -geometry 319x319 -projection azimuthal -num_times 1 -output /tmp/map.png
composite $DIR/script/azimuth.png /tmp/map.png /tmp/azimuth-map.png

exit 0
