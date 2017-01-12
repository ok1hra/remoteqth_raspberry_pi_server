<!doctype html>
<html>
<head>
	<title><? include '../cfg/s-login-note';?> | Webcam</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="styles.css" rel="stylesheet" type="text/css">
	<meta http-equiv="Refresh" content="60">
</head>
<body>

<?
if (file_exists('cam_hires.jpg')) {
    echo '<img src="cam_hires.jpg" width="100%"><br>';
} else {
    echo '<p><i>Picture yet not been generated</i></p><br>';
}
?>
<p class="text2"><a href="c-webcam.php" title="Webcam">&larr; back</a></p>

</body>
</html>

