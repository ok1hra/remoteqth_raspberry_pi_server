#!/bin/bash
DIR=$(cat /etc/remoteqth-path)
I2CBUS=$(cat $DIR/cfg/rpii2cbus)
I2CADDR=$(cat $DIR/cfg/lcd-i2c-addr)
UP=$(cat /tmp/eth0ip)
DWN=$(uptime | awk -F'load average:' '{ print $2 }' | sed s/,//g)

$DIR/script/lcd.py $I2CBUS "$UP" "$DWN  "

exit 0
