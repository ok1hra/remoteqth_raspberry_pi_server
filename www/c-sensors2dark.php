<div class="graph"><?
// definice barev - temp
$LOW = '#00bbff';
$MID = '#66aa00';
$HI  = '#FF6600';
$DEF = '#888888';
// definice barev - V
$OK  = '#808080';
$KO = '#bb0000';

gethw();
if ( $hw == "PI" ) {
	$i2cbus = rxfile('../cfg/rpii2cbus');
}elseif ( $hw == "BBB" ) {
	$i2cbus = rxfile('../cfg/bbbi2cbus');
}else {
}
$lines = "0" ;
$linesad = "0" ;
$path = "../cfg/";
$pocettemp = rxfile('s-sensors-temps');

$path = "/sys/class/thermal/thermal_zone0/";
$data = rxfile('temp')/1000;
$path = "../cfg/";
// barvicky
if (($data >= -50) && ($data <= 20))
	$color = $LOW ;
else if (($data >= 20) && ($data <= 40))
	$color = $MID ;
else if ($data >= 40)
	$color = $HI ;
// bargraph rozsah od -20 do +60
?><span class="bar" style="width: <? $w=50+$data*0.8; echo $w ?>%; background: <? echo $color; ?>">RPI internal <?printf(" %.1f", $data); ?> &deg;C</span><?

if ($pocettemp == "0") {
//	echo '<span class="td1">Thermometers n/a</span>';
}
else {
	for($temp=1; $temp < $pocettemp+1; $temp++){ 
		$adress = rxfile("s-sensors-temp{$temp}");
		if ( $adress != "n/a" ) { $lines++ ;
			$data = temp("$temp", $hw);
			// barvicky
			if (($data >= -50) && ($data <= 0))
				$color = $LOW ;
			else if (($data >= 0) && ($data <= 26))
				$color = $MID ;
			else if ($data >= 26)
				$color = $HI ;
			// bargraph rozsah od -20 do +60
			?><span class="bar" style="width: <? $w=50+$data*0.8; echo $w ?>%; background: <? echo $color; ?>"><?include "../cfg/s-sensors-temp{$temp}n"; printf(" %.1f", $data); ?> &deg;C</span><?
		} else {
			break;
		}
	// konec cyklu
	}
}
?></div><div class="graph"><?
$pocetad = rxfile("s-sensors-ad");

if ($pocetad == "0") {
	?><tr><td colspan="2" class="td1">A/D sensors n/a</td></tr><?
}
else {
	for($ad=1; $ad < $pocetad+1; $ad++){ 
		$adress = rxfile("s-sensors-ad{$ad}");
		for($nr=1; $nr<5; $nr++){
			$namex = rxfile("s-sensors-ad{$ad}n{$nr}");
			$coefficient = rxfile("s-sensors-ad{$ad}c{$nr}");
			$input = $nr-1;
			if ( $namex != "n/a" ) { $linesad++ ;
				// rawdata z cidla ($nr je cislo vstupu)
				$dataraw = exec("sudo /usr/sbin/i2cget -y $i2cbus 0x{$adress} 0x0{$input} w");
				$dataraw = exec("sudo /usr/sbin/i2cget -y $i2cbus 0x{$adress} 0x0{$input} w");
				// prevod dvou bajtu na dekadicke
				$data = hexdec(substr($dataraw,4,2)) ;
				// vypocet napeti
				$volt = $data*(3.3/255)*$coefficient;
				//echo $adress.'-'.$input.'|'.$dataraw.'|'.$data.'|' ;
				// barvicky
				$from = rxfile("s-sensors-ad{$ad}f{$nr}");
				$to = rxfile("s-sensors-ad{$ad}t{$nr}");
				if (($volt >= $from) && ($volt <= $to))
					$color = $OK ;
				else $color = $KO ; ?>
				<span class="bar" style="width: <? $w=50+$volt*0.8; echo $w ?>%; background: <? echo $color; ?>"><?include "../cfg/s-sensors-ad{$ad}n{$nr}"; printf(" %.1f", $volt); ?> V</span><?
			}
		}
	} 
}
?></div>
