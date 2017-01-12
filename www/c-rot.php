<!doctype html>
<html>
<head>
	<title><? include '../cfg/s-login-note';?> | Rotator</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body scroll="no">
<div id="obsah">
<!-- #################### Obsah #################### -->

<h1>Rotators</h1>
<?
require 'function.php';
$path = "../cfg/";
$pocetrot = rxfile('s-rot-rots');

if ($pocetrot == "0") {
	?><p class="text2">No Rotators defined.</p><?
}
else { ?>
<table  class="rot">
<tr class="prvni">
	<th>Server</th>
	<th>Rotator</th>
	<th>Name</th>
	<th>From</th>
	<th>To</th>
	<th colspan="2">CONTROL</th>
</tr>
<tr>
	<th>local</th>
	<th></th>
	<th></th>
	<th>&#8630;</th>
	<th>&#8631;</th>
	<th>static</th>
	<th>realtime</th>
</tr>
<?	for($rot=1; $rot < $pocetrot+1; $rot++) {
		$name = rxfile("s-rot-r{$rot}name");
		$rotl = rxfile("s-rot-r{$rot}from");
		$rotr = rxfile("s-rot-r{$rot}to");?>
		<tr>
			<td></td>
			<td><?echo $rot; ?></td>
			<td><? echo $name ; ?></td>
			<td><? echo $rotl ; ?>&deg;</td>
			<td><? echo $rotr ; ?>&deg;</td>
			<td class="center"><a href="c-rotx.php?rot=<? echo $rot ;?>"
				onclick="window.open( this.href, this.href, 'width=320,height=480,left=0,top=0,menubar=no,location=no,status=no' ); return false;"  title="<? echo $name ; ?>">
				<img src="split.png" alt="split window"></a></td>
			<td class="center"><a href="c-rrotx.php?rot=<? echo $rot.'&ip=127.0.0.1&name='.$name.'&rotl='.$rotl.'&rotr='.$rotr.'&external=n&server=no' ; ?>"
				onclick="window.open( this.href, this.href, 'width=320,height=400,left=0,top=0,menubar=no,location=no,status=no' ); return false;"  title="<? echo $name ; ?>">
				<img src="split.png" alt="split window"></a></td>
		</tr>
<?	}
}
$nrservers = rxfile('s-rot2-servers');
if ( $nrservers != "0" ) {

	for ($server = 1; $server < $nrservers+1; ++$server) { ?>
		<tr>
			<th><?echo $server; ?></th>
			<th><?php include "../cfg/s-rot-s{$server}rots";?></th>
			<th><?php include "../cfg/s-rot-s{$server}note";?></th>
			<th colspan="2"><a href="http://<?php
				$serverip = rxfile("s-rot2-s{$server}ip");
				echo $serverip;?>" target="_blank"><?php echo $serverip;
				if (availableUrl("$serverip", 80, 3) == 1){			  // host, port, timeout
					echo '<span style="color: #080; font-weight: bold;"> ONLINE</span>';
				}else{
					echo '<span style="color: red; font-weight: bold;"> OFFLINE</span>';
				}?>&#8599;</a>
			</th>
			<th>Control</th>
			<th class="center"><a href="http://<?php include "../cfg/s-rot2-s{$server}ip";?>/mob.php" onclick="window.open( this.href, this.href, 'width=320,height=600,left=0,top=0,menubar=no,location=no,status=no' ); return false;" title="<?php include "../cfg/s-rot-s{$server}note";?>"><img src="split.png" alt="split window"></a></th>
		</tr><?
		$srotators[$server] = rxfile("s-rot-s{$server}rots");
		$IP = rxfile("s-rot2-s{$server}ip");
		for ($rot = 1; $rot < $srotators[$server]+1; ++$rot) {
			$name = rxfile("s-rot-s{$server}r{$rot}name");
			$rotl = rxfile("s-rot-s{$server}r{$rot}from");
			$rotr = rxfile("s-rot-s{$server}r{$rot}to");
			$rotget = rxfile("s-rot-s{$server}r{$rot}get");
			$rotset = rxfile("s-rot-s{$server}r{$rot}set"); ?>
			<tr>
				<td><img src="cluster2.png" alt="on cluster"></td>
				<td><?echo $rot; ?></td>
				<td><?php include "../cfg/s-rot-s{$server}r{$rot}name";?></td>
				<td><?php include "../cfg/s-rot-s{$server}r{$rot}from";?>&deg;</td>
				<td><?php include "../cfg/s-rot-s{$server}r{$rot}to";?>&deg;</td>
				<td></td>
				<td class="center"><a href="c-rrotx.php?rot=<? echo $rot.'&ip='.$IP.'&name='.$name.'&rotl='.$rotl.'&rotr='.$rotr.'&external=y&server='.$server ; ?>"
					onclick="window.open( this.href, this.href, 'width=320,height=400,left=0,top=0,menubar=no,location=no,status=no' ); return false;" 
					title="<?php include "../cfg/s-rot-s{$server}r{$rot}name";?>"><img src="split.png" alt="split window"></a></td>
			</tr>
<?		}
	}
}
?>
</table>
<p class="next"><a href="c-sensors.php"><img src="previous.png" alt="previous page"></a><a href="c-cw.php"><img src="next.png" alt="next page"></a></p>

<!-- #################### /Obsah #################### -->
</div>
<div id="header"><?php include 'header.html';?></div>
<div id="leftmenu"><?php include 'menu.html';?></div>
</body>
</html>

