<!doctype html>
<html>
<head>
	<title><? include '../cfg/s-login-note';?> | Relay</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<link href="styles.css" rel="stylesheet" type="text/css">
	<meta http-equiv="refresh" content="55">
</head>
<body bgcolor="#000000">

<div class="splitwindow"><?
require 'function.php';
$path = "../cfg/";
$info = '';
$server = "0";
$lines = "0" ;
$table = 'black';
// definice barev
$ON = '#c00';
$OFF = '#000';
$THROW = '#090';
$TEXT = '#eee';

echo '<span style="color: '.$TEXT.'"><b>'.rxfile('s-login-note').'</b></span>';
echo '<form action="c-relay-pi2.php" method="POST">';
require 'c-relay-pi-s.php';
echo '</form>';
$nrservers = rxfile('s-rot2-servers');
if ( $nrservers != "0" ) {
	for ($server = 1; $server < $nrservers+1; ++$server) {
		echo '<span style="color: '.$TEXT.'"><b>'.rxfile("s-rot-s{$server}note").'</b></span>' ;
		$serverip = rxfile("s-rot2-s{$server}ip");
		echo ' ('.$serverip.')';
		if (availableUrl("$serverip", 80, 3) == 1){			  // host, port, timeout
			echo '<form action="c-relay-pi2.php" method="POST">';
				include 'c-relay-pi-s.php';
			echo '</form>';
		}else{
			echo '<span style="color: red; font-weight: bold;"> - OFFLINE</span>';
		}
	}
}
?></div>

</body>
</html>

