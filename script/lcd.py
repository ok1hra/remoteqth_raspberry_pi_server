#!/usr/bin/python
# sudo python lcd.py [i2c-bus] [line1] [line2]


import sys
import pylcdlib  

x = int(sys.argv[1])

lcd = pylcdlib.lcd(0x20,x)

print sys.argv

lcd.lcd_puts(sys.argv[2],1)
lcd.lcd_puts(sys.argv[3],2)
