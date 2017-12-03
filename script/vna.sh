#!/bin/sh
# https://rigexpert.com/products/kits-analyzers/aa-30-zero/getting-started-with-the-zero/

if [ -z $5 ]; then
	clear
	/bin/echo -e "\n Missing argument \n\n"
	/bin/echo -e "./vna.sh [center frequency Hz] [range Hz] [number of measurements] [IP] [port]\n\n"
	exit 0
fi

DELAY=$(echo $3/12+2 | bc)

{ /bin/echo "FQ$1"; sleep 1; echo "SW$2"; sleep 1; echo "FRX$3"; sleep $DELAY; } | /usr/bin/telnet $4 $5 | grep ',' > /tmp/vna.log 2>&1

/usr/bin/gnuplot << EOF
	set output '/tmp/vna.svg'
	set terminal svg size 1200, 800
	set grid
	set ylabel 'R'
	set y2label 'J'
	set y2tics
	set lmargin at screen 0.05
	set rmargin at screen 0.95

	set style rect fc rgb '#00bbff' fs solid 0.09 noborder
	set obj rect from 1.81, graph 0 to 2, graph 1
	set obj rect from 3.5, graph 0 to 3.8, graph 1
	set obj rect from 7, graph 0 to 7.2, graph 1
	set obj rect from 10.1, graph 0 to 10.15, graph 1
	set obj rect from 14, graph 0 to 14.35, graph 1
	set obj rect from 18.068, graph 0 to 18.168, graph 1
	set obj rect from 21, graph 0 to 21.45, graph 1
	set obj rect from 24.89, graph 0 to 24.99, graph 1
	set obj rect from 28, graph 0 to 29.7, graph 1

  set datafile separator ','
	set style line 1 lt 1 lc rgb '#00cc00' lw 1
		set style line 2 pt 7 lc rgb '#00cc00' lw 2
	set style line 3 lt 1 lc rgb 'orange' lw 1
		set style line 4 pt 7 lc rgb 'orange' lw 2
	set style line 5 lt 1 lc rgb '#8888cc' lw 2
	set style line 6 lt 1 lc rgb 'gray' lw 2

	set multiplot layout 3,1
	plot \
	     '/tmp/vna.log' using 1:3 ls 3 t 'J' w lines smooth csplines  axes x1y2,\
		'/tmp/vna.log' using 1:3 ls 4 notitle axes x1y2,\
	     '/tmp/vna.log' using 1:2 ls 1 t 'R' w lines smooth csplines, \
		'/tmp/vna.log' using 1:2 ls 2 notitle

	unset y2tics
	unset ylabel
	set y2label 'VSWR'
	RefCoeffP(x,y) = 1+(abs(sqrt((x-50)*exp(2)+y*exp(2))/sqrt((x+50)*exp(2)+y*exp(2))))
	RefCoeffM(x,y) = 1-(abs(sqrt((x-50)*exp(2)+y*exp(2))/sqrt((x+50)*exp(2)+y*exp(2))))
	stats '/tmp/vna.log' using (RefCoeffP(\$2,\$3)/RefCoeffM(\$2,\$3))
	plot '/tmp/vna.log' using 1:(RefCoeffP(\$2,\$3)/RefCoeffM(\$2,\$3)) title sprintf("50 ohm minimum = %.3f", STATS_min) ls 5 w lines smooth csplines
	unset yrange
	unset ytics

	set ytics 0,5
	set xlabel 'MHZ'
	set y2label 'dB'
	plot '/tmp/vna.log' using 1:(-20*log((sqrt((\$2-50)*exp(2)+\$3*exp(2)))/sqrt((\$2+50)*exp(2)+\$3*exp(2)))) t 'Return Loss' ls 6 w lines smooth csplines
	unset multiplot
	set output
EOF

exit 0
