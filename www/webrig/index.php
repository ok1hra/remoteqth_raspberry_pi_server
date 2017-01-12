<!DOCTYPE html>
<html>
<head>
    <title>WebRig</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <!--meta name="viewport" content="width=500px"-->
    <!--meta name="viewport" content="width=device-width, initial-scale=1"-->
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="zjslib.js" type="text/javascript"></script>
<style>

</style>
    <script>
    function start(){
        rigstart();
    }
    window.onload = start;
    </script>
</head>
<body oncontextmenu="return false;">
	
<?
    require_once("rig.php");
    rig();
?>

<!--
<div id="validators">
<a href="http://validator.w3.org/check/referer">
<img src="http://www.w3.org/html/logo/badge/html5-badge-h-css3-semantics.png" 
     alt="Valid HTML 5!" height="31" width="88"></a>&nbsp;

<a href="http://jigsaw.w3.org/css-validator/check/referer">
<img style="width:88px;height:31px"
     src="http://jigsaw.w3.org/css-validator/images/vcss" 
     alt="Valid CSS!"></a>&nbsp;
</div>
-->

</body>
</html>
