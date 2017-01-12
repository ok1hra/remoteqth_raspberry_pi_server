#!/bin/sh

# number of sensors
SENS=8
CESTA='/home/pi/remoteqth/cfg/s-sensors-temp'

rm $CESTA[[:digit:]]c                                    # smaze stare hodnoty
# get Sensors address > TEMP${SENS} ($SENS = 1-8)
until [ "$SENS" -eq 0 ]; do                              # cyklus pokracuje dokud neni splnena podminka ($SENS = 0)
	# echo -n "$SENS "                               # cislo akualniho sensoruX
	eval `echo ADR${SENS}=$(cat $CESTA${SENS})`      # adresa sensoruX = obsah soboruX
	# echo `eval echo '$'ADR${SENS}`                 # adresa sensoruX
	ADRNR=$(echo -n `eval echo '$'ADR${SENS}`)       # adr=adresa sensoruX
	if [ $ADRNR != "--" ]; then                      # pokud neni sensor OFF coz znaci '--' namisto adresy, vycte sensorX a hodnotu prevede na °C
		sudo i2cget -y 0 0x$ADRNR 0x00 w | \
		awk '{printf("%.1f\n", (a=((("0x"substr($1,5,2)substr($1,3,1))*0.0625)+0.1))>128?a-256:a)}' > $CESTA${SENS}c
	fi
	SENS=$((SENS - 1))                               # snizi cislo sensoru o jednu
done
exit 0
