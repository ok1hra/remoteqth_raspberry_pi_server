<!doctype html>
<html>
<head>
	<title><? include '../cfg/s-login-note';?> | WebRIG</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="styles.css" rel="stylesheet" type="text/css">
	<link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:300italic,400italic,700italic,400,700,300&subset=latin-ext' rel='stylesheet' type='text/css'>
</head>
<body scroll="no">
<div id="obsah">
<!-- #################### Obsah #################### -->
<h1>Web VNA - <a href="https://rigexpert.com/products/kits-analyzers/aa-30-zero/" class="external" target="_blank">RigExpert AA-30.ZERO</a></h1>
<? require 'function.php';
// apt-get install telnet
// symlink /tmp/vna.svg
$path = "../cfg/";
$on = rxfile('s-vna-on');
if ($on == "1") {
	?><p class="text2">VNA disable.</p><?
}
else {
	if (file_exists('vna.svg')) {
	    echo '<img src="vna.svg"><br>';
	} else {
	    echo '<p><strong>Graph yet not been generated</strong><br><br>';
	    echo 'How to setup:<br>';
	    echo '<ul><li>Connect <a href="https://rigexpert.com/products/kits-analyzers/aa-30-zero/" class="external" target="_blank">RigExpert AA-30.ZERO VNA</a> via USB to UART-TTL serial converter to Raspberry PI USB</li>';
	    echo '<li>Setup <a href="s-ser2net.php">ser2net</a> with settings in red box <br><img src="vna-ser2net.png" style="border:1px solid gray"></li>';
	    echo '<li>Replug USB</li>';
	    echo '<li>Press band button for measure...</li></ul>';
	}
	if (file_exists('vna.log')) {
	    echo '<a href="vna.log" class="external">Download measure LOG</a><br><br>';
	}

	echo '<form action="c-vna.php" method="POST">' ;

	echo '<input type="submit" name="160m" value="160 m">&nbsp;' ;
	echo '<input type="submit" name="80m"  value="80 m">&nbsp;' ;
	echo '<input type="submit" name="40m"  value="40 m">&nbsp;' ;
	echo '<input type="submit" name="30m"  value="30 m">&nbsp;' ;
	echo '<input type="submit" name="20m"  value="20 m">&nbsp;' ;
	echo '<input type="submit" name="17m"  value="17 m">&nbsp;' ;
	echo '<input type="submit" name="15m"  value="15 m">&nbsp;' ;
	echo '<input type="submit" name="13m"  value="12 m">&nbsp;' ;
	echo '<input type="submit" name="10m"  value="10 m">&nbsp;' ;
	echo '</form>';
	$WAIT=10;
	if (isset($_POST['160m'])) {
		exec('/home/pi/remoteqth/script/vna.sh 1905000 290000 20 127.0.0.1 200');
		sleep($WAIT);
	}
	if (isset($_POST['80m'])) {
		exec('/home/pi/remoteqth/script/vna.sh 3650000 400000 20 127.0.0.1 200');
		sleep($WAIT);
	}
	if (isset($_POST['40m'])) {
		exec('/home/pi/remoteqth/script/vna.sh 7100000 300000 20 127.0.0.1 200');
		sleep($WAIT);
	}
	if (isset($_POST['30m'])) {
		exec('/home/pi/remoteqth/script/vna.sh 10125000 100000 20 127.0.0.1 200');
		sleep($WAIT);
	}
	if (isset($_POST['20m'])) {
		exec('/home/pi/remoteqth/script/vna.sh 14175000 400000 20 127.0.0.1 200');
		sleep($WAIT);
	}
	if (isset($_POST['17m'])) {
		exec('/home/pi/remoteqth/script/vna.sh 18098000 200000 20 127.0.0.1 200');
		sleep($WAIT);
	}
	if (isset($_POST['15m'])) {
		exec('/home/pi/remoteqth/script/vna.sh 21225000 550000 20 127.0.0.1 200');
		sleep($WAIT);
	}
	if (isset($_POST['13m'])) {
		exec('/home/pi/remoteqth/script/vna.sh 24940000 200000 20 127.0.0.1 200');
		sleep($WAIT);
	}
	if (isset($_POST['10m'])) {
		exec('/home/pi/remoteqth/script/vna.sh 28850000 2000000 20 127.0.0.1 200');
		sleep($WAIT);
	}
}?>

<p class="next"><a href="c-webcam.php"><img src="previous.png" alt="previous page"></a></p>
<!-- #################### /Obsah #################### -->
</div>
<div id="header"><?php include 'header.html';?></div>
</div><div id="leftmenu"><?php include 'menu.html';?></div>
</body>
</html>

