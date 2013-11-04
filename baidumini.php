<?php 
//作者:冻猫。
//博客:http://www.icycat.com。
//如有问题，请到博客留言。此版本不添加多余功能。

if (empty($_GET['id'])) {
	exit();
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
	private $password;

	public function __construct($id,$cacheTime) {
		$fileTypePos = strpos($id, '.');
		if ($fileTypePos !== false) {
			$id = substr($id, 0, $fileTypePos);
		}
		if (strpos($id, '_') !== false) {
			$arr = explode('_', $id);
			$id = $arr[0];
			$this->password = $arr[1];
		}
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
		header('Location: '.$this->dlink);
	}

	public function downPrivateFile() {
		echo 'Wait for add this function!';
	}

	private function getPublicDlink() {
		$pagefile = $this->curlGet($this->url);
		if(preg_match_all('/(http:\/\/d\.pcs\.baidu\.com\/file\/.*)"\sid="fileDownload"/',$pagefile,$result)) {
			$this->dlink = htmlspecialchars_decode($result[0][0]);
		} else {
			header("Content-Type:text/html;charset=utf-8");
			die('分享的文件已经被取消或不存在!');
		}
		$_SESSION[$this->id] = $this->dlink;
	}

	private function curlGet($url,$cookie='') {
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_TIMEOUT,5);
		curl_setopt($ch,CURLOPT_HEADER,0);
		curl_setopt($ch,CURLOPT_COOKIE, $cookie);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_REFERER,'http://pan.baidu.com/');
		curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (iPhone; CPU iPhone OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5376e Safari/8536.25');
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
		$content = curl_exec($ch);
		curl_close($ch);
		return $content;
	}

}

?>