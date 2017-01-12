<!doctype html>
<html>
<head>
	<title><? include '../cfg/s-login-note';?> | Band decoder</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="styles.css" rel="stylesheet" type="text/css">
	<link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:300italic,400italic,700italic,400,700,300&subset=latin-ext' rel='stylesheet' type='text/css'>
	<meta http-equiv="Refresh" content="60">
<script>
var xmlhttp, band = -1, target = -1, centerx, centery, r;
function loadXMLDoc(url,cfunc)
{
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=cfunc;
    xmlhttp.open("GET",url,true);
    xmlhttp.send();
}
function updateQrg()
{
    loadXMLDoc("get.php?q=4", function()
    {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            band = parseInt(xmlhttp.responseText); 
            document.getElementById("band").innerHTML = band + 'm';

        }
    });
}
function start(){
    setInterval('updateQrg()', 1000);
}
window.onload = start;
</script>
</head>
<body scroll="no">
<div id="obsah">
<!-- #################### Obsah #################### -->
<h1>Band decoder</h1>
<? require 'function.php';
$path = "../cfg/";
$rigon = rxfile('s-rigctld-on');
$bandon = rxfile("s-band-on");

if ( $bandon == "0" || $rigon == "0" ){
	echo '<p class="text">Decoder <a href="s-band-decoder.php">disable.</a></p>';
}
else { ?>
	<span id="band" class="band">connected...</span><br><br><br>
	<p class="text2"><a href="band.php" onclick="window.open( this.href, this.href, 'width=350,height=70,left=0,top=0,menubar=no,location=no,status=no' ); return false;"  title="band">Split window <img src="split.png" alt="split window"></a>
	<form action="c-band-decoder.php" method="POST" class="next">
	<input type="submit" name="restart" value="Restart daemon">
	<? if (isset($_POST['restart'])) {
	//	exec('../script/band.sh restart > /dev/null 2>&1 &');
		exec('sudo /bin/systemctl restart bd.service');
	}
	echo '</form>';?>
	Process:</p><?
	exec('ps aux | grep [b]and-decoder.sh > /tmp/ps-band');
	echo '<pre>';
	include '/tmp/ps-band';
	echo '</pre>';
}?>

<p class="next"><a href="c-rigctld.php"><img src="previous.png" alt="previous page"></a><a href="c-webcam.php"><img src="next.png" alt="next page"></a></p>
<!-- #################### /Obsah #################### -->
</div>
<div id="header"><?php include 'header.html';?></div>
</div><div id="leftmenu"><?php include 'menu.html';?></div>
</body>
</html>

