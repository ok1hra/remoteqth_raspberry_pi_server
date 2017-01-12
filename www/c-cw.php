<!doctype html>
<html>
<head>
	<title><? include '../cfg/s-login-note';?> | CW</title>
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
$cwd = rxfile('s-cw-cwd');  //cw device 0-disable 1-cwd 2-cwcli
$cwddev = '/dev/ttyUSB.cw'; //cw daemon device
$cwdaemonport = '6789' ;    //cw daemon UDP port
$cwcliport = '7890'; //UDP   //rxfile('s-cw-cwcli'); //cw CLI TCP port
$memories = rxfile('s-cw-mem');  // number of memories

// DISABLE
if ( $cwd == "0" ) {
		echo '<h1>CW disable</h1>';
}

// CW daemon
if ( $cwd == "1" ) {
	echo '<h1>CWdaemon</h1>';
	if (file_exists($cwddev)) {
	   	//echo "The file $cwddev exists"; ?>
		<form action="c-cw.php" method="POST">
		<p class="text2">
		<?
		// cyklus pro pameti
		for ($mem = 1; $mem < $memories+1; ++$mem){ 
			$value = rxfile("s-cw-mem$mem");
			if ( $value != "n/a" ) { ?>
				<input type="submit" name="<? echo "cw$mem"; ?>" value="<? echo $value; ?>">
				<? if (isset($_POST["cw$mem"])) {
					$cwmem = rxfile("s-cw-mem{$mem}c");
					echo $cwmem ;
					udpsocket($IP, $cwdaemonport, $cwmem);
				} ?>
		<br><?	}
		} ?><hr>
		WPM: <input type="submit" name="wpm15" value="15">
		<input type="submit" name="wpm25" value="20">
		<input type="submit" name="wpm25" value="25">
		<input type="submit" name="wpm25" value="30">
		<input type="submit" name="wpm35" value="35">
		<input type="submit" name="plus" value="+">
		<input type="submit" name="minus" value="-">
		<br>Set:<input type="submit" name="stop" value="STOP">
		<? if (isset($_POST['plus'])) {
			udpsocket($IP, $cwdaemonport, '++');
		}
		else if (isset($_POST['minus'])) {
			udpsocket($IP, $cwdaemonport, '--');
		}
		//  http://cqrlog.svn.sourceforge.net/viewvc/cqrlog/trunk/src/uCWKeying.pas?revision=475&view=markup - od řádku 471, Chr(27) je ESC
		// v php je esc \e - ESC 2 25 nastaví rychlost na 25WPM
		else if (isset($_POST['stop'])) {
			udpsocket($IP, $cwdaemonport, "\e4");
		}
		else if (isset($_POST['wpm15'])) {
			udpsocket($IP, $cwdaemonport, "\e215");
		}
		else if (isset($_POST['wpm20'])) {
			udpsocket($IP, $cwdaemonport, "\e220");
		}
		else if (isset($_POST['wpm25'])) {
			udpsocket($IP, $cwdaemonport, "\e225");
		}
		else if (isset($_POST['wpm30'])) {
			udpsocket($IP, $cwdaemonport, "\e230");
		}
		else if (isset($_POST['wpm35'])) {
			udpsocket($IP, $cwdaemonport, "\e235");
		}
		?></p>
		</form>
		<form action="c-cw.php" method="POST">
			<p class="text2">Text: 
			<input type="text" name="cwtext" size="30" maxlength="30">
			<input type="submit" value="Send">
			<? $cwtext = $_POST['cwtext'];
			if (isset($_POST['cwtext'])) {
				echo $cwtext;
				udpsocket($IP, $cwdaemonport, $cwtext);
			}
			?></p>
		</form>
		<h1> </h1>
	<?
	} else { 
		echo '<p class="warn">CW daemon USB device not connected.</p>';
	}
}
// Arduino CW CLI
if ( $cwd == "2" ) {
	echo '<h1>CW Arduino K3NG CLI</h1>';
	?><form action="c-cw.php" method="POST">
	<p class="text2">
	<?
	// cyklus pro pameti
	for ($mem = 1; $mem < $memories+1; ++$mem){ 
		$value = rxfile("s-cw-mem$mem");
		if ( $value != "n/a" ) { ?>
			<input type="submit" name="<? echo "cw$mem"; ?>" value="<? echo $value; ?>">
			<? if (isset($_POST["cw$mem"])) {
				$cwmem = rxfile("s-cw-mem{$mem}c");
				echo $cwmem ;
				udpsocket($IP, $cwcliport, $cwmem);
			} ?>
	<br><?	}
	} ?><hr>
	WPM: <input type="submit" name="wpm15" value="15">
	<input type="submit" name="wpm20" value="20">
	<input type="submit" name="wpm25" value="25">
	<input type="submit" name="wpm30" value="30">
	<input type="submit" name="wpm35" value="35"><br>Set:
	<input type="submit" name="tune" value="Tune">
	<input type="submit" name="x1" value="Tx1">
	<input type="submit" name="x2" value="Tx2">
	<input type="submit" name="stop" value="STOP">
	<? //  http://blog.radioartisan.com/arduino-cw-keyer/
	if (isset($_POST['tune'])) {udpsocket($IP, $cwcliport, '\t'."\r");}
	else if (isset($_POST['stop'])) {udpsocket($IP, $cwcliport, '\\'."\r");}
	else if (isset($_POST['x1'])) {udpsocket($IP, $cwcliport, '\x1'."\r");}
	else if (isset($_POST['x2'])) {udpsocket($IP, $cwcliport, '\x2'."\r");}
	else if (isset($_POST['tone'])) {udpsocket($IP, $cwcliport, '\o'."\r");}
	else if (isset($_POST['wpm15'])) {udpsocket($IP, $cwcliport, '\w15'."\r");}
	else if (isset($_POST['wpm20'])) {udpsocket($IP, $cwcliport, '\w20'."\r");}
	else if (isset($_POST['wpm25'])) {udpsocket($IP, $cwcliport, '\w25'."\r");}
	else if (isset($_POST['wpm30'])) {udpsocket($IP, $cwcliport, '\w30'."\r");}
	else if (isset($_POST['wpm35'])) {udpsocket($IP, $cwcliport, '\w35'."\r");}
	?></p>
	</form>
	<form action="c-cw.php" method="POST">
		<p class="text2">Text: 
		<input type="text" name="cwtext" size="30" maxlength="30">
		<input type="submit" value="Send">
		<? $cwtext = isset($_POST['cwtext']) ? $_POST['cwtext'] : 1;
		if (isset($_POST['cwtext'])) {
			echo $cwtext;
			udpsocket($IP, $cwcliport, $cwtext);
		}
		?></p>
	</form>
	<h1> </h1><?
	echo '<p class="text">You can access the CW control over <b>UDP port 7890</b>. Replug usb restart process:</p>';
	exec('ps aux | grep "[s]ocat UDP4-RECVFROM:7890,fork" > /tmp/ps-cw');
	echo '<pre>';
	include '/tmp/ps-cw';
	echo '</pre>';

}


if ( $cwd != "0" ) {
	echo '<p class="text2"><a href="c-cw2.php" onclick="window.open( this.href, this.href, \'width=490,height=140,left=0,top=0,menubar=no,location=no,status=no\' ); return false;"  title="CW memory">Split this window <img src="split.png" alt="split window"></a></p>';
}
?>
<p class="next"><a href="c-rot.php"><img src="previous.png" alt="previous page"></a><a href="c-fsk.php"><img src="next.png" alt="next page"></a></p>
<!-- #################### /Obsah #################### -->
</div>
<div id="header"><?php include 'header.html';?></div>
</div><div id="leftmenu"><?php include 'menu.html';?></div>
</body>
</html>

