<!doctype html>
<html>
<head>
	<title><? include '../cfg/s-login-note';?> | FSK control</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#000000">
<?
require 'function.php';
$IP = '127.0.0.1' ;
$path = "../cfg/";
$fsk = rxfile('s-fsk');  //cw device 0-disable 1-fsk
$fskport = '7891';		 //UDP
$memories = rxfile('s-fsk-mem');  // number of memories

// DISABLE
if ( $fsk == "0" ) {
		echo '<h1>Serial to FSK disable</h1>';
}

// serial2fsk
if ( $fsk == "1" ) {
	echo '<h1>FSK interface</h1>';
	?><form action="c-fsk2.php" method="POST">
	<p class="text2">
	<?
	// cyklus pro pameti
	for ($mem = 1; $mem < $memories+1; ++$mem){ 
		$value = rxfile("s-fsk-mem$mem");
		if ( $value != "n/a" ) { ?>
			<input type="submit" name="<? echo "cw$mem"; ?>" value="<? echo $value; ?>">
			<? if (isset($_POST["cw$mem"])) {
				$cwmem = rxfile("s-fsk-mem{$mem}c");
				echo $cwmem ;
				udpsocket($IP, $fskport, $cwmem);
			} ?>
	<br><?	}
	} ?></p>
	</form>
	<form action="c-fsk2.php" method="POST">
		<p class="text2">Text: 
		<input type="text" name="cwtext" size="30" maxlength="30">
		<input type="submit" value="Send">
		<? $cwtext = isset($_POST['cwtext']) ? $_POST['cwtext'] : 1;
		if (isset($_POST['cwtext'])) {
			echo $cwtext;
			udpsocket($IP, $fskport, $cwtext);
		}
}
		?></p>
	</form>
</body>
</html>
