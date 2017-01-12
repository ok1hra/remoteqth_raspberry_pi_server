<!doctype html>
<html>
<head>
	<title><? include '../cfg/s-login-note';?> | dmesg</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body>
	<form action="s-usb.php" method="post"><p class="status1"><input type="submit" value="Refresh"> dmesg...</p>
		<?php echo exec('dmesg | grep -i usb | tail -n 30 > /tmp/usb'); ?>
	</form>
<pre>
<?php include '/tmp/usb';?>
</pre>
</body>
</html>

