<!doctype html>
<html>
<head>
	<title><? include '../cfg/s-login-note';?> | Network</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="styles.css" rel="stylesheet" type="text/css">
	<script src="netteForms.js"></script>
</head>
<body scroll="no">
<div id="obsah">
<!-- #################### Obsah #################### -->
<span style="position: absolute; top: 20px; left: 360px; z-index: auto"><img src="s-net.png"></span>
<p class="text2">More about settings in <a href="http://remoteqth.com/wiki/index.php?page=Network" target="_blank" class="external">Wiki</a></p>

<?
require 'function.php';
require 'Nette/loader.php';
use Nette\Forms\Form,
//	Nette\Diagnostics\Debugger,
	Nette\Utils\Html;
////debugger::enable();
//$configurator = new Nette\Config\Configurator; 
//$configurator->setDebugMode(TRUE);
//$configurator->enableDebugger(__DIR__ . '/../log');

$warn = '';
$dhcpx = file('../cfg/s-net-dhcp'); $dhcp= $dhcpx[0];
$ipx = file('../cfg/s-net-ip'); $ip= $ipx[0];
$maskx = file('../cfg/s-net-mask'); $mask= $maskx[0];
$gatex = file('../cfg/s-net-gate'); $gate= $gatex[0];
$dnsx = file('../cfg/s-net-dns'); $dns= $dnsx[0];
$qrx = file('../cfg/s-net-qr'); $qr= $qrx[0];
$debversion = rxfile2('/etc/debian_version');
	if ($debversion <= 8 ){	// Jessie ?>
		<h1>By default is enabled DHCP</h1>

		<p><b>How to setting <a href="https://www.google.com/search?q=raspberry+static+ip+jessie">Ethernet</a></b></p>

		<p><b>How to setting WIFI</b></p>
		<ul>
			<li><a href="https://www.raspberrypi.org/documentation/configuration/wireless/README.md">Via the command line</a></li>
			<li><a href="http://www.raspyfi.com/wi-fi-on-raspberry-pi-a-simple-guide/">Using wicd-curses</a></li>
		</ul>

	<?}


$form = new Form;
$form->setMethod('POST');
if ($debversion < 8 ){	// Wheezy
	$form->addGroup('Network (Ethernet)')
		->setOption('embedNext', TRUE);
		$dhcpx = array(
			'1' => 'Enable',
			'0' => 'Disable',
			);
			$form->addSelect('dhcp', 'DHCP:', $dhcpx)
			//$form->addCheckbox('dhcp', 'DHCP')
			->addCondition($form::EQUAL, FALSE) // conditional rule: if is checkbox checked...
			->toggle('sendBox'); // toggle div #sendBox
	$form->addGroup()
		->setOption('container', Html::el('div')->id('sendBox'));
		$form->addText('ip', 'IP:', 15)
			->setRequired('Choose your IP adress')
			->addRule(Form::MIN_LENGTH, 'IP address must have at least %d characters', 7)
			->addRule(Form::MAX_LENGTH, 'IP address can have maximum %d characters', 15)
			->addRule(Form::PATTERN, 'IP address must be in range 0.0.0.0 - 255.255.255.255', '\b(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\b');
		$form->addText('mask', 'Mask:', 15)
			->setRequired('Choose your Netmask')
			->addRule(Form::MIN_LENGTH, 'Netmask address must have at least %d characters', 7)
			->addRule(Form::MAX_LENGTH, 'Netmask address can have maximum %d characters', 15)
			->addRule(Form::PATTERN, 'Netmask address must be in range 0.0.0.0 - 255.255.255.255', '\b(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\b');
		$form->addText('gate', 'Gateway:', 15)
			->setRequired('Choose your Gateway')
			->addRule(Form::MIN_LENGTH, 'Gateway address must have at least %d characters', 7)
			->addRule(Form::MAX_LENGTH, 'Gateway address can have maximum %d characters', 15)
			->addRule(Form::PATTERN, 'Gateway address must be in range 0.0.0.0 - 255.255.255.255', '\b(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\b');
		$form->addText('dns', 'DNS:', 15)
			->setRequired('Choose your DNS')
			->addRule(Form::MIN_LENGTH, 'DNS must have at least %d characters', 7)
			->addRule(Form::MAX_LENGTH, 'DNS can have maximum %d characters', 15)
			->addRule(Form::PATTERN, 'DNS must be in range 0.0.0.0 - 255.255.255.255', '\b(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\b');
}

$label = Html::el()->setHtml('<p>html://<b>You PUBLIC IP or URL:port</b>/mob.php</p>');
$form->addGroup('Generate QRcode')
	->setOption('description', $label);
	$form->addText('qr', 'IP/URL:port:')
		->setEmptyValue('publicIP:port')
		->addRule(Form::MAX_LENGTH, 'QRcode URL can have maximum %d characters', 25);
$form->addSubmit('submit', 'Apply');

$form->setDefaults(array(
	'dhcp' => $dhcp, 'ip' => $ip, 'mask' => $mask, 'gate' => $gate, 'dns' => $dns, 'qr' => $qr, 
	));
if ($form->isSuccess()) {
	$warn = "Settings to take effect after server Restart.<br> <a href=\"stop.php\">&rarr; Restart now</a>";
	$values = $form->getValues();
	$fp = fopen('../cfg/s-net-dhcp', 'w'); fwrite($fp, $values->dhcp); fclose($fp);
	$fp = fopen('../cfg/s-net-ip', 'w'); fwrite($fp, $values->ip); fclose($fp);
	$fp = fopen('../cfg/s-net-mask', 'w'); fwrite($fp, $values->mask); fclose($fp);
	$fp = fopen('../cfg/s-net-gate', 'w'); fwrite($fp, $values->gate); fclose($fp);
	$fp = fopen('../cfg/s-net-dns', 'w'); fwrite($fp, $values->dns); fclose($fp);
	$fp = fopen('../cfg/s-net-qr', 'w'); fwrite($fp, $values->qr); fclose($fp);
	$path = getcwd();
	if ($debversion < 8 ){	// Wheezy
		exec("$path/../script/net.sh > /etc/network/interfaces");
	}
	$qrcode = 'http://'.$values->qr.'/mob.php';
	gethw();
	if ( $hw == "PI" ) {
		$ttyqr = 'ttyqr-armv6l' ;
	}
	else {
		$ttyqr = 'ttyqr-i586' ;
	}
	exec("$path/../script/$ttyqr -b -l M $qrcode | sed 's/\[30;47;27m//g;s/\[0m//g' > $path/../cfg/s-net-qrcode");
	$path= '/etc/';
	if ( $values->dhcp == "0") {
		txfile('resolv.conf', "nameserver $dns");
	}
	else {
		txfile('resolv.conf', 'nameserver 8.8.8.8'."\n".'nameserver 8.8.4.4');
	}
}

echo $form; ?>

<p class="warn"><?php echo $warn ?></p>

<p class="next"><a href="s-login.php"><img src="previous.png" alt="previous page"></a><a href="s-vpn.php"><img src="next.png" alt="next page"></a></p>

<!-- #################### /Obsah #################### -->
</div>
<div id="header"><?php include 'header.html';?></div>
</div><div id="leftmenu"><?php include 'menu.html';?></div>
</body>
</html>

