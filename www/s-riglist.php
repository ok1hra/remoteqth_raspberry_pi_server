<!doctype html>
<html>
<head>
	<title><? include '../cfg/s-login-note';?> | Rig</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body>
	<?php echo exec('/usr/bin/rigctld -l > /tmp/riglist'); ?>
<pre>
<?php include '/tmp/riglist';?>
</pre>
</body>
</html>

