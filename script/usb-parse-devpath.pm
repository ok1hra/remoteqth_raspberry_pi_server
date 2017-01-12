#!/usr/bin/perl -w
# path query 'sudo udevadm info --query=path --name=/dev/ttyUSB0'

@items = split("/", $ARGV[0]);
for ($i = 0; $i < @items; $i++) {
    if ($items[$i] =~ m/usb[0-9]+$/) {
#       print $items[$i + 1] . "\n";    # parse first dir after  usbX = 1-1 (/devices/platform/bcm2708_usb/usb1/1-1/1-1.3/1-1.3.1/1-1.3.1:1.0/ttyUSB0/tty/ttyUSB0)
#       print $items[$i + 2] . "\n";    # parse second dir after usbX = 1-1.3
	$items[$i + 3] =~ s/:/-/g ;     # replace ':' if items is 1-1.3.1:1.0
        print $items[$i + 3] . "\n";    # parse third dir after  usbX = 1-1.3.1
        last;
    }
}
