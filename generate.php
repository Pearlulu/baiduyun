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
.button{
	background-color: #0e59ad;
	font:12px 'Microsoft YaHei',微软雅黑;
	color: white;
	height: 20px;
	min-width: 6em;
	padding: 1px 12px 1px;
	border: 0;
	cursor: pointer;
	margin:0 0 3px 10px;
}
</style>
</head>
</body>
<?php
//贴吧ID：祈渺，博客:http://www.icycat.com
//懒，没写UI，有兴趣的可以自己改。
//或者把site改为【http://yourdomain.com/】。

$basesite = 'http://s.icycat.com/';

$site = $basesite;
//若修改.htaccess将域名指向down.php?id=，下面这行可以注释掉。
$site .= 'down.php?id=';

$backhome = '<a href='.$basesite.'><input class="button" type="button" name="test" value="返回首页"/>';

$panlink = $_POST['panlink'];

if(empty($panlink)){
  die('<p>请填写分享链接</p></a>'.$backhome);
}

/*由于参数顺序会变化，POST传递过来的参数，正则获取参数值。------旧版
$panidre = '/shareid=(?<shareid>\d+)|uk=(?<uk>\d+)|fid=(?<fid>\d+)|dir\/path=(?<dir>.*)/';
preg_match_all($panidre,$panlink,$panid);
$s = implode("",$panid['shareid']);
$u = implode("",$panid['uk']);
$f = implode("",$panid['fid']);
$dir = implode("",$panid['dir']);
*/

//新版短链接
$sharepage = curlget($panlink);
$sharepagere = '/FileUtils\.share_id="(?<shareid>\d+)"|FileUtils\.share_uk="(?<uk>\d+)"/';
preg_match_all($sharepagere,$sharepage,$panid);
$s = implode("",$panid['shareid']);
$u = implode("",$panid['uk']);

//判断文件夹
$dirnum = strpos($panlink, '#dir/path=');
if($dirnum) {
	$dir = substr($panlink, $dirnum+10);
	$isdir = true;
} else {
	$isdir = false;
}


if($isdir)
{
	//$dirbaselink = substr($panlink,0,$dirbaselinknum+10);
	
	$linkid = 's'.$s.'u'.$u.'d'.$dir;

	$dirurl = 'http://pan.baidu.com/share/list?page=1&shareid='.$s.'&uk='.$u.'&dir='.$dir;
	$pagefile = curlget($dirurl);
	$jsonobj = json_decode($pagefile);
	if($jsonobj->errno != 0)
	{
		die("<p>分享的文件已经被取消或不存在!</p>".$backhome);
	}

}
else
{
	$linkid = 's'.$s.'u'.$u;
	
	if(strpos($panlink,'#dir'))
	{
		$panlink = str_replace('#dir','',$panlink);
	}
	
	$pagefile = $sharepage;
	$re = '/\[\{.*\}\]/';
	if(preg_match_all($re,$pagefile,$result))
	{
		$jsonstr = str_replace('\\\\','$ma^rk$',$result[0][0]);
		$jsonstr = str_replace('\\','',$jsonstr);
		$jsonstr = str_replace('$ma^rk$','\\',$jsonstr);
		$jsonstr = '{"errno":0,"list":'.$jsonstr.'}';
		$jsonobj = json_decode($jsonstr);
	}
	else
	{
		die("<p>分享的文件已经被取消或不存在!</p>".$backhome);
	}
}

$obj = $jsonobj->list;

foreach($obj as $k=>$v)
{
	if($obj[$k]->isdir==1)
	{
		$dirname = $obj[$k]->server_filename;
		$path = $obj[$k]->path;
		
		$dirpanlink = 'http://pan.baidu.com/share/link?shareid='.$s.'&uk='.$u.'#dir/path='.rawurlencode($path);
		
		echo '文件夹:<form method="post" action="generate.php" style="margin:0px;display:inline"><input type="hidden" name="panlink" value="'.$dirpanlink.'" ><input class="button" type="submit"  value="'.$dirname.'"></form>';
		echo '<br/><br/>';
	}
	else
	{
		
		$dlink = $obj[$k]->dlink;
		$filename = $obj[$k]->server_filename;
		
		$typenum = strrpos($filename,'.');
		$type = substr($filename,$typenum+1);
		
		$linkurl = $site.$linkid.'p'.$k.'.'.$type;
		
		echo '真实链接>><a style="color:#000;font-size:13;font-weight:bold" href="'.$dlink.'">'.$filename.'</a>';
		if($type=='mp4' || $type=='mkv')
		{
			//$vdlink = str_replace('&','%26',$dlink);
			//echo '<a href="'.$basesite.'video.php?videoid='.$vdlink.'" target="_blank"><input type="button" name="test" value="尝试在线播放"/></a>';
			echo '<form method="post" action="video.php" target= "_blank" style="margin:0px;display:inline"><input type="hidden" name="videoid" value="'.$dlink.'" ><input type="hidden" name="videoname" value="'.$filename.'" ><input class="button" type="submit"  value="尝试在线播放"></form>';
		}
		echo '<br/>';
		echo '外链地址>><a style="color:#0196e3;font-size:13" href="'.$linkurl.'">'.$linkurl.'</a><br/>';
		echo '<br/>';
	}
}

function curlget($url)
{
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_TIMEOUT,5);
	curl_setopt($ch,CURLOPT_HEADER,0);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_REFERER,'http://pan.baidu.com/');
	curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
	curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
	$content = curl_exec($ch);
	curl_close($ch);
	return $content;
}

?>
<?php echo $backhome ?>
</body>
</html>