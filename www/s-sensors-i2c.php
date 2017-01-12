<!doctype html>
<html>
<head>
	<title><? include '../cfg/s-login-note';?> | Sensors</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body>
	<?
	require 'function.php'; 
	gethw();
	if ( $hw == "PI" ) {
		$i2c = rxfile('../cfg/rpii2cbus'); ?>
		<form action="s-sensors-i2c.php" method="post"><p class="status1"><input type="submit" value="Refresh"> address device on i2c bus nr<? echo $i2c; ?>...</p>
			<?php echo exec("sudo /usr/sbin/i2cdetect -y -a $i2c > /tmp/i2c"); ?>
		</form>
		<pre><?php include '/tmp/i2c';?></pre><?
	}elseif ( $hw == "BBB" ) {
		$i2c = rxfile('../cfg/bbbi2cbus'); ?>
		<form action="s-sensors-i2c.php" method="post"><p class="status1"><input type="submit" value="Refresh"> address device on i2c bus nr<? echo $i2c; ?>...</p>
			<?php echo exec("sudo /usr/sbin/i2cdetect -y -r $i2c > /tmp/i2c"); ?>
		</form>
		<pre><?php include '/tmp/i2c';?></pre><?
	}else {
		echo '<p class="warn">I2C device not connected...</p>' ;
	} ?>
</body>
</html>

