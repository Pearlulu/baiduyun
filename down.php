<?php
header('Content-Type: text/html;charset=utf-8');

$id = $_REQUEST['id'];

$typenum = strrpos($id,'.');
if($typenum)
{
	$id = substr($id,0,$typenum);
}

$pnum = strrpos($id,'p');
$p = substr($id,$pnum+1);

$panid = substr($id,0,$pnum);

//判断有无文件夹 替换字符串
$dnum = strpos($panid,'d');
if($dnum)
{
	$dir = substr($panid,$dnum+1);

	$su = substr($panid,0,$dnum);
	$su = str_replace('s','shareid=',$su);
	$su = str_replace('u','&uk=',$su);
	$dir = rawurldecode($dir);
	$dir = urlencode($dir);
	$dir = '&dir='.$dir;
	$dirurl = 'http://pan.baidu.com/share/list?page=1&'.$su.$dir;
	$pagefile = curlget($dirurl);
	$jsonobj = json_decode($pagefile);
}
else
{
	if(strpos($panid,'f'))
	{
		$panid = str_replace('f','&fid=',$panid);
	}
	$panid = str_replace('s','shareid=',$panid);
	$panid = str_replace('u','&uk=',$panid);
	$panlink='http://pan.baidu.com/share/link?'.$panid;
	$pagefile = curlget($panlink);
	$re = '/\[\{.*\}\]/';
	preg_match_all($re,$pagefile,$result);
	$jsonstr = str_replace('\\\\','$ma^rk$',$result[0][0]);
	$jsonstr = str_replace('\\','',$jsonstr);
	$jsonstr = str_replace('$ma^rk$','\\',$jsonstr);
	$jsonstr = '{"errno":0,"list":'.$jsonstr.'}';
	$jsonobj = json_decode($jsonstr);
}

$dlink = $jsonobj->list[$p]->dlink;

header("location:$dlink");

function curlget($url)
{
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_TIMEOUT,5);
	curl_setopt($ch,CURLOPT_HEADER,0);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_REFERER,'http://www.baidu.com/');
	curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
	curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
	$content = curl_exec($ch);
	curl_close($ch);
	return $content;
}

?>