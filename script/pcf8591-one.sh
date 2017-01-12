#!/bin/bash
# inspired by K.Jacobs / http://www.grautier.com / CC-by-NC-SA
# original http://www.grautier.com/wiki/doku.php?id=i_c-adc-erweiterungsplatine-sw#i_c-adc-erweiterungsplatine_-_bashscript
#
# pouziti
# ./pcf8591-one.sh [cislo vstupu 0-3] [nasobitel odporoveho delice]

### Set ADC, Set VARs###
vref="3.3"     # Referencni napeti V
BusID="4f"     # Adresa na i2c sbernici

### Namerene hodnoty ###
adc=$(sudo /usr/sbin/i2cget -y 0 0x$BusID 0x0${1} w | awk -F "0x" '{print $2}' | cut -c3-4) 
adc=$(sudo /usr/sbin/i2cget -y 0 0x$BusID 0x0${1} w | awk -F "0x" '{print $2}' | cut -c3-4)

### HEX -> DEC ###
adc=$(echo "$adc" | tr '[a-z]' '[A-Z]')       # Zmena na velka pismena pro BC
adc=$(echo "ibase=16; $adc" | bc)             # HEX -> DEC

### Vypocet hodnot ###
# Vref / (MAXadcwert * adcwert ) = U
vpp=$(echo "scale=10; $vref / 255" | bc)
adc=$(echo "scale=3; $adc * $vpp * ${2}" | bc | xargs printf "%.1f")

echo $adc V

exit 0
