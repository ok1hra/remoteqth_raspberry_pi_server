<!doctype html>
<html>
<head>
	<title><? include '../cfg/s-login-note';?> | USB</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body>
	<form action="s-usb2.php" method="post"><p class="status1"><input type="submit" value="Refresh"> ls...</p>
		<?php echo exec('ls -al /dev/ttyUSB* | cut -d\'/\' -f3 | grep \'>\' > /tmp/usb2'); ?>
	</form>
<pre>
<?php include '/tmp/usb2';?>
</pre>
</body>
</html>

