<!doctype html>
<html>
<head>
	<title><? include '../cfg/s-login-note';?> | Sensors</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="styles.css" rel="stylesheet" type="text/css">
	<meta http-equiv="refresh" content="55">
</head>
<body scroll="no">
<div id="obsah">
<!-- #################### Obsah #################### -->

<h1>Read sensors</h1>
<table border="0" cellspacing="0" cellpadding="0">
<?
require 'function.php';
// definice barev - temp
$LOW = '#00bbff';
$MID = '#66aa00';
$HI  = '#FF6600';
$DEF = '#888888';
// definice barev - V
$OK  = '#808080';
$KO = '#ff0000';

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

echo '<tr><td class="td1" width="150px">RPI internal</td><td class="td2">'; 
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
	?><span style="color: <? echo $color; ?>"><? printf("%.1f", $data); ?></span> &deg;C<?
echo '</td>';

if ($pocettemp == "0") {

}
else {
	for($temp=1; $temp < $pocettemp+1; $temp++){ 
		$adress = rxfile("s-sensors-temp{$temp}");
		if ( $adress != "n/a" ) { $lines++ ?>
		<tr>
		<td class="td1" width="150px"><?include "../cfg/s-sensors-temp{$temp}n" ?></td>
	        <td class="td2"><? 
			$data = temp("$temp", $hw);
			// barvicky
			if (($data >= -50) && ($data <= 0))
				$color = $LOW ;
			else if (($data >= 0) && ($data <= 26))
				$color = $MID ;
			else if ($data >= 26)
				$color = $HI ;
			?><span style="color: <? echo $color; ?>"><? printf("%.1f", $data); ?></span> &deg;C<?
		} else {
			break;
		} ?></td>
		</tr>
	<? // konec cyklu
	}
}

?><tr><td colspan="2"><hr></td></tr><?

$pocetad = rxfile("s-sensors-ad");

if ($pocetad == "0") {
	?><tr><td colspan="2"><p class="text2">No A/D sensors defined...</p></td></tr><?
}
else {
	for($ad=1; $ad < $pocetad+1; $ad++){ 
		$adress = rxfile("s-sensors-ad{$ad}");
		for($nr=1; $nr<5; $nr++){
			$name = rxfile("s-sensors-ad{$ad}n{$nr}");
			$coefficient = rxfile("s-sensors-ad{$ad}c{$nr}");
			$input = $nr-1;
			if ( $name != "n/a" ) { $linesad++ ; ?>
				<tr>
				<td class="td1" width="150px"><?include "../cfg/s-sensors-ad{$ad}n{$nr}" ?></td>
        			<td class="td2"><?
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
					else $color = $KO ;
					?><span style="color: <? echo $color; ?>"><? printf("%.1f", $volt); ?></span> V
				</td>
				</tr><?
			}
		}
	} 
}
?></table>

<h1> </h1>
<p class="text2"><a href="c-sensors2.php" onclick="window.open( this.href, this.href, 'width=250,height=<?php echo ($lines+$linesad)*25+15 ; ?>,left=0,top=0,menubar=no,location=no,status=no' ); return false;"  title="Sensors">Split this window <img src="split.png" alt="split window"></a></p>
<p class="next"><a href="c-relay.php"><img src="previous.png" alt="previous page"></a><a href="c-rot.php"><img src="next.png" alt="next page"></a></p>

<!-- #################### /Obsah #################### -->
</div>
<div id="header"><?php include 'header.html';?></div>
</div><div id="leftmenu"><?php include 'menu.html';?></div>
</body>
</html>

