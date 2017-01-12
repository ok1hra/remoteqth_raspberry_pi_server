<!doctype html>
<html>
<head>
	<title><? include '../cfg/s-login-note';?> | FSK control</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body scroll="no">
<div id="obsah">
<!-- #################### Obsah #################### -->
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
	echo '<h1>Serial to FSK interface</h1>';
	?><form action="c-fsk.php" method="POST">
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
	<form action="c-fsk.php" method="POST">
		<p class="text2">Text: 
		<input type="text" name="cwtext" size="30" maxlength="30">
		<input type="submit" value="Send">
		<? $cwtext = isset($_POST['cwtext']) ? $_POST['cwtext'] : 1;
		if (isset($_POST['cwtext'])) {
			echo $cwtext;
			udpsocket($IP, $fskport, $cwtext);
		}
		?></p>
	</form>
	<h1> </h1><?
	echo '<p class="text">You can access the FSK control over <b>UDP port 7891</b>. Replug usb, to restart process:</p>';
	exec('ps aux | grep "[s]ocat UDP4-RECVFROM:7891,fork" > /tmp/ps-fsk');
	echo '<pre>';
	include '/tmp/ps-fsk';
	echo '</pre>';

}


if ( $fsk != "0" ) {
	echo '<p class="text2"><a href="c-fsk2.php" onclick="window.open( this.href, this.href, \'width=490,height=140,left=0,top=0,menubar=no,location=no,status=no\' ); return false;"  title="CW memory">Split this window <img src="split.png" alt="split window"></a></p>';
}
?>
<p class="next"><a href="c-cw.php"><img src="previous.png" alt="previous page"></a><a href="c-rigctld.php"><img src="next.png" alt="next page"></a></p>
<!-- #################### /Obsah #################### -->
</div>
<div id="header"><?php include 'header.html';?></div>
</div><div id="leftmenu"><?php include 'menu.html';?></div>
</body>
</html>

