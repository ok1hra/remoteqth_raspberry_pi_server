<!doctype html>
<html>
<head>
<?
$log = $_GET['log'];
$call = $_GET['call'];
?>
<title><? include '../cfg/s-login-note';?> | ॐ  | <? echo $log.'-'.$call ?></title>

<!---------------------------------------------------

  Óm - very simple contest log

  see http://remoteqth.com/wiki/index.php?page=PHP+contest+Log

	Changelog
	2017-11 - show no QSO if check blank in SP mode
		- add reverse CW (CWR)
	2016-03 - fix show previous qso in SSB mode
	2016-01 - add FSK mode
	2015-11 - after press 'nr?' exchange do not clear
		- change wpm and tune don't clear input value
		- move '*?' button after call input field for use with Tab key
		- '*?' also check partial calls (grep)
		- redesigned
	2015-10 - disable autofill input forms
	2015-08 - new frequency cache - if hmlib short fail 


-------------------------------------------- setup below -->

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<style type="text/css">
	body {
		font-family: 'Roboto Condensed',sans-serif,Arial,Tahoma,Verdana;
		background: #ccc;
	}
	#obsah0 {
		position: absolute;
		top: 0px;
		left: 0px;
		bottom: 0px;
		right: 0px;
		height: 40px;
		width: expression(document.body.clientWidth - 150);
		background: #444;
		overflow-y: hidden;
		padding: 10px 0 0 10px;
	}
	#obsah1 {
		position: absolute;
		top: 40px;
		left: 0px;
		bottom: 0px;
		right: 0px;
		height: 100px;
		width: expression(document.body.clientWidth - 150);
		background: #ccc;
		overflow-y: hidden;
		padding: 10px 0 0 10px;
	}
	#obsah2 {
		position: absolute;
		top: 140px;
		left: 0px;
		bottom: 0px;
		right: 0px;
		height: expression(document.body.clientHeight - 120);
		width: expression(document.body.clientWidth - 150);
		background: #444;
		color: #ccc;
		overflow: auto;
		padding: 0 0 0 10px;
	}
	a.switch:link  {
		color : #ccc;
		background-color: #888;
		font-weight : bold;
		text-decoration : none;
		border-radius: 5px;
		-webkit-border-radius: 5px;
		-moz-border-radius: 5px;
		}
	a.switch:visited  {
		color : #ccc;
		background-color: #888;
		font-weight : bold;
		text-decoration : none;
		border-radius: 5px;
		-webkit-border-radius: 5px;
		-moz-border-radius: 5px;
		}
	a.switch:hover  {
		color : #fff;
		background-color: #080;
		font-weight : bold;
		text-decoration : none;
		border-radius: 5px;
		-webkit-border-radius: 5px;
		-moz-border-radius: 5px;
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
	.checkg  {
		color : #080;
	}
	.gray  {
		color : #888;
		font-weight:bold;
	}
	.red  {
		color : #d00;
		font-weight:bold;
	}
	input.wpm {
	    border: 0px solid #333;
	    background: #ccc;
	    margin: 0 5px 0 0;
	    -webkit-border-radius: 5px;
	    -moz-border-radius: 5px;
	    border-radius: 5px;
	    color : #333;
	}
	input.wpm:hover {
	    border: 0px solid #080;
	    background: #080;
	    color : #fff;
	}

	input[type=text] {
	    border: 2px solid #333;
	    padding: 5px 8px 4px 8px;
	    background: #333;
	    margin: 0 0 0px 5px;
	    -webkit-border-radius: 7px 0 0 7px;
	    -moz-border-radius: 7px 0 0 7px;
	    border-radius: 7px 0 0 7px;
	    font-size: 125%;
		font-weight: bold;
		letter-spacing: 1px;
	    color : #ccc;
	}
	input.qso {
	    border-top: 2px solid #333;
	    border-bottom: 2px solid #333;
	    border-right: 2px solid #333;
	    border-left: 0px solid #333;
	    padding: 5px 10px 5px 10px;
	    background: #ccc;
	    margin: 0 5px 0 -2px;
	    -webkit-border-radius: 0 7px 7px 0;
	    -moz-border-radius: 0 7px 7px 0;
	    border-radius: 0 7px 7px 0;
	    font-size: 125%;
	        font-weight: 100;
		letter-spacing: 1px;
	    color : #333;
	}
	input[type=text]:focus {
	    background: #080;
	    border: 2px solid #080;
	}
	input.qso:focus {
	    border-top: 4px solid #080;
	    border-bottom: 4px solid #080;
	    border-right: 4px solid #080;
	    border-left: 0px solid #080;
	    padding: 3px 8px 3px 8px;
	    color : #080;
	}
	</style>
	<link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:300italic,400italic,700italic,400,700,300&subset=latin-ext' rel='stylesheet' type='text/css'>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<meta name="mobile-web-app-capable" content="yes">
</head>
<body>
<div id="obsah0">
<?
require 'function.php';
// txIP socket
function mode($ip) {
	global $style;
	$mode = exec("rigctl -m 2 -r $ip m | head -n1");
	if ($mode == 'CW' || $mode == 'CWR' || $mode == 'LSB' || $mode == 'USB' || $mode == 'RTTY') { // mode OK
		$style = 'gray';
		txfile('/tmp/mode', $mode);  // save last mode
	}else{ // rig OFF
		$style = 'red';
		$mode = rxfile('/tmp/mode'); // use saved mode
	}
	return $mode;
}
function freq($ip) {
	$hz = exec("rigctl -m 2 -r $ip f");
	if ($hz == 0) { // rig OFF
		$mhz = rxfile('/tmp/freq');  // use saved freq
	}else{
		$mhz = $hz/1000000;
		txfile('/tmp/freq', $mhz); // save last freq
	}
	return round($mhz, 3);
}
function rst($mode) {
	if ($mode == 'CW' || $mode == 'CWR' || $mode == 'RTTY'){
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
function port() {
	global $cwcliport, $fskport, $mode;
	if ($mode == 'RTTY'){
		return $fskport;
	} else {
		return $cwcliport;
	}
}
$conteststyle = $_GET['s'];
if (empty($conteststyle)){
	$conteststyle='run';
}
$path = ''; // ../cfg/
$logpath = 'log/'.$log ;
$cwcliport = '7890'; //rxfile('../cfg/s-cw-cwcli');
$fskport = '7891'; //rxfile('../cfg/s-cw-cwcli');
$exch = $_GET['exch'];
$IP = '127.0.0.1' ;                                     // CW IP 
$rigip = '127.0.0.1' ;                                  // hamlib TRX IP (rigctld)

$mode= mode($rigip);
if ($mode == 'CW' || $mode == 'CWR' || $mode == 'LSB' || $mode == 'USB' || $mode == 'RTTY') {
	$qsonrs = qsonr("$logpath.txt");
	date_default_timezone_set('UTC');
	$date = date('Y-m-d H:i ', time());
	$dateadif = date('Ymd', time());
	$timeadif = date('Hi', time());
	$show = '';
	?><form action="<?echo basename($_SERVER['PHP_SELF']).'?s='.$conteststyle.'&log='.$log.'&call='.$call.'&exch='.$exch ;?>" method="POST"><?     //form self url
	$callr = trim(strtoupper($_POST['callr']));     // trim whitespace and change to UPERCASE
	$qsonrr = trim(strtoupper($_POST['qsonrr']));

	/////////////////// RTTY MEMORY ////////////////////////////
	if ($mode == 'RTTY'){
		$CQ = ' CQ CQ '.$call.' '.$call.' '.$call.' TEST ';
		if (strtoupper($exch) == 'NR'){
			$TXEXCH = ' '.$callr.' '.$callr.' 599-'.$qsonrs.' 599-'.$qsonrs.' ';    // $cwtwxt = call in input form, $qsonrs = QSO nr
			$TXEXCHSP = ' '.$callr.' '.$callr.' 599-'.$qsonrs.' 599-'.$qsonrs.' ';
			$TXEXCHSP2 = ' '.$callr.' '.$callr.' 599-'.($qsonrs-1).' 599-'.($qsonrs-1).' ';     // Exchange previous QSO
		}else{
			$TXEXCH = ' '.$callr.' '.$callr.' 599-'.$exch.' 599-'.$exch.' ';
			$TXEXCHSP = ' '.$callr.' '.$callr.' 599-'.$exch.' 599-'.$exch.' ';
			$TXEXCHSP2 = ' '.$callr.' '.$callr.' 599-'.$exch.' 599-'.$exch.' ';
		}
		$TU = ' '.$callr.' tu '.$call.' ';
	/////////////////// CW MEMORY ////////////////////////////
	}else{
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

	}
	///////////////////////////////////////////////////////

	if (file_exists("$logpath.adif")) { } else {       // if adif dont exist, create
		file_put_contents("$logpath.adif", "Created by Óm PHP form ver. 0.1 - RemoteQTH.com\nPCall=$call\n<adif_ver:4>1.00 <eoh>\n");
		$valuex = file("$logpath.adif");
	}
	if ($_POST['send'] == '*?'){                 // detection button Call?
		udpsocket($IP, port(), $callr.'?' );    // TX cw text
		$show = $callr.'?' ;                    // Show cw text
		$preset = $callr;                       // call insert back in form field
		$search = preg_grep("/$callr/", file("$logpath.txt"));
	//	$preset2 = $qsonrr;                     // exch insert back in form field
		$af1 = 'autofocus="autofocus"';         // cursor in first field (call)
		$af2 = '';                              //            second      (nr)
	}elseif ($_POST['send'] == 'nr?'){
		if ($conteststyle == 'run'){
			udpsocket($IP, port(), 'NR' );
			$show = 'NR' ;
		}elseif ($conteststyle == 'sp'){
			udpsocket($IP, port(), 'NR?' );
			$show = 'NR?' ;
		}
		$preset = $callr;
		$preset2 = $qsonrr;
		$af1 = '';
		$af2 = 'autofocus="autofocus"';
	}elseif ($_POST['send'] == 'previous exchange'){
		udpsocket($IP, port(), $TXEXCHSP2 );
		$show = $TXEXCHSP2 ;
		$preset = $callr;
		$preset2 = $qsonrr;
		$af1 = 'autofocus="autofocus"';
		$af2 = '';
		$prevexch='<input type="submit" name="send" value="previous exchange" class="qso"><input type="submit" name="send" value="Check" class="qso">';
	}elseif ($_POST['send'] == 'Check' && isset($callr)){
		$search = preg_grep("/ $callr /", file("$logpath.txt"));
		$preset = $callr;
		$preset2 = $qsonrr;
		$af1 = 'autofocus="autofocus"';
		$af2 = '';
	}elseif (isset($_POST['callr']) && !isset($_POST['wpm15']) && !isset($_POST['wpm20']) && !isset($_POST['wpm25']) && !isset($_POST['wpm28']) && !isset($_POST['wpm30']) && !isset($_POST['wpm32']) && !isset($_POST['wpm35']) && !isset($_POST['tune'])) {             // if press enter in field call
		if (empty($callr) && empty($qsonrr)){  // if call and nr field clear, run CQ
			if ($mode == 'CW' || $mode == 'CWR' || $mode == 'RTTY'){            // CW only
				if ($conteststyle == 'run'){
					udpsocket($IP, port(), $CQ );
					$mhz = freq($rigip);
					$show = $CQ.' <span class="'.$style.'">('.$mhz.' Mhz)</span>' ;
				}elseif ($conteststyle == 'sp'){
					udpsocket($IP, port(), $call );
					$mhz = freq($rigip);
					$show = $call.' <span class="'.$style.'">('.$mhz.' Mhz)</span>' ;
				}
			}
			$preset = '';
			$af1 = 'autofocus="autofocus"';
			$af2 = '';
		}elseif (isset($callr) && empty($qsonrr)){ // if call writed and nr clear, run EXCH
			if ($mode == 'CW' || $mode == 'CWR' || $mode == 'RTTY'){
				if ($conteststyle == 'run'){
					udpsocket($IP, port(), $TXEXCH);
				//	$search = preg_grep("/ $callr /", file("$logpath.txt"));  // Check call in log
						txfile('/tmp/callr', $callr);  // save call for test, if change 
					$show = $TXEXCH ;
				}elseif ($conteststyle == 'sp'){
					udpsocket($IP, port(), $call );
				//	$search = preg_grep("/ $callr /", file("$logpath.txt"));  // Check call in log
					$mhz = freq($rigip);
					$show = $call.' <span class="'.$style.'">('.$mhz.' Mhz)</span>' ;
				}
			}
			$search = preg_grep("/ $callr /", file("$logpath.txt"));  // Check call in log
			$preset = $callr;
			$af1 = '';
			$af2 = 'autofocus="autofocus"';
		}elseif (isset($callr) && isset($qsonrr)){  // if call and nr writed, run TU
			if ($mode == 'CW' || $mode == 'CWR' || $mode == 'RTTY'){
				if ($conteststyle == 'run'){
					$callrtest = rxfile('/tmp/callr');
					if ($callrtest == $callr){                            // if call not changed
						udpsocket($IP, port(), $TU);
						$show = $TU;
					}else{                                                // if call changed
						udpsocket($IP, port(), $callr.' '.$TU);   // send changed $callr before TU
						$show = $callr.' '.$TU;
					}
				}elseif ($conteststyle == 'sp'){
					udpsocket($IP, port(), $TXEXCHSP );
					$show = $TXEXCHSP ;
				}
			$prevexch='<input type="submit" name="send" value="previous exchange" class="qso"><input type="submit" name="send" value="Check" class="qso">';
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
	}?>
	<input style="display: none" type="submit" name='send' value="Send">	<!-- hidden button - use if press enter (without click any other button)-->  <?
	if ($mode == 'CW' || $mode == 'CWR'){?>
		<span class="gray">WPM: </span>
		<!-- <input type="submit" name="wpm15" value="15">
		<input type="submit" name="wpm20" value="20"> -->
		<input type="submit" name="wpm25" value="25" class="wpm">
		<input type="submit" name="wpm28" value="28" class="wpm">
		<input type="submit" name="wpm30" value="30" class="wpm">
		<input type="submit" name="wpm32" value="32" class="wpm">
		<input type="submit" name="wpm35" value="35" class="wpm">
		<!--<input type="submit" name="stop" value="STOP" class="wpm">-->
	<?}?>
		<input type="submit" name="tune" class="wpm" <?
			if ($mode == 'CW' || $mode == 'CWR'){
				echo 'value="Tune">';
			}else{
				echo 'value="PTT">';
			}
		//  http://blog.radioartisan.com/arduino-cw-keyer/
		if (isset($_POST['tune'])) {udpsocket($IP, port(), '\t'."\r"); $preset = $callr; $preset2 = $qsonrr;}
		else if (isset($_POST['stop'])) {udpsocket($IP, port(), '\\'."\r"); $preset = $callr; $preset2 = $qsonrr;}
		else if (isset($_POST['wpm15'])) {udpsocket($IP, port(), '\w15'."\r"); $preset = $callr; $preset2 = $qsonrr;}
		else if (isset($_POST['wpm20'])) {udpsocket($IP, port(), '\w20'."\r"); $preset = $callr; $preset2 = $qsonrr;}
		else if (isset($_POST['wpm25'])) {udpsocket($IP, port(), '\w25'."\r"); $preset = $callr; $preset2 = $qsonrr;}
		else if (isset($_POST['wpm28'])) {udpsocket($IP, port(), '\w28'."\r"); $preset = $callr; $preset2 = $qsonrr;}
		else if (isset($_POST['wpm30'])) {udpsocket($IP, port(), '\w30'."\r"); $preset = $callr; $preset2 = $qsonrr;}
		else if (isset($_POST['wpm32'])) {udpsocket($IP, port(), '\w32'."\r"); $preset = $callr; $preset2 = $qsonrr;}
		else if (isset($_POST['wpm35'])) {udpsocket($IP, port(), '\w35'."\r"); $preset = $callr; $preset2 = $qsonrr;}?>
</div><div id="obsah1">

		<span class="<? echo $style;?>"><? echo $mode;?> &#10095</span> <? echo $show ?><br>
			<p class="text2">Call  
			<input type="text" <? echo $af1?> value="<? echo $preset?>" name="callr" id="Call" onblur="if (this.value == '') {this.value = '<? echo $preset?>';}" size="8" maxlength="30" autocomplete="off"/><?

		if (($mode == 'CW' || $mode == 'CWR' || $mode == 'RTTY')&& $conteststyle == 'run'){
			echo '<input type="submit" name="send" value="*?" class="qso">';
		}
			echo " Exch<input type=\"text\"$af2 value=\"$preset2\" name=\"qsonrr\" size=\"1\" maxlength=\"30\" autocomplete=\"off\">";

		if ($mode == 'CW' || $mode == 'CWR' || $mode == 'RTTY'){
		if ($conteststyle == 'run'){
			echo '<input type="submit" name="send" value="nr?" class="qso">';
		}
		if ($conteststyle == 'sp'){
			if (empty($prevexch)){
				echo '<input type="submit" name="send" value="nr?" class="qso">';
				echo '<input type="submit" name="send" value="Check" class="qso">';
			}
			echo $prevexch;
		}
	}?>
	</form>
	</div><div id="obsah2">
	<pre class="<?
	if (isset($search)){
		if (count($search) ==0){
			echo 'checkg">';
			echo "$callr no QSO";
		}else{
			echo 'check">';
			foreach($search as $value){                            // print array value
			    echo $value;
			}
		}
	}else{
		echo '">' ;
	}?></pre>
	<pre><?$file = file("$logpath.txt");                               // viewing log reverse
		$file = array_reverse($file);
		foreach($file as $f){
		    echo $f;
		}?></pre>
	Download: <a href="<?echo "$logpath.txt" ?>">.txt&#8599;</a> <a href="<?echo "$logpath.adif" ?>">.adif&#8599;</a> | <a href="http://remoteqth.com/wiki/index.php?page=Simple+WEB+contest+LOG">ॐ-wiki</a>
<?
}else{
	echo 'Switch TRX to <b>CW/SSB/RTTY</b> or check <a href="s-rigctld.php">TRX CAT</a>.';
}?>
</div>
</body>
</html>
