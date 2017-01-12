<!doctype html>
<html>
<head>
<?
$log = $_GET['log'];
$call = $_GET['call'];
?>
<title>ॐ - <? echo $log.'-'.$call ?></title>

<!---------------------------------------------------

  Óm - very simple contest log

  see http://remoteqth.com/wiki/index.php?page=PHP+contest+Log

-------------------------------------------- setup below -->

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<style type="text/css">
	a.switch:link  {
		color : #ccc;
		background-color: #888;
		font-weight : bold;
		text-decoration : none;
		}
	a.switch:visited  {
		color : #ccc;
		background-color: #888;
		font-weight : bold;
		text-decoration : none;
		}
	a.switch:hover  {
		color : #fff;
		background-color: #080;
		font-weight : bold;
		text-decoration : none;
		}
	a.switch:after{
		content: "\00a0";
	}
	a.switch:before{
		content: "\00a0";
	}
	a:hover span {
		display: none;
	}
	a:hover span.onhover {
		display: inline;
	}
	a span {
		display: inline;
	}
	a span.onhover {
		display: none;
	}
	.check  {
		color : #800;
	}
	.gray  {
		color : #888;
		font-weight:bold;
	}
:-webkit-full-screen {
  background: #ccc;
}
:-moz-full-screen {
  background: #ccc;
}
:-ms-fullscreen {
  background: #ccc;
}
:full-screen { /*pre-spec */
  background: #ccc;
}
:fullscreen { /* spec */
  background: #ccc;
}
	</style>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<script>

	// Find the right method, call on correct element
	function launchFullscreen(element) {
	  if(element.requestFullscreen) {
	    element.requestFullscreen();
	  } else if(element.mozRequestFullScreen) {
	    element.mozRequestFullScreen();
	  } else if(element.webkitRequestFullscreen) {
	    element.webkitRequestFullscreen();
	  } else if(element.msRequestFullscreen) {
	    element.msRequestFullscreen();
	  }
	}
	function exitFullscreen() {
	  if(document.exitFullscreen) {
	    document.exitFullscreen();
	  } else if(document.mozCancelFullScreen) {
	    document.mozCancelFullScreen();
	  } else if(document.webkitExitFullscreen) {
	    document.webkitExitFullscreen();
	  }
	}
	function dumpFullscreen() {
	  console.log("document.fullscreenElement is: ", document.fullscreenElement || document.mozFullScreenElement || document.webkitFullscreenElement || document.msFullscreenElement);
	  console.log("document.fullscreenEnabled is: ", document.fullscreenEnabled || document.mozFullScreenEnabled || document.webkitFullscreenEnabled || document.msFullscreenEnabled);
	}
	// Events
	document.addEventListener("fullscreenchange", function(e) {
	  console.log("fullscreenchange event! ", e);
	});
	document.addEventListener("mozfullscreenchange", function(e) {
	  console.log("mozfullscreenchange event! ", e);
	});
	document.addEventListener("webkitfullscreenchange", function(e) {
	  console.log("webkitfullscreenchange event! ", e);
	});
	document.addEventListener("msfullscreenchange", function(e) {
	  console.log("msfullscreenchange event! ", e);
	});
	
	// Add different events for fullscreen
</script>
</head>
<body bgcolor="#ccc">
<?
require 'function.php';
// txIP socket
function freq($ip) {
	$hz = exec("rigctl -m 2 -r $ip f");
	$mhz = $hz/1000000;
	return round($mhz, 3);
}
function mode($ip) {
	$mode = exec("rigctl -m 2 -r $ip m | head -n1");
	return $mode;
}
function rst($mode) {
	if ($mode == 'CW'){
		$rst='599';
	}elseif ($mode == 'LSB' || $mode == 'USB'){
		$rst='59';
	}
	return $rst;
}
function setrit($ip, $hz) {
	$hz = exec("rigctl -m 2 -r $ip J $hz");
}
function qsonr($log) {
	if (file_exists($log)) {
		$qsonrs = 0 ;
		$handle = fopen($log, "r");  // number lines in log = qso nr
		while(!feof($handle)){
			$line = fgets($handle);
			$qsonrs++;
		}
		fclose($handle);
		return $qsonrs;
	} else {                              // if log dont exist, create
		file_put_contents($log, '');
		$valuex = file($log);
	}
}
$conteststyle = $_GET['s'];
if (empty($conteststyle)){
	$conteststyle='run';
}
$path = '../cfg/';
$logpath = 'log/'.$log ;
$cwcliport = rxfile('s-cw-cwcli');
$exch = $_GET['exch'];
$IP = '127.0.0.1' ;                                     // CW IP 
$rigip = '127.0.0.1' ;                                  // hamlib TRX IP (rigctld)

$mode= mode($rigip);
if ($mode == 'CW' || $mode == 'CWR' || $mode == 'LSB' || $mode == 'USB') {
	$qsonrs = qsonr("$logpath.txt");
	date_default_timezone_set('UTC');
	$date = date('Y-m-d H:i ', time());
	$dateadif = date('Ymd', time());
	$timeadif = date('Hi', time());
	$show = '';
	?><form action="<?echo basename($_SERVER['PHP_SELF']).'?s='.$conteststyle.'&log='.$log.'&call='.$call.'&exch='.$exch ;?>" method="POST"><?     //form self url
	$callr = trim(strtoupper($_POST['callr']));     // trim whitespace and change to UPERCASE
	$qsonrr = trim(strtoupper($_POST['qsonrr']));

	/////////////////// CW MEMORY ////////////////////////////
	$CQ = $call.' '.$call.' TEST';
	if (strtoupper($exch) == 'NR'){
		$TXEXCH = $callr.' 5nn '.$qsonrs;    // $cwtwxt = call in input form, $qsonrs = QSO nr
		$TXEXCHSP = '5nn '.$qsonrs;
		$TXEXCHSP2 = '5nn '.($qsonrs-1);     // Exchange previous QSO
	}else{
		$TXEXCH = $callr.' 5nn '.$exch;
		$TXEXCHSP = '5nn '.$exch;
		$TXEXCHSP2 = '5nn '.$exch;
	}	
	$TU = 'tu '.$call ;
	/////////////////// CW MEMORY ////////////////////////////

	if (file_exists("$logpath.adif")) { } else {       // if adif dont exist, create
		file_put_contents("$logpath.adif", "Created by Óm PHP form ver. 0.1 - RemoteQTH.com\nPCall=$call\n<adif_ver:4>1.00 <eoh>\n");
		$valuex = file("$logpath.adif");
	}
	if ($_POST['send'] == 'Call?'){                 // detection button Call?
		txtcp($IP, $cwcliport, $callr.'?' );    // TX cw text
		$show = $callr.'?' ;                    // Show cw text
		$preset = $callr;                       // call insert back in form field
		$af1 = 'autofocus="autofocus"';         // cursor in first field (call)
		$af2 = '';                              //            second      (nr)
	}elseif ($_POST['send'] == 'nr?'){
		if ($conteststyle == 'run'){
			txtcp($IP, $cwcliport, 'NR' );
			$show = 'NR' ;
		}elseif ($conteststyle == 'sp'){
			txtcp($IP, $cwcliport, 'NR?' );
			$show = 'NR?' ;
		}
		$preset = $callr;
		$af1 = '';
		$af2 = 'autofocus="autofocus"';
	}elseif ($_POST['send'] == 'previous exchange'){
		txtcp($IP, $cwcliport, $TXEXCHSP2 );
		$show = $TXEXCHSP2 ;
		$preset = $callr;
		$af1 = 'autofocus="autofocus"';
		$af2 = '';
		$prevexch='<input type="submit" name="send" value="previous exchange"><input type="submit" name="send" value="Check">';
	}elseif ($_POST['send'] == 'Check' && isset($callr)){
		$search = preg_grep("/ $callr /", file("$logpath.txt"));
		$preset = $callr;
		$af1 = 'autofocus="autofocus"';
		$af2 = '';
	}elseif (isset($_POST['callr'])) {             // if press enter in field call
		if (empty($callr) && empty($qsonrr)){  // if call and nr field clear, run CQ
			if ($mode == 'CW'){            // CW only
				if ($conteststyle == 'run'){
					txtcp($IP, $cwcliport, $CQ );
					$mhz = freq($rigip);
					$show = $CQ.' <span class="gray">('.$mhz.' Mhz)</span>' ;
				}elseif ($conteststyle == 'sp'){
					txtcp($IP, $cwcliport, $call );
					$mhz = freq($rigip);
					$show = $call.' <span class="gray">('.$mhz.' Mhz)</span>' ;
				}
			}
			$preset = '';
			$af1 = 'autofocus="autofocus"';
			$af2 = '';
		}elseif (isset($callr) && empty($qsonrr)){ // if call writed and nr clear, run EXCH
			if ($mode == 'CW'){
				if ($conteststyle == 'run'){
					txtcp($IP, $cwcliport, $TXEXCH);
					$search = preg_grep("/ $callr /", file("$logpath.txt"));  // Check call in log
					$show = $TXEXCH ;
				}elseif ($conteststyle == 'sp'){
					txtcp($IP, $cwcliport, $call );
					$search = preg_grep("/ $callr /", file("$logpath.txt"));  // Check call in log
					$mhz = freq($rigip);
					$show = $call.' <span class="gray">('.$mhz.' Mhz)</span>' ;
				}
			}
			$preset = $callr;
			$af1 = '';
			$af2 = 'autofocus="autofocus"';
		}elseif (isset($callr) && isset($qsonrr)){  // if call and nr writed, run TU
			if ($mode == 'CW'){
				if ($conteststyle == 'run'){
					txtcp($IP, $cwcliport, $TU);
					$show = $TU;
				}elseif ($conteststyle == 'sp'){
					txtcp($IP, $cwcliport, $TXEXCHSP );
					$show = $TXEXCHSP ;
				}
			$prevexch='<input type="submit" name="send" value="previous exchange"><input type="submit" name="send" value="Check">';
			}
			$mhz = freq($rigip);
			file_put_contents("$logpath.txt", str_pad($qsonrs, 5, " ", STR_PAD_RIGHT).$date.str_pad($callr, 14, " ", STR_PAD_RIGHT).str_pad($mhz, 6, " ", STR_PAD_LEFT).str_pad($mode, 5, " ", STR_PAD_LEFT).str_pad($qsonrr, 5, " ", STR_PAD_LEFT).'   '.str_pad(rst($mode), 3, " ", STR_PAD_LEFT).' '.rst($mode)."\n", FILE_APPEND);  // add qso to txt log

			file_put_contents("$logpath.adif", '<FREQ:'.strlen($mhz).'>'.$mhz.' <QSO_DATE:'.strlen($dateadif).'>'.$dateadif.' <TIME_ON:'.strlen($timeadif).'>'.$timeadif.' <CALL:'.strlen($callr).'>'.$callr.' <MODE:'.strlen($mode).'>'.$mode.' <RST_SEND:'.strlen(rst($mode)).'>'.rst($mode).' <STX:'.strlen($qsonrs).'>'.$qsonrs.' <RST_RCVD:'.strlen(rst($mode)).'>'.rst($mode).' <SRX:'.strlen($qsonrr).'>'.$qsonrr.' <EOR>'."\n", FILE_APPEND);  // add qso to adif log

			setrit($rigip, '0');
			$preset = '';
			$af1 = 'autofocus="autofocus"';
			$af2 = '';
		}
	}else{                                         // cursor after open php form
		$preset = '';
		$af1 = 'autofocus="autofocus"';
		$af2 = '';
	}
	if ($conteststyle == 'run'){
		echo '<a href="'.basename($_SERVER['PHP_SELF']).'?s=sp&log='.$log.'&call='.$call.'&exch='.$exch.'" class="switch"><span>RUN</span><span class="onhover">S&P</span></a>';
	}
	if ($conteststyle == 'sp'){
		echo '<a href="'.basename($_SERVER['PHP_SELF']).'?s=run&log='.$log.'&call='.$call.'&exch='.$exch.'" class="switch"><span>S&P</span><span class="onhover">RUN</span></a>';
	}
	if ($mode == 'CW'){?>
		<span class="gray">WPM: </span><input type="submit" name="wpm15" value="15">
		<input type="submit" name="wpm20" value="20">
		<input type="submit" name="wpm25" value="25">
		<input type="submit" name="wpm28" value="28">
		<input type="submit" name="wpm30" value="30">
		<input type="submit" name="wpm32" value="32">
		<input type="submit" name="wpm35" value="35"> |  
		<!--<input type="submit" name="stop" value="STOP">-->
	<?}?>
		<input type="submit" name="tune" <?
			if ($mode == 'CW'){
				echo 'value="Tune">';
			}else{
				echo 'value="PTT">';
			}
		//  http://blog.radioartisan.com/arduino-cw-keyer/
		if (isset($_POST['tune'])) {txtcp($IP, $cwcliport, '\t'."\r");}
		else if (isset($_POST['stop'])) {txtcp($IP, $cwcliport, '\\'."\r");}
		else if (isset($_POST['wpm15'])) {txtcp($IP, $cwcliport, '\w15'."\r");}
		else if (isset($_POST['wpm20'])) {txtcp($IP, $cwcliport, '\w20'."\r");}
		else if (isset($_POST['wpm25'])) {txtcp($IP, $cwcliport, '\w25'."\r");}
		else if (isset($_POST['wpm28'])) {txtcp($IP, $cwcliport, '\w28'."\r");}
		else if (isset($_POST['wpm30'])) {txtcp($IP, $cwcliport, '\w30'."\r");}
		else if (isset($_POST['wpm32'])) {txtcp($IP, $cwcliport, '\w32'."\r");}
		else if (isset($_POST['wpm35'])) {txtcp($IP, $cwcliport, '\w35'."\r");}
	?>	</form><hr>
		<span class="gray"><? echo $mode;?> &#10095</span> <? echo $show ?><br><?
		?><form action="<?echo basename($_SERVER['PHP_SELF']).'?s='.$conteststyle.'&log='.$log.'&call='.$call.'&exch='.$exch ;?>" method="POST">
			<p class="text2">Call  
			<input type="text" <? echo $af1?> value="<? echo $preset?>" name="callr" id="Call" onblur="if (this.value == '') {this.value = '<? echo $preset?>';}" size="8" maxlength="30"/>
			Exch <input type="text" <? echo $af2?> name="qsonrr" size="4" maxlength="30">
			<input style="display: none" type="submit" name='send' value="Send">
	<?if ($mode == 'CW'){
		if ($conteststyle == 'run'){?>
			<input type="submit" name='send' value="Call?">
			<input type="submit" name='send' value="nr?">
		<?}?>
	<?	if ($conteststyle == 'sp'){
			if (empty($prevexch)){
				echo '<input type="submit" name="send" value="nr?">';
				echo '<input type="submit" name="send" value="Check">';
			}
			echo $prevexch;
		}?>
	<?}?>
	</form><pre class="check"><?
	if (isset($search)){
		foreach($search as $value){                            // print array value
		    echo $value;
		}
	}?></pre>
	<pre><?$file = file("$logpath.txt");                               // viewing log reverse
		$file = array_reverse($file);
		foreach($file as $f){
		    echo $f;
		}?></pre>
	Download: <a href="<?echo "$logpath.txt" ?>">.txt&#8599;</a> <a href="<?echo "$logpath.adif" ?>">.adif&#8599;</a>
<?
}else{
	echo 'Switch TRX to <b>CW/SSB</b> or check <a href="s-rigctld.php">TRX CAT</a>.';
}?>

	<button onclick="launchFullscreen(document.documentElement);">Fullscreen ON</button>
	<button onclick="exitFullscreen();">Fullscreen off</button>

</body>
</html>
