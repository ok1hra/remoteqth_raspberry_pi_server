<!doctype html>
<html>
<head>
	<title><? include '../cfg/s-login-note';?> | Webcam</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="styles.css" rel="stylesheet" type="text/css">
	<meta http-equiv="Refresh" content="60">
</head>
<body scroll="no">
<div id="obsah">
<!-- #################### Obsah #################### -->
<h1>View webcam</h1>
<p class="text2">
<? require 'function.php';
$path = "../cfg/";
$on = rxfile('s-webcam-on');
$norm = rxfile('s-webcam-norm');
$hf = rxfile('s-webcam-hf');
$vf = rxfile('s-webcam-vf');
	if ($norm == "1") {
		echo 'Image is a equalized ';
	}
	if ($hf == "1") {
		echo'&#8596; Horizontally mirrored ';
	}
	if ($vf == "1") {
		echo '&#8597; Vertically mirrored ';
	}
?></p><?
gethw();
if ( $hw == "PI" ) {
	$info = '<pre class="info">Detecting RPI, <b>webcam may be available.</b></pre>' ;
	if ($on == "0") {
		?><p class="text2">RaspiCam disable...</p><?
	}
	else {
		if (file_exists('cam.jpg')) {
		    echo '<img src="cam.jpg"><br>';
		} else {
		    echo '<p><i>Picture yet not been generated</i></p><br>';
		}
		echo '<p class="text2">Page will be updated automatically every minute. | <a href="raspistill.txt" title="log">Log</a> | <a href="c-webcam2.php" title="Webcam">Click to enlarge</a> | <a href="c-webcam3.php" onclick="window.open( this.href, this.href, \'width=648,height=486,left=0,top=0,menubar=no,location=no,status=no\' ); return false;"  title="Webcam">Split window <img src="split.png" alt="split window"></a></p>';
	}
}
elseif ( $hw == "ALIX" ) {
	$info = '<pre class="info">Detecting hardware Alix, <b>RaspiCam NOT available.</b></pre>' ;
}
else 	{
	$info = '<pre class="info">Hardware not detected - <b>RaspiCam NOT available.</b></pre>' ;
}
echo "<br>$info"; ?>

<p class="next"><a href="c-rigctld.php"><img src="previous.png" alt="previous page"></a><a href="c-vna.php"><img src="next.png" alt="next page"></a></p>
<!-- #################### /Obsah #################### -->
</div>
<div id="header"><?php include 'header.html';?></div>
</div><div id="leftmenu"><?php include 'menu.html';?></div>
</body>
</html>

