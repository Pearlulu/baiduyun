<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>百度网盘外链 | 冻猫</title> 
<link rel="shortcut icon" href="http://static.icycat.com/images/favicon.ico" />
<style type="text/css">
body{
	font:12px 'Microsoft YaHei',微软雅黑,Arial,Lucida Grande,Tahoma,sans-serif;
}
.textinput{
	width:500px;
}
.button{
	background-color: #0e59ad;
	font:12px 'Microsoft YaHei',微软雅黑;
	color: white;
	height: 23px;
	min-width: 6em;
	padding: 1px 12px 1px;
	border: 0;
	cursor: pointer;
	margin:0 0 3px 10px;
}
</style>
</head>
<body>
<p>支持所有文件格式，支持多文件分享，支持文件夹分享。基本上啥都支持。部分视频格式可直接在线播放。</p>
<p>如有问题，请到<a style="color:#000;font-size:13;font-weight:bold;text-decoration: none;" href="http://www.icycat.com/baidupan" target="_blank" >我的博客</a>留言反馈。</p>
<p>复制要分享的页面链接，粘贴到文本框。也可以直接复制要分享的文件所在页面链接。</p>
<form method="post" action="generate.php">
<input class="textinput" type="text" name="panlink">
<input class="button" type="submit" value="生成外链">
</form>
<br/>
<br/>
<p>版本更新：</p>
1.7 修复文件夹目录包含空格导致不能外链的问题<br/>
1.6 增加子文件夹浏览功能，现在浏览方式基本和百度网盘一样<br/>
1.5 修复极少数分享链接参数顺序错误导致不能外链的问题<br/>
1.4 精简代码，加快解析速度<br/>
1.3 增加在线播放网盘视频功能<br/>
1.2 修改样式，增加自动生成文件名功能<br/>
1.1 修复若干BUG，增加支持fid<br/>
1.0 上线第一个版本，支持文件夹解析<br/>
</body>
</html>