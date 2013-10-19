<?php 
//作者:冻猫。
//博客:http://www.icycat.com。
//如有问题，请到博客留言。此版本不添加多余功能。

if (empty($_GET['id'])) {
	exit();
} else if (strlen($_GET['id'])!=6) {
	header("Content-Type:text/html;charset=utf-8");
	die('请输入正确的分享链接');
}

//缓存时间单位秒，缓存时间不能超过expires时间。若出现问题则改为0。
$cacheTime = 25200;

$baiduMini = new BaiduMini($_GET['id'],$cacheTime);

if (strpos($_GET['id'], '_')!==false) {
	//预留
	$baiduMini->downPrivateFile();
} else {
	$baiduMini->downPublicFile();
}

class BaiduMini {

	private $id;
	private $url;
	private $dlink;
	private $cacheTime;

	public function __construct($id,$cacheTime) {
		$this->id = $id;
		$this->url = 'http://pan.baidu.com/s/'.$id;
		$this->cacheTime = $cacheTime;
	}

	public function downPublicFile() {
		session_id($this->id);
		session_start();
		if (!empty($_SESSION[$this->id])) {
			$this->dlink = $_SESSION[$this->id];
			parse_str($this->dlink,$dlinkInfo);
			$pastTime = time()-$dlinkInfo['time'];
			if ($pastTime>$this->cacheTime || $pastTime>25200) {
				$this->getPublicDlink();	
			}
		} else {
			$this->getPublicDlink();
		}
		echo $this->dlink;
		//header('Location: '.$this->dlink);
	}

	public function downPrivateFile() {
		echo 'Wait for add this function!';
	}

	private function getPublicDlink() {
		$pagefile = $this->curlGet($this->url);
		$list = $this->pageDecode($pagefile);
		$this->dlink = $list[0]->dlink;
		$_SESSION[$this->id] = $this->dlink;
	}

	private function pageDecode($pagefile) {
		if(preg_match_all('/\[\{.*\}\]/',$pagefile,$result)) {
			$jsonstr = '{"errno":0,"list":'.stripslashes($result[0][0]).'}';
			$jsonobj = json_decode($jsonstr);
		} else {
			header("Content-Type:text/html;charset=utf-8");
			die('分享的文件已经被取消或不存在!');
		}
		$list = $jsonobj->list;
		return $list;
	}

	private function curlGet($url,$cookie='') {
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_TIMEOUT,5);
		curl_setopt($ch,CURLOPT_HEADER,0);
		curl_setopt($ch,CURLOPT_COOKIE, $cookie);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_REFERER,'http://pan.baidu.com/');
		curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
		$content = curl_exec($ch);
		curl_close($ch);
		return $content;
	}

}

?>