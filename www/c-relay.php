<!doctype html>
<html>
<head>
	<title><? include '../cfg/s-login-note';?> | Relay</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body scroll="no">
<div id="obsah">
<!-- #################### Obsah #################### -->
<h1>Relay's control</h1>

<?
require 'function.php';
$path = "../cfg/";
$info = '';
$server = "0";
$lines = "0" ;
$table = 'white';
// definice barev
$ON = '#d00';
$OFF = '#fff';
$THROW = '#090';
$THROWB = '#009';
$TEXT = '#000';

echo '<b>Local</b>';
echo '<form action="c-relay.php" method="POST">';
require 'c-relay-pi-s.php';
echo '</form>';
$nrservers = rxfile('s-rot2-servers');
if ( $nrservers != "0" ) {
	for ($server = 1; $server < $nrservers+1; ++$server) {
		echo '<b>'.rxfile("s-rot-s{$server}note").'</b>' ;
		$serverip = rxfile("s-rot2-s{$server}ip");
		echo ' ('.$serverip.')';
		if (availableUrl("$serverip", 80, 3) == 1){			  // host, port, timeout
			echo '<form action="c-relay.php" method="POST">';
				include 'c-relay-pi-s.php';
			echo '</form>';
		}else{
			echo '<span style="color: red; font-weight: bold;"> - OFFLINE</span>';
		}
	}
}
$saverelay = rxfile("s-relay-save");
if ( $saverelay == "1" ) {
	echo '<span style="color: #08f">Stored settings will be set after reboot.</span>';
}?>
<h1> </h1>
<p class="text2"><a href="c-relay-pi2.php" onclick="window.open( this.href, this.href, 'width=310,height=<?php echo $lines*40 ; ?>,left=0,top=0,menubar=no,location=no,status=no' ); return false;"  title="Relay's controll">Split this window <img src="split.png" alt="split window"></a></p>


<?
gethw();
if ( $hw == "PI" ) {
	$rev = rpi2rev();
//	if ($rev < 16) {  // B+ detection
	if (preg_match('/00(02|03|0[456]|0[789]|0[def]|11)/', $rev)){
		$nrgpio = '15';
	}else{
		$nrgpio = '24';
	}
	$alert = "<pre class=\"info\">Detecting RPI rev. $rev - <b>$nrgpio GPIO</b></pre>";
}elseif ( $hw == "BBB" ) {
	$nrgpio = '28';
	$alert = "<pre class=\"info\">Detecting BeagleboneBlack <b>$nrgpio GPIO</b></pre>";
}else 	{
	$alert = "Detecting hardware \n$hw" ;
	$nrgpio = '8';
}

$multtithrow = 0;
for($rel=1; $rel<=$nrgpio; $rel++){
	$switch[$rel] = rxfile("s-relay-sw-{$rel}");
	if ($switch[$rel] == "1") { //multi throw?
		$multtithrow++;
	}
}
if ($multtithrow > "1") {
	echo '<p class="text2"><a href="c-relay-pi-ant.php" onclick="window.open( this.href, this.href, \'width=310,height=400,left=0,top=0,menubar=no,location=no,status=no\' ); return false;"  title="Relay\'s controll">'.$multtithrow.' antennas switching table <img src="ant-sw-table.png"> Ant-<span style="color: #080;">sw-1</span> <img src="split.png" alt="split window"></a></p>';
}

$multtithrowb = 0;
for($rel=1; $rel<=$nrgpio; $rel++){
	$switch[$rel] = rxfile("s-relay-swb-{$rel}");
	if ($switch[$rel] == "1") { //multi throw?
		$multtithrowb++;
	}
}
if ($multtithrowb > "1") {
	echo '<p class="text2"><a href="c-relay-pi-antb.php" onclick="window.open( this.href, this.href, \'width=310,height=400,left=0,top=0,menubar=no,location=no,status=no\' ); return false;"  title="Relay\'s controll">'.$multtithrowb.' antennas switching table <img src="ant-sw-table.png"> Ant-<span style="color: #080;">sw-2</span> <img src="split.png" alt="split window"></a></p>';
}




echo "<br>$info";
?>

<p class="next"><a href="c-sensors.php"><img src="next.png" alt="next page"></a></p>
<!-- #################### /Obsah #################### -->
</div>
<div id="header"><?php include 'header.html';?></div>
</div><div id="leftmenu"><?php include 'menu.html';?></div>
</body>
</html>

