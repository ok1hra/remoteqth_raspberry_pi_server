<!doctype html>
<html>
<head>
	<title><? include '../cfg/s-login-note';?> | Status</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body scroll="no">
<div id="obsah">
<!-- #################### Obsah #################### -->
<span style="position: absolute; top: 15px; left: 600px; z-index: backup"><img src="index.png"></span>
<h1>Status page</h1>
<?php require 'function.php'; ?>

<form action="update.php" method="POST">
<p class="status1">Build</p>
<pre>Actual: <?php
$server = "remoteqth.com";
$debversion = rxfile2('/etc/debian_version');
if ($debversion < 8 ){	// Wheezy
	$build = rxfile("../build2");
		include '../build2';
	$buildFile = 'build2';
}
if ($debversion <= 8 ){	// Jessie
	$build = rxfile("../build3");	// --------------------------------------------------------------------------<<<<<<<<<<<<<<<<
		include '../build3';	
	$buildFile = 'build3';
}
	//kontrola build ze serveru + kontrola navratove hodnoty
	if ($buildnew = file_get_contents("http://$server/$buildFile", true)+0) {  // <-- +1 aby se zobrazoval update vzdy
		//navratova hodnota true = check build OK, nasleduje porovnani s lokalnim
		if ( $build == $buildnew ) {
			echo "Software up to date.";
		}
		else if ( $build > $buildnew ) {
			echo 'Mishmash - actual build > serverbuild.';
		}
		else if ( $build < $buildnew ) {
			echo "The software is <span style=\"color: #f00\"><b>old,</b></span> please <input type=\"submit\" name=\"update\" value=\"update\"> to $buildnew";
		}
	}
	// navratova hodnota flase = kontrola se nezdarila
	else {
		echo "Check new <a href='http://remoteqth.com/$buildFile'>build</a> FAIL.";
	} ?></pre>
</form>

<form action="index.php" method="POST">
<? gethw();
txfile('../cfg/server-hw', $hw);
$url = '';
if ( $hw == "PI" ) {
	$rev = rpi2rev();
	//if ( $rev == "0x2" || $rev == "0x3") { //  HEX
	//if ($rev < 4) {                        //  DEC
	if ( $rev == "0002" || $rev == "0003") { //  CPUinfo
		$i2cbusnew = "0";
	}
	else {
		$i2cbusnew = "1";
	}
	if ( $rev == '0002' ) {
		$model = "Model B Revision 1.0 [256 MB]";
	} elseif ( $rev == '0003' ) {
		$model = "Model B Revision 1.0 + ECN0001 (no fuses, D14 removed) [256 MB]";
	} elseif ( $rev == '0004' || $rev == '0005' || $rev == '0006') {
		$model = "Model B Revision 2.0 - Mounting holes [256 MB]";
	} elseif ( $rev == '0007' || $rev == '0008' || $rev == '0009') {
		$model = "Model A Mounting holes [256 MB]";
	} elseif ( $rev == '000d' || $rev == '000e' || $rev == '000f') {
		$model = "Model B Revision 2.0 - Mounting holes [512 MB]";
	} elseif ( $rev == '0010' ) {
		$model = "Model B+ [512 MB]";
	} elseif ( $rev == '0011' ) {
		$model = "Compute Module [512 MB]";
	} elseif ( $rev == '0012' ) {
		$model = "Model A+ [256 MB]";
	} elseif ( $rev == 'a01041' || $rev == 'a21041' ) {
		$model = "Pi 2 Model B [1 GB]";
	} elseif ( $rev == 'a02082' || $rev == 'a22082' ) {
		$model = "Pi 3 Model B [1 GB]";
	}else {
	}
	$i2cbus = rxfile('../cfg/rpii2cbus');
	if ( $i2cbusnew != $i2cbus ) {
		//echo '|'.$i2cbus.'|'.$i2cbusnew.'|' ;
		txfile('../cfg/rpii2cbus', $i2cbusnew);
	}
	$url = ' | <a href="http://remoteqth.com/wiki/index.php?page=Status+page#Update" target="_blank" class="external">revision table</a>';
	$hwx = 'Rasperry PI | revision '.$rev.' | '.$model ;
}elseif ( $hw == "BBB" ) {
	$i2cbus = 1 ;
	txfile('../cfg/bbbi2cbus', $i2cbus);
	$url = ' - <a href="http://beagleboard.org/Support/bone101" target="_blank" class="external">introduction</a>';
	preg_match("/bone(..)/",php_uname(''),$BBB);
	$hwx = 'Beaglebone Black | '.$BBB[0] ;
}elseif ( $hw == "ALIX" ) {
	$kernel = exec('uname -r');

	if($_POST['rw']) {
		exec('sudo /usr/local/sbin/remountrw');
	}
	elseif($_POST['ro']) {
		exec('sudo /usr/local/sbin/remountro');
	}
	// nahrada grep
	$mounts = file_get_contents ('/proc/mounts');    // vypis filesystemu do promenne
	$findme   = 'ROOT_FS / ext2';                    // hledany retezec
	$pos = strpos($mounts, $findme);                 // pozice hledaneho retezce v promenne
	$readwrite = substr($mounts, $pos+15, 2);        // hledana promenna je za hledanym retezcem na pozici $pos+15
	if ($readwrite == 'ro') {
		$switch = "file system is <span style=\"color: #f00\"><b>Read-only</b></span> please remount to <input type=\"submit\" name=\"rw\" value=\"Read-write \">";
	}
	elseif ($readwrite == 'rw') {
		$switch = "file system is <b>Read-write,</b> possibility switch to <input type=\"submit\" name=\"ro\" value=\"Read-only \">";
	}
	$hwx = 'Alix | kernel '.$kernel.' '.$readwrite.' | '.$switch ;

}
else {
	$hwx = $hw ;
}
echo '<p class="status1">Hardware'.$url.'</p><pre>'.$hwx ; ?></pre>

</form>

<pre class="qr">
	<? $path = getcwd();
	include "$path/../cfg/s-net-qrcode";?>
	M o b i l e  U R L</pre>

<? $path = "../cfg/";
$rots = rxfile('s-rot-rots');
	if ($rots > 0) {  ?>
		<p class="status1">Actual grayline map with sun</p>
		<p class="status2"><img src="azimuth-map.png"><br>Locator: <?php include '../cfg/s-rot-loc';?></p>
	<?}?>

<p class="status1">Uptime</p>
<pre><? echo exec('uptime'); ?></pre>

<!--<p class="status1">Disk space</p>
<pre><? echo exec('df -h | grep root'); ?></pre>-->

<p class="status1">You & server IP</p>
<pre> <?php $ip = $_SERVER['REMOTE_ADDR']; $ips = $_SERVER['SERVER_ADDR']; echo "$ip <--> $ips"; ?> | DHCP <?php include '../cfg/s-net-dhcp';?> | eth0: <?php include '/tmp/eth0ip';?></pre>

<p class="status1">Recognized USB devices</p>
<pre>
<? if ($handle = opendir('/dev')) {
	while (false !== ($entry = readdir($handle))) {
		$pozice = strpos($entry, "USB.");
		if( $pozice ) {
			echo substr($entry, $pozice+4, strlen($entry)).' | ';
		}	
	}
	closedir($handle);
} ?></pre>

<p class="status1">Rotators config</p>

<pre>
Rotator From   To    Note
-------------------------------------
<? $pocetrot = rxfile("s-rot-rots");
for ($rot = 1; $rot < $pocetrot+1; ++$rot){
	echo $rot.'       '.rxfile("s-rot-r{$rot}from").'°  '.rxfile("s-rot-r{$rot}to").'°  '.rxfile("s-rot-r{$rot}name")."\n" ;
} ?></pre>

<p class="status1">Ser2Net config</p>
<pre>
COM     IP port  Note
-------------------------------------
<? $pocetcomu = rxfile("s-ser2net-coms");
for ($com = 1; $com < $pocetcomu+1; ++$com){
	echo 'com'.$com.'    '.rxfile("s-ser2net-c{$com}port").'    '.rxfile("s-ser2net-c{$com}name")."\n" ;
} ?></pre>

<p class="status1">ser2net conf</p>
<pre><?php include '/etc/ser2net.conf';?></pre>

<p class="status1">udev rules</p>
<pre><?php include '/etc/udev/rules.d/99-remoteqth.rules';?></pre>

<p class="status1">NTP</p>
<? echo exec('/usr/bin/ntpq -p > /tmp/ntp'); ?>
<pre><? include "/tmp/ntp"; ?></pre>

<p class="status1">Interfaces</p>
<pre><?php include '/etc/network/interfaces';?></pre>

<p class="status1">Ifconfig</p>
<pre><? exec('sudo /sbin/ifconfig > /tmp/ifconfig'); include '/tmp/ifconfig' ?></pre>

<p class="status1">resolv.conf</p>
<pre><?php include '/etc/resolv.conf';?></pre>

<? echo '<p class="status1">Current PHP version: '.phpversion().'</p>';

//phpinfo(); ?>

<!-- #################### /Obsah #################### -->
</div>
<div id="header"><?php include 'header.html';?></div>
</div><div id="leftmenu"><?php include 'menu.html';?></div>
</body>
</html>

