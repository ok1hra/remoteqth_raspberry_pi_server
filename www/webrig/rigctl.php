<?

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Expires: -1");

$q = Preg_Replace('/[^- a-z0-9]/i', '', $_GET['q']);
system("rigctl -m 2 -- $q");


?>
