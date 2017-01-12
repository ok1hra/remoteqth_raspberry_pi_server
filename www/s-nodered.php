<!doctype html>
<html>
<head>
	<title><? include '../cfg/s-login-note';?> | Node-RED</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="styles.css" rel="stylesheet" type="text/css">
	<meta http-equiv="Refresh" content="60">
</head>
<body scroll="no">
<div id="obsah">
<!-- #################### Obsah #################### -->
<p class="text2">More about settings in <a href="https://www.remoteqth.com/wiki/index.php?page=Node-RED" target="_blank" class="external">Wiki</a></p>

<h1>Node-RED</h1>
<? require 'function.php';
$ip = $_SERVER['SERVER_NAME'];

echo "<p><a href=\"http://$ip:1880\" target=\"_blank\"><img src=\"node-red.png\"><br>Available on $ip:1880</a></p>";
?>
	<form action="s-nodered.php" method="POST" class="">
	<input type="submit" name="start" value="Start Node-RED">
	<input type="submit" name="stop" value="Stop Node-RED">
	<? if (isset($_POST['start'])) {
		exec('sudo /etc/init.d/node_red start');
	}
	if (isset($_POST['stop'])) {
		exec('sudo /etc/init.d/node_red stop');
	}
	echo '</form>';
	echo '<p>For run after startup enable in terminal</p><pre>sudo rcconf</pre>';
	echo '<p>Process:</p>';
	exec('ps aux | grep [n]ode-red > /tmp/ps-nodered');
	echo '<pre>';
	include '/tmp/ps-nodered';
	echo '</pre>';

	echo '<p class="status1"><a href="node-red.log" _target="blank" class="external">Log</a></p>';
	echo '<form action="s-nodered.php#r" method="post" id="r"><input type="submit" value="Refresh"></form>';
?>

<p class="next"><a href="s-mqtt.php"><img src="next.png" alt="next page"></a></p>
<!-- #################### /Obsah #################### -->
</div>
<div id="header"><?php include 'header.html';?></div>
</div><div id="leftmenu"><?php include 'menu.html';?></div>
</body>
</html>

