#!/bin/sh

DIR=$(cat /etc/remoteqth-path)

DHCP=$(cat $DIR/cfg/s-net-dhcp)
IP=$(cat $DIR/cfg/s-net-ip)
MASK=$(cat $DIR/cfg/s-net-mask)
GATE=$(cat $DIR/cfg/s-net-gate)
DNS=$(cat $DIR/cfg/s-net-dns)

if [ "$DHCP" -eq "1" ]; then
	cat $DIR/script/interfaces
	exit 0
fi

cat $DIR/script/interface

echo 'auto eth0'
echo 'iface eth0 inet static'
echo '      address '$IP
echo '      netmask '$MASK
echo '      gateway '$GATE
echo '      dns-nameservers '$DNS

exit 0
