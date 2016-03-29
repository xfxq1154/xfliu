<?php 
class articleAction extends Action{

	private $art_id;

	function __construct() {

		if( intval($_GET['art_id']) > 0 ) {
			$this->art_id = intval($_GET['art_id']);
		} else {
			header('HTTP/1.1 404 Not Found'); 
			header('status: 404 Not Found');
			exit(); 
		}
		parent::__construct();
		//监控memcached 
		//$stats = $this->mem->getExtendedStats();
	}


	//筛选类别 语言类别 需要分页类
	public function articleList() {

		//增加一个浏览记录
		$this->addArticleCookies( $this->art_id );
		//获取内容
		$artContent = $this->getArtContent( $this->art_id );
		//获取浏览量
		$artCount = $this->getThisArticlesCookiesCount( $this->art_id );
		//获取回复信息
		$artCallBack = $this->artGetTotalCallBackMessage( $this->art_id );

		$this->assign('artCount', $artCount);
		$this->assign('artCallBack', $artCallBack);
		$this->assign("artContent", $artContent);
		$this->display("article.tpl");
	} 

	//获取文章内容
	//其实因为列表的原因 所有的文章都可以存入缓存里面 这样只需要获取回复就可以了
	//所以这里其实应该先获取一下缓存是否有文章 如果有的话则获取缓存 没有的话则从SQL中获取
	private function getArtContent($id) {
		$artContent = $this->getArtToMem($id);
		if( !is_array( $artContent ) ) {
			$article = new mysqlModel("article");
			$field = array("id", "title", "content", "time");
			$where = " id = ".$id. " AND status = 1";
			$artContent = $article->select($field, $where);
			if( empty($artContent) || $artContent ) {
				//如果不存在 或者为空的话 则直接跳转到404
				#TODO
			}
		}
		return $artContent[0];
	}

	//获取所有回复
	private function artGetTotalCallBackMessage( $id ) {
		$article_reply = new mysqlModel("article_reply");
		$field = array("time", "user_ip", "content");
		$where = " article_reply_id = " . $id . " AND status = 1";
		$order = " sort DESC, time DESC";
		$result = $article_reply->select($field, $where, $order);

		if( empty( $result ) ) {
			//直接跳转到错误
		}
		return $result;
	}

	//判断缓存中是否有信息
	private function getArtToMem($art_id) {
		//这个数据要经过 json_decode 来转化成数组
		return false;
	}

	//提交回复
	public function ueditorContent() {
		if( @$_SESSION['user_id'] ) {
			$data['user_id'] = $_SESSION['user_id'];
		} else {
			$data['user_ip'] = $this->getUserIp();
		}
		$data['content'] = mysql_real_escape_string($_POST['content']);
		$data['article_reply_id'] = $_GET['art_id']; //$_POST['reply_id'];
		$data['time'] = time();
		$article_reply = new mysqlModel("article_reply");
		$result = $article_reply->add($data);
		//需要返回默认头像 和 坐标值  坐标值需要加工
		echo json_encode($result);
	}


	//获取用户当前IP
	private function getUserIp() {
		return $_SERVER["REMOTE_ADDR"];
	}

	//增加一个浏览记录
	private function addArticleCookies( $id ) {
		if( empty( $this->mem->get($this->userCookiePoint) ) ) {
			//如果不存在则所有浏览记录重新插入
			$this->mem->add($this->userCookiePoint, 1, false, 0); 
			$this->addAllArticleDataInsertMem();
		}

		//当用户进入这个帖子 会增加一次浏览记录 
		//但是同一ID 2分钟重复刷新页面就不会增加浏览记录
		//所以要先查询这个IP是否已经登录过

		if( empty( $this->mem->get($this->createCookiesKey( $id )) ) ) { //存在
			$this->mem->increment( $this->userCookiePoint."-".$id, 1); //浏览量+1

			$this->mem->add($this->createCookiesKey( $id ), 1, false, COOKIE_LIFE_TIME); //之后增加浏览标志位 30秒钟内再次浏览则不增加浏览记录
			$this->cookieLogAddSql( $id ); //之后把数据插入到数据库中
		}

	}




	//创建cookiekey
	private function createCookiesKey( $id ) {
		return $this->getUserIp()."-".addslashes($id);
	}

	//所有的帖子浏览记录计算一遍插入服务器
	//后期这个操作可以交给python来做
	private function addAllArticleDataInsertMem() {
		$article_cookies = new mysqlModel();
		$sql = "SELECT 
					COUNT(*) as art_cookies,
					art_id 
				FROM 
					blog_article_cookies 
				GROUP BY 
					art_id";
		$result = $article_cookies->query($sql);
		$count = count($result);
		for( $i = 0 ; $i < $count ; $i++) {
			$this->mem->add($this->userCookiePoint."-".$result[$i]['art_id'], $result[$i]['art_cookies'], false, 0);
		} 
	}


	//
	private function cookieLogAddSql( $id ) {
		$article_cookies = new mysqlModel("article_cookies");
		$data["art_id"] = $id;
		$data['time'] = time();
		$article_cookies->add($data);
	}

}

 ?>