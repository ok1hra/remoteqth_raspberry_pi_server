#!/bin/sh
DEBVERSION=$(sed 's/\..*//' /etc/debian_version)
DIR=$(cat /etc/remoteqth-path)
MYIP=$(cat $DIR/cfg/s-vpn-ip)
USR=$(cat $DIR/cfg/s-vpn-community)
PASSWORD=$(cat $DIR/cfg/s-vpn-pass)
SUPERNODE=$(cat $DIR/cfg/s-vpn-node)
PORT=$(cat $DIR/cfg/s-vpn-nodeport)
ON=$(cat $DIR/cfg/s-vpn-on)

if [ $DEBVERSION -eq 7 ]; then			# wheezy
	echo "# Debian version $DEBVERSION"
	cat $DIR/script/n2n-1.cfg
	echo MYIP=\"$MYIP\"
	echo USR=\"$USR\"
	echo PASSWORD=\"$PASSWORD\"
	echo SUPERNODE=\"$SUPERNODE\"
	echo PORT=\"$PORT\"
	cat $DIR/script/n2n-2.cfg
elif [ $DEBVERSION -eq 8 ]; then			# jessie
	echo "# Debian version $DEBVERSION"
	echo N2N_COMMUNITY=\"$USR\"
	echo N2N_KEY=\"$PASSWORD\"
	echo N2N_SUPERNODE=\"$SUPERNODE\"
	echo N2N_SUPERNODE_PORT=\"$PORT\"
	echo N2N_IP=\"$MYIP\"
	echo N2N_DAEMON_OPTS=\"\"
	if [ $ON -eq 0 ]; then
		echo '#N2N_EDGE_CONFIG_DONE="yes"'
	elif [ $ON -eq 1 ]; then
		echo 'N2N_EDGE_CONFIG_DONE="yes"'
	fi
fi


exit 0

# Config file for the n2n edge node daemon.

# Sets the n2n community name. All edges within the same community appear on
# the same LAN (layer 2 network segment). Community name is 16 bytes in length.
#N2N_COMMUNITY="DEBIAN_n2n_Testers"

# Sets the twofish encryption key from ASCII text. All edges communicating must
# use the same key and community name.
#N2N_KEY="SuperSecurePassword"

# Sets the n2n supernode IP address and port to register to.
#N2N_SUPERNODE="remoteqth.com"
#N2N_SUPERNODE_PORT="82"

# Sets the n2n virtual LAN IP address being claimed. This is a private IP
# address. All IP addresses in an n2n community typical belong to the same /24
# net‐ work (ie. only the last octet of the IP addresses varies).
#N2N_IP="10.1.2.3"

#N2N_DAEMON_OPTS=""

# Uncomment this to get edge node started.
#N2N_EDGE_CONFIG_DONE="yes"

#TODO
# add routing option
# sudo ip route add 192.168.1.0/24 via 10.1.2.1
