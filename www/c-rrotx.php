<!doctype html>
<html>
<body bgcolor="#000">
<?php
require 'function.php';
//require 'Nette/loader.php';
//use Nette\Forms\Form,
//	Nette\Diagnostics\Debugger,
//	Nette\Utils\Html;
//debugger::enable();
//$configurator = new Nette\Config\Configurator; 
//$configurator->setDebugMode(TRUE);
//$configurator->enableDebugger(__DIR__ . '/../log');

// nastaveni promennych
$rot = isset($_GET['rot']) ? $_GET['rot'] : 1;
$server = isset($_GET['server']) ? $_GET['server'] : 1;
$IP = isset($_GET['ip']) ? $_GET['ip'] : 1;
$port = isset($_GET['port']) ? $_GET['port'] : 1;
if (empty($_GET['port'])) {
	$port = $rot + 90 ;
}
$name = isset($_GET['name']) ? $_GET['name'] : 1;
$rotl = isset($_GET['rotl']) ? $_GET['rotl'] : 1;
$rotr = isset($_GET['rotr']) ? $_GET['rotr'] : 1;
$external = isset($_GET['external']) ? $_GET['external'] : 1;

echo "<canvas id=\"map\" width=\"319\" height=\"319\" style=\"border:0px solid #000000;background-image:url('img.php?doraz_l={$rotl}&doraz_p={$rotr}');\"></canvas><br>QTF {$name}: <span id=\"qtf\" style=\"color:#fff\">n/a</span>"; ?>

<form action="<?echo actualpage().'?rot='.$rot.'&ip='.$IP.'&name='.$name.'&rotl='.$rotl.'&rotr='.$rotr.'&external='.$external.'&server='.$server; ?>" method="POST" class="center">
	<input type="submit" name="ccw" value="&#8634; CCW"><input type="submit" name="stop" value="STOP"><input type="submit" name="cw" value="CW &#8635;">
	<?
	if (isset($_POST['ccw'])) {
		txtcp($IP, $port, "L\r");
	}
	else if (isset($_POST['stop'])) {
		txtcp($IP, $port, "A\r");
	}
	else if (isset($_POST['cw'])) {
		txtcp($IP, $port, "R\r");
	}?>
</form>
<!-- <a href="get.php?q=2&rot=<?echo $rot; ?>&ip=<?php echo $IP; ?>&port=<?php echo $port; ?>&external=<?php echo $external; ?>&server=<?php echo $server;?>">get</a> -->

</body>
<head>
	<title><? echo $name ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="styles.css" rel="stylesheet" type="text/css">
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<!--<script src="netteForms.js"></script>  -->
	<meta http-equiv="refresh" content="900">
	<meta charset="utf-8">

<script>
      function hideAddressBar()
      {
          if(!window.location.hash)
          { 
              if(document.height <= window.outerHeight + 10)
              {
                  document.body.style.height = (window.outerHeight + 50) +'px';
                  setTimeout( function(){ window.scrollTo(0, 1); }, 50 );
              }
              else
              {
                  setTimeout( function(){ window.scrollTo(0, 1); }, 0 ); 
              }
          }
      } 

      window.addEventListener("load", hideAddressBar );
      window.addEventListener("orientationchange", hideAddressBar ); 

// band
var xmlhttp, qtf = -1, target = -1, centerx, centery, r;
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
    loadXMLDoc("get.php?q=2&rot=<?php echo $rot; ?>&ip=<?php echo $IP; ?>&port=<?php echo $port; ?>&external=<?php echo $external; ?>&server=<?php echo $server; ?>", function()
    {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            qtf = parseInt(xmlhttp.responseText); 
            document.getElementById("qtf").innerHTML = qtf + '°';
            redraw();
            if (target >= 0 && target >= qtf - 1 && target <= qtf + 1) target = -1;
        }
    });
}

function redraw(){
    var canvas = document.getElementById("map");
    var ctx = canvas.getContext("2d");

    ctx.clearRect(0,0,canvas.width,canvas.height);

    if (target >= 0){
        ctx.beginPath();
        ctx.lineWidth = 2;
        ctx.strokeStyle = '#fff';
        ctx.moveTo(centerx, centery);
        var x = centerx + 0.88 * r * Math.sin(target * Math.PI / 180);
        var y = centery - 0.88 * r * Math.cos(target * Math.PI / 180);
        ctx.lineTo(x, y);
        ctx.stroke();
    }

    if (qtf >= 0){
        ctx.beginPath();
        ctx.lineWidth = 4;
        ctx.strokeStyle = '#09f';
        ctx.moveTo(centerx, centery);
        var x = centerx + 0.88 * r * Math.sin(qtf * Math.PI / 180);
        var y = centery - 0.88 * r * Math.cos(qtf * Math.PI / 180);
        ctx.lineTo(x, y);
        ctx.stroke();
    }
}

function doMouseDown(ev){


    var canvas = document.getElementById("map");
    var ctx = canvas.getContext("2d");
    var x, y;

    var rect = canvas.getBoundingClientRect();
    x = ev.clientX - rect.left;
    y = ev.clientY - rect.top;

    if (x != centerx || y != centery){
        target = Math.atan2(x - centerx, -(y - centery)) * 180 / Math.PI;
        if (target < 0) target += 360;
	levy = <?php echo $rotl; ?>;
	pravy = <?php echo $rotr; ?>;
	if (levy > pravy){
		if (target < levy && target > pravy && target > (levy - pravy) / 2 + pravy ) target = levy;
		if (target < levy && target > pravy && target < (levy - pravy) / 2 + pravy ) target = pravy;
	}else{
		if (target < levy || target > pravy){
			if (target < levy) target += 360;
			if (target <= (levy+360) && target > pravy && target > (360 - pravy + levy) / 2 + pravy ) target = levy;
			if (target <= (levy+360) && target > pravy && target < (360 - pravy + levy) / 2 + pravy ) target = pravy;
		}
	}
        if (confirm("Turn ant to " + parseInt(target) + "°?") != true) {
            target = -1;
            return;
        }
        redraw();
        loadXMLDoc("turn.php?rot=<?php echo $rot; ?>&ip=<?php echo $IP; ?>&port=<?php echo $port; ?>&external=<?php echo $external; ?>&server=<?php echo $server; ?>&turn=" + parseInt(target), function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
            }
        });
    }
}

function start(){
    setInterval('updateQrg()', 1000);
    var canvas = document.getElementById("map");
    var rect = canvas.getBoundingClientRect();
    centerx = rect.width / 2;
    centery = rect.height / 2;
    r = Math.min(centerx, centery);
    //alert(centerx + ' ' + centery + ' ' + r);
    canvas.addEventListener("mousedown", doMouseDown, false);
}

window.onload = start;

</script>
</head>
</html>
