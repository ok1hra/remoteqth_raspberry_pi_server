<!doctype html>
<html>
<head>
	<title><? include '../cfg/s-login-note';?> | WebRIG</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="styles.css" rel="stylesheet" type="text/css">
	<link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:300italic,400italic,700italic,400,700,300&subset=latin-ext' rel='stylesheet' type='text/css'>
	<meta http-equiv="Refresh" content="60">
<script>
var xmlhttp, freq = -1, target = -1, centerx, centery, r;
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
function zeroPad(num, places) {
  var zero = places - num.toString().length + 1;
  return Array(+(zero > 0 && zero)).join("0") + num;
}
function updateQrg()
{
    loadXMLDoc("get.php?q=1", function()
    {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            freq = parseInt(xmlhttp.responseText); 
	    //freq = zeroPad(freq2, 8);
	    freq = freq.toString().replace(/([0-9]*)([0-9]{3})/, '$1.$2');
	    freq = freq.toString().replace(/([0-9]*)([0-9]{3})/, '$1 $2');
            document.getElementById("freq").innerHTML = '<span class="lcd">' + freq + '</span> <span class="khz">kHz</span>';

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
<h1>WebRIG</h1>
<? require 'function.php';
$path = "../cfg/";
$on = rxfile('s-rigctld-on');
if ($on == "0") {
	?><p class="text2">Rig disable.</p><?
}
else {
	echo '<span id="freq" class="freq">connected...</span><br><br><p class="text2">FREQ only <a href="freq.php" onclick="window.open( this.href, this.href, \'width=450,height=70,left=0,top=0,menubar=no,location=no,status=no\' ); return false;"  title="freq">Split window <img src="split.png" alt="split window"></a></p>';
	echo '<p class="text2">WebRIG <a href="webrig/" onclick="window.open( this.href, this.href, \'width=1230,height=150,left=0,top=0,menubar=no,location=no,status=no\' ); return false;"  title="freq">Split window <img src="split.png" alt="split window"></a></p>';



	echo '<p class="text">You can access the radio control over IP port 4532 with Hamlib rigctrl setting:</p><pre>rigctl -m 2 -r ';
	$ips = $_SERVER['SERVER_ADDR'];
	echo "$ips".'</pre>';?>


	<form action="c-rigctld.php" method="POST" class="next">
	<input type="submit" name="restart" value="Restart daemon">
	<? if (isset($_POST['restart'])) {
		exec('sudo /bin/systemctl restart rig.service');
	}
	echo '</form>';

	echo '<p class="text2">Process:</p>';
	exec('ps aux | grep [r]igctld > /tmp/ps-rig');
	echo '<pre>';
	include '/tmp/ps-rig';
	echo '</pre>';
}
?>

<p class="next"><a href="c-fsk.php"><img src="previous.png" alt="previous page"></a><a href="c-band-decoder.php"><img src="next.png" alt="next page"></a></p>
<!-- #################### /Obsah #################### -->
</div>
<div id="header"><?php include 'header.html';?></div>
</div><div id="leftmenu"><?php include 'menu.html';?></div>
</body>
</html>

