<form action="<?echo actualpage();?>" method="POST">
<table border="0" cellspacing="0" cellpadding="0" style="margin-left: 0px; margin-top: 0px;">
<?
// definice barev
$ON = '#66cc00';
$OFF = '#666666';
$lines = "0" ;
$path = getcwd();
// cyklus pro 8 sensoru
for($rel=1; $rel<9; $rel++){
	//for($rel = array(4, 17, 21, 22, 23, 24, 25); $rel<9; $rel++){
	//$valuex = file("../cfg/s-relay-$rel"); $value = $valuex[0];
	$path = "../cfg/";
	$value = rxfile("s-relay-$rel");
	if ( $value != "n/a" ) { $lines++ ?>
	<tr>
        <td class="td2"><?php echo $value ; ?></td>
        <td class="td1" width="130px">
		<input type="radio" name="<? echo "puntik$rel"; ?>" value="<? echo "OFF$rel"; ?>" onclick="this.form.submit();">OFF&nbsp;&nbsp;
		<input type="radio" name="<? echo "puntik$rel"; ?>" value="<? echo "ON$rel"; ?>" onclick="this.form.submit();">ON
		<? $zapnout = empty($_POST["puntik$rel"]) ? "" : $_POST["puntik$rel"];
		$path = "/tmp/";
		$lptnr = $rel-1; 
		if ($zapnout=="OFF$rel") {
			exec("sudo /usr/local/bin/ledblink -l parport$lptnr -0");
			txfile("lpt$rel", 'OFF');
		}
		if ($zapnout=="ON$rel") {
			exec("sudo /usr/local/bin/ledblink -l parport$lptnr -1");
			txfile("lpt$rel", 'ON');
	} ?>
	</td><td class="td2">
		<? //include "../cfg/gpio{$rel}";
		//$statusx = file("../cfg/gpio$rel"); $status= $statusx[0];
		$status = rxfile("lpt{$rel}");
		if ($status == "ON") {
			$color = $ON ; }
		else {
			$color = $OFF ; }
		echo "<span style=\"color: $color\">$status</span>";
		?>
	</td></tr>
	<? }
// konec cyklu
} ?>



</table>
</form>
