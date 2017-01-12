#!/bin/bash

DIR=$(cat /etc/remoteqth-path)
RIG=$(cat $DIR/cfg/s-rigctld-on)
DECODER=$(cat $DIR/cfg/s-band-on)
PROGDIR="/usr/bin"
STATUS='/tmp/band-decoder-status'
HW=$(cat $DIR/cfg/server-hw)
REV=$(grep Revision /proc/cpuinfo | cut -d ':' -f2 | sed 's/ //g')


if [ $HW == 'PI' ]; then
	#if [ $REV -gt 15 ]; then 
        if [ $REV == '0010' ] || [ $REV == '0012' ] || [ $REV == 'a01041' ] || [ $REV == 'a21041' ] || [ $REV == 'a02082' ] || [ $REV == 'a22082' ]; then # model B+
		GPIO='24'
	else
		GPIO='15'
	fi	
elif [ $HW == 'BBB' ]; then
	GPIO='28'
fi

while true; do

	# enable rigctld & band-decoder form?
	if [ $RIG == 0 ] || [ $DECODER == 0 ]; then
		exit 0
	fi

	# precteni freq
	#freq=$($PROGDIR/rigctl -m 1901 -r localhost f) # verze pro rpc.rigd
	freq=$($PROGDIR/rigctl -m 2 -r 127.0.0.1 f)     # verze pro rigctrld

	# get_freq: error = Communication timed out
	# get_freq: error = Protocol error
	# rig_open: error = IO error
	# (standard_in) 1: syntax error
	err=$(echo $freq | grep error | wc -l)

	# chyba pri cteni (trx off?)
	while [ $err -ge 1 ]
	do
#		echo -e '\n\e[1;30m  *** TRX OFF *** \e[0m'
#		echo -n 'err' > $STATUS
		sleep 3
		freq=$($PROGDIR/rigctl -m 2 -r 127.0.0.1 f)
		err=$(echo $freq | grep error | wc -l)
		PWR=$(cat $DIR/cfg/gpio1)
#		echo "pwr $PWR | err $err | f $freq"
		if [ $err -ge 1 ] && [ $PWR == '0' ]; then
			echo -n 'off' > $STATUS
			exit 0

#		for (( r=1; r<=$GPIO; r++ )); do                  # if trx off and gpio 1 off - all bd gpio off
#			if [ -e "$DIR/cfg/s-band${r}-on" ] && [ $PWR -eq 0 ]; then # if file exist and PWR off
#				ON=$(cat $DIR/cfg/s-band${r}-on)  # read
#				if [ $ON ] && [ $ON == 1 ] ; then # if length is nonzero
#					echo '0' > $DIR/cfg/gpio${r}
#				fi
#			fi
#		done
		fi
		if [ $err -ge 1 ] && [ $PWR == '1' ]; then
			echo -n 'err' > $STATUS
		fi
#		#clear
	done

	if [ $freq ] && [ $err == 0 ]; then

		# zaokrouhleni na kHz
		khz=$(echo "$freq/1000" | bc | xargs printf "%.0f")

		#echo -n '' > $STATUS
		#echo "$khz | $freq"

		# relay on gpio
		for (( r=1; r<=$GPIO; r++ )); do
			if [ -e "$DIR/cfg/s-band${r}-on" ] ; then # if file exist
				ON=$(cat $DIR/cfg/s-band${r}-on)  # read
				if [ $ON ] && [ $ON == 1 ] ; then # if length is nonzero
					FROM=$(cat $DIR/cfg/s-band${r}-from)
					TO=$(cat $DIR/cfg/s-band${r}-to)
					if [ $khz -ge $FROM ] && [ $khz -le $TO ]; then
					#	echo rel$r $ON $FROM $khz $TO ON $(cat $DIR/cfg/s-band${r}-name)
						echo '1' > $DIR/cfg/gpio${r}
						echo -n "$(cat $DIR/cfg/s-band${r}-name)" > $STATUS
					else
					#	echo rel$r $ON $FROM $khz $TO OFF
						echo '0' > $DIR/cfg/gpio${r}
					fi
				fi
			fi
		done
	fi
sleep 1

done

exit 0
