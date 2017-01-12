#!/bin/bash
sleep 5  # avoiding collisions with i2c (lcd script)

DIR=$(cat /etc/remoteqth-path)
ON=$(cat $DIR/cfg/s-webcam-on)
NOTE=$(cat $DIR/cfg/s-webcam-note)
NORM=$(cat $DIR/cfg/s-webcam-norm)
HF=$(cat $DIR/cfg/s-webcam-hf)
VF=$(cat $DIR/cfg/s-webcam-vf)
DATE=$(date -u +%Y.%m.%d\ %H:%M)

# running other webcam.sh ?
RUN=$(pgrep -c webcam.sh)
if [ $RUN -gt 1 ] ; then exit 0 ; fi

# night view
# 2cron
#00 00   * * *   pi      /home/pi/webcam/sun.pl > /tmp/sun
#SUNRISE=$(echo $(grep sunrise /tmp/sun | cut -d ' ' -f2) - 100 | bc | xargs printf %.4i)
#SUNSET=$(echo $(grep sunset /tmp/sun | cut -d ' ' -f2) + 100 | bc | xargs printf %.4i)
#TIME=$(date -u +%H%M)
# je-li cas vetsi nez sunrise a zaroven plati ze je mensi nez sunset
#if [ $SUNRISE -lt $TIME ] && [ $SUNSET -gt $TIME ]
#        then echo "$(date +%F\ %H:%M:%S) $$ - je den"
#        	exit 0
#	else echo "$(date +%F\ %H:%M:%S) $$ - je noc"
#		convert /tmp/actual.jpg  -auto-level /tmp/actual-norm.jpg
#fi

if [ "$ON" == '1' ] ; then
	if [ "$HF" == '1' ] ; then HFS='-hf' ; fi
	if [ "$VF" == '1' ] ; then VFS='-vf' ; fi
	# Verbose Quality100 NoPreview 
	/usr/bin/raspistill -v -q 100 $HFS $VFS -n -o /tmp/actual-hires.jpg &> /tmp/raspistill.log
	if [ "$NORM" == '1' ] ; then
		# -auto-level -normalize -equalize
		mv /tmp/actual-hires.jpg /tmp/tmp.jpg
		/usr/bin/convert /tmp/tmp.jpg -equalize /tmp/actual-hires.jpg
	fi
	convert -font        "$DIR/script/TerminusBold-4.38.ttf" \
	        -undercolor  black \
	        -fill        white \
	        -pointsize   20 \
	        -draw        "text 25,1920 '$DATE UTC | $NOTE'" \
	/tmp/actual-hires.jpg /tmp/cam_hires.jpg

	convert -font        "$DIR/script/TerminusBold-4.38.ttf" \
	        -undercolor  black \
	        -fill        white \
	        -pointsize   12 \
		-resize      25% \
	        -draw        "text 15,470 '$DATE UTC | $NOTE'" \
	/tmp/actual-hires.jpg /tmp/cam.jpg
fi
exit 0
