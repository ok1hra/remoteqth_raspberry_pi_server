<!doctype html>
<html>
<head>
	<title><? include '../cfg/s-login-note';?> | MQTT</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="styles.css" rel="stylesheet" type="text/css">
	<meta http-equiv="Refresh" content="60">
</head>
<body scroll="no">
<div id="obsah">
<!-- #################### Obsah #################### -->
<p class="text2">More about settings in <a href="https://www.remoteqth.com/wiki/index.php?page=MQTT" target="_blank" class="external">Wiki</a></p>

<h1>MQTT Broker</h1>
<? require 'function.php';
$rigon = rxfile('../cfg/s-rigctld-on');
$bandon = rxfile("../cfg/s-band-on");
$ip = $_SERVER['SERVER_NAME'];

echo "<img src=\"mosquitto.png\">";
?>
	<form action="s-mqtt.php" method="POST" class="">
	<input type="submit" name="start" value="Start mosquitto">
	<input type="submit" name="stop" value="Stop mosquitto">
	<? if (isset($_POST['start'])) {
		exec('sudo /etc/init.d/mosquitto start');
	}
	if (isset($_POST['stop'])) {
		exec('sudo /etc/init.d/mosquitto stop');
	}
	echo '</form>';
	echo '<p>For run after startup enable in terminal</p><pre>sudo rcconf</pre>';
	echo '<p>Process:</p>';
	exec('ps aux | grep sbin/[m]osquitto > /tmp/ps-mosquitto');
	echo '<pre>';
	include '/tmp/ps-mosquitto';
	echo '</pre>';

	echo '<p class="status1">Log:</p>';
	echo '<pre>';
	if (file_exists('/var/log/mosquitto/mosquitto.log')) {
		include '/var/log/mosquitto/mosquitto.log';
	}
	echo '</pre>';
	echo '<form action="s-mqtt.php#r" method="post" id="r"><input type="submit" value="Refresh"></form>';
?>

<p class="next"><a href="s-nodered.php"><img src="previous.png" alt="previous page"></a></p>
<!-- #################### /Obsah #################### -->
</div>
<div id="header"><?php include 'header.html';?></div>
</div><div id="leftmenu"><?php include 'menu.html';?></div>
</body>
</html>

