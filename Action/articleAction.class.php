<?php 

/*
	@author 刘雪峰 (Xuefeng Liu) 
	@E-mail 273063623@qq.com
*/


class articleAction extends Action{

	private $art_id;
	private $cookiesKey;


	function __construct() {

		if( intval($_GET['art_id']) > 0 ) {
			$this->art_id = intval($_GET['art_id']);
		} else {
			header('HTTP/1.1 404 Not Found'); 
			header('status: 404 Not Found');
			exit(); 
		}
  
		$this->cookiesKey = $this->createCookiesKey($this->art_id);

		parent::__construct();
	}


	//筛选类别 语言类别 需要分页类
	public function articleList() {

		//增加一个浏览记录
		$this->addArticleCookies( $this->art_id );
		//获取内容
		$artContent = $this->getArtContent( $this->art_id );
		//获取浏览量
		$artCount = $this->getCacheCount( $this->userCookiePoint, $this->art_id );
		//获取回复信息
		$artCallBack = $this->artGetTotalCallBackMessage( $this->art_id );

		$this->assign('artCount', $artCount);
		$this->assign('artCallBack', $artCallBack);
		$this->assign("artContent", $artContent);
		$this->display("article.tpl");
	} 

	
	/*
		获取博客内容
		@params int art_id
		@return array
	*/
	private function getArtContent( $art_id ) {

		$artContent = $this->getArtToMem( $art_id );

		if( !is_array( $artContent ) ) {

			$article = new mysqlModel("article");
			$field = array("id", "title", "content", "time");
			$where = " id = ".$art_id. " AND status = 1";

			$artContent = $article->select($field, $where);

			if( empty($artContent) || $artContent ) {
				//如果不存在 或者为空的话 则直接跳转到404
				#TODO 
			} 
		}
		return $artContent[0];
	}

	
	/*
		获取评论信息
		@params int art_id
		@return array
	*/
	private function artGetTotalCallBackMessage( $art_id ) {
		$article_reply = new mysqlModel("article_reply");
		$field = array("time", "user_ip", "content");
		$where = " article_reply_id = " . $art_id . " AND status = 1";
		$order = " sort DESC, time DESC";

		$result = $article_reply->select($field, $where, $order);

		if( empty( $result ) ) {
			//直接跳转到错误
		}
		return $result;
	}

	
	/*
		获取被缓存的文章
		@params int art_id
		@return array
	*/
	private function getArtToMem($art_id) {
		//这个数据要经过 json_decode 来转化成数组
		return false;
	}


	/*
		增加一个浏览记录
		@params int art_id
		@return void
	*/
	private function addArticleCookies( $art_id ) {

		if( empty( $this->mem->get($this->cookiesKey) ) ) { //为空

			$this->computeToCache( $this->userCookiePoint, $art_id );
			$this->mem->add($this->cookiesKey, 1, false, COOKIE_LIFE_TIME); //之后增加浏览标志位 30秒钟内再次浏览则不增加浏览记录
			$this->cookieLogAddSql( $art_id ); //之后把数据插入到数据库中

		}

	}


	/*
		增加浏览每个人的浏览记录KEY
		@params int art_id
		@return varchar(32)
	*/
	private function createCookiesKey( $art_id ) {
		return md5( $this->getUserIp()."-".addslashes( $art_id ) );
	}


	/*
		数据库增加一个浏览记录
		@params int art_id
		@return void
	*/
	private function cookieLogAddSql( $art_id ) {
		$article_cookies = new mysqlModel("article_cookies");
		$data["art_id"] = $art_id;
		$data['time'] = time();
		$data['user_ip'] = $this->getUserIp();
		$article_cookies->add($data);
	}


	/*
		评论 && 评论次数缓存
		@return json
	*/
	public function submitComment() {
		if( @$_SESSION['user_id'] ) {
			$data['user_id'] = $_SESSION['user_id'];
		} else {
			$data['user_ip'] = $this->getUserIp();
		}

		$data['content'] = $this->_real_escape_string($_POST['content']);
		$data['article_reply_id'] = $_GET['art_id'];
		$data['time'] = time();

		$article_reply = new mysqlModel("article_reply");
		$result = $article_reply->add($data);

		#TODO 需要返回默认头像 和 坐标值  坐标值需要加工

		//提交评论缓存
		if( $result['success'] ) {
			$this->computeToCache($this->commentPoint, $data['article_reply_id']);
		}

		echo json_encode($result);
	}


	/*
		用于计算增加减少量
		@params int art_id      帖子ID
		@params varchar keyName 区别功能的KEY 
		@params bool type       操作类型
		@return void            不返回任何数据
	*/
	private function computeToCache( $keyName, $art_id, $type = TRUE , $num = 1) {
		if( $type ) { //如果type 为真 则增加
			$this->mem->increment( $this->createKeyName($keyName, $art_id), $num);
		} else {
			$this->mem->decrement( $this->createKeyName($keyName, $art_id), $num);
		}
	}

}

 ?>