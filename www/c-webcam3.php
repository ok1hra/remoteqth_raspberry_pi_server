<!doctype html>
<html>
<head>
	<title><? include '../cfg/s-login-note';?> | Webcam</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="styles.css" rel="stylesheet" type="text/css">
	<script src="jquery-1.6.js" type="text/javascript"></script>
	<script src="jquery.jqzoom-core.js" type="text/javascript"></script>
	<meta http-equiv="Pragma" CONTENT="no-cache">
	<meta http-equiv="Cache-Control" content="no-cache">
	<meta http-equiv="Refresh" content="60">
<script type="text/javascript">
$(document).ready(function() {
	$('.jqzoom').jqzoom({
            zoomType: 'innerzoom',
            preloadImages: false,
            alwaysOn:false
        });
});
</script>
</head>
<body>


<?
if (file_exists('cam_hires.jpg')) { ?>
	<div id="container">
	<div class="clearfix" id="content" style="margin:0 auto 0 auto;">
		<div class="clearfix">
			<a href="cam_hires.jpg" class="jqzoom" rel='gal1'  title="Webcam">
			<img src="cam.jpg" title="triumph"  style="border: 1px solid #ccc;"></a>
		</div>
	</div>
	</div>
<?} else {
    echo '<p><i>Picture yet not been generated</i></p><br>';
}
?>





</body>
</html>

