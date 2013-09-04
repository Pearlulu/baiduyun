<!DOCTYPE html>
<?php 
$videoid = $_POST['videoid']; 
$videoname = $_POST['videoname'];
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $videoname ?> | 冻猫</title>
<link rel="shortcut icon" href="http://static.icycat.com/images/favicon.ico" />
<style type="text/css">
body{background-color: rgb(0, 0, 0);}
#html5player{
	max-height: 100%;
	max-width: 100%;
	margin: auto;
	position: absolute;
	top: 0px;
	right: 0px;
	bottom: 0px;
	left: 0px;
}
</style>
</head>
<body>
<video id="html5player" controls="" autoplay="" name="media"><source src="<?php echo $videoid ?>">
你的浏览器不支持HTML5。请使用Chrome、Firefox 或 Opera。
</video>
</body>
</html>