<?php 

/*
	@author 刘雪峰 (Xuefeng Liu) 
	@E-mail 273063623@qq.com
*/

class Action extends tplOrg{

	protected $mem;
	protected $userCookiePoint;
	protected $commentPoint; 
	protected $cacheSyncKey;
	protected $navCacheKey;
	protected $badwordKey;

    function __construct()
    {
    	$this->navCacheKey     = "navCacheKey";
    	$this->cacheSyncKey    = "cacheSyncKey";
    	$this->userCookiePoint = "userCookiePoint";
    	$this->commentPoint    = "commentPoint";
 		$this->badwordKey      = "badword";

		//导航
    	$masterMessage = $this->masterMessageFormat( $this->getBlogMasterMessage() ); 
		$nav = $this->getNav();
		$this->assign("nav", $nav);
		$this->assign("masterMessage", $masterMessage[0]);

		//client memcache
		$this->mem = new Memcache;
		$this->mem->connect(MEM_HOST, MEM_PORT);
		//加载缓存数据
		$this->autoCache();
		$this->cacheBadWord();
		
        parent::__construct();

    }   


	/********************************导航信息****************************************/
	/*
		获取导航信息
		@return array
	*/
	protected function getNav() {
		$nav_sql = new mysqlModel("nav");
		$field = array("id", "title");
		$where = "status = 1";
		$order = "sort asc";
		$limit = "0, 8";
		$result = $nav_sql->select($field, $where, $order, $limit);
		return $result;
	}


	/*
		获取博客主人信息
		@return array
	*/
	protected function getBlogMasterMessage() {
		$nav_master_message = new mysqlModel("master_message");
		$field = array("email", "name", "start_work_time", "age", "work_name", "telephone_num", "qq_num", "weibo", "now_company_name", "blog_title", "blog_description", "blog_keywords");
		return $nav_master_message->select($field);
	}


	/*
		个人信息时间戳格式化
		@params array masterMessage
		@return array
	*/
	protected function masterMessageFormat($masterMessage) {
		$masterMessage[0]['start_work_time'] = $this->dateFormatYearAndMonth($masterMessage[0]['start_work_time']);
		$masterMessage[0]['age'] = $this->dateFormatYearAndMonth($masterMessage[0]['age']);
		return $masterMessage;
	}


	/*
		个人信息时间戳格式化
		@params int time
		@return int
	*/
	protected function dateFormatYearAndMonth($time) {
		$year = 365 * 24 * 60 * 60;
		return floor( (time() - $time) / $year );
	}


	/*
		插入数据库字符串过滤
		@params varchar str
		@return varchar
	*/
	protected function _real_escape_string( $str ) {
		return mysql_real_escape_string( $str );
	}


	/********************************缓存信息初始化****************************************/

	/*
		缓存初始化
		@return void
	*/
	protected function autoCache() {
    	//验证缓存信息是否存在
    	if( empty( $this->mem->get( $this->cacheSyncKey ) ) ) {
    		$this->mem->add($this->cacheSyncKey, 1, false, 0);
    		$this->insertCookiesCache(); //浏览记录插入缓存
    		$this->insertCommentCache(); //评论记录插入缓存
    		$this->insertNavCache();     //导航缓存插入
    		$this->cacheBadWord();       //关键词缓存
    	}
    }

    /*
		插入用户浏览数据到缓存
		@return void
	*/
	private function insertCookiesCache() {
		$mysql = new mysqlModel();
		$sql = "SELECT 
					COUNT(*) as art_cookies,
					art_id 
				FROM 
					blog_article_cookies 
				GROUP BY 
					art_id";
		$result = $mysql->query($sql);
		$count = count($result);
		
		for( $i = 0 ; $i < $count ; $i++) {
			$this->mem->add($this->userCookiePoint."-".$result[$i]['art_id'], $result[$i]['art_cookies'], false, 0);
		} 
	}


	/*
		插入用户评论数量到缓存
		@return void
	*/
	private function insertCommentCache() {
		$mysql = new mysqlModel();
		$sql = "SELECT 
					COUNT(*) as art_comment,
					article_reply_id 
				FROM 
					blog_article_reply
				WHERE status = 1 
				GROUP BY 
					article_reply_id";
		$result = $mysql->query($sql);
		$count = count($result);
		
		for( $i = 0 ; $i < $count ; $i++) {
			$this->mem->add($this->commentPoint."-".$result[$i]['article_reply_id'], $result[$i]['art_comment'], false, 0);
		} 
	}	

	private function insertNavCache() {
		$result = $this->getNav();
		$count = count($result);
		for( $i = 0 ; $i < $count ; $i++ ) {
			$this->mem->set($this->navCacheKey."-".$result[$i]['title'], $result[$i]['id']); 
		}
	}

	/*
		生成cache的缓存key
		@params varchar keyName 区别缓存的唯一ID
		@params int     art_id  帖子ID
		@return varchar 一个缓存的key 
	*/
	protected function createKeyName( $keyName, $art_id ) {
		return $keyName."-".$art_id;
	}

	/*
		查看当前文章的各项缓存量
		@params varchar keyName 缓存键名
		@params int     art_id 帖子ID
		@params int     num 默认显示数量
		@return varchar 
	*/
	protected function getCacheCount( $keyName, $art_id , $num = 0) {
		$count = $this->mem->get($this->createKeyName( $keyName, $art_id) );
		if( $count == 0 ) {
			//如果浏览量为零 曾默认增加一个浏览记录 因为increment 不会自动创建元素
			$this->mem->add($this->createKeyName( $keyName, $art_id ), $num);
			$count = $num;
		}
		return $count;
	}

	/*
		缓存屏蔽词
	*/
	private function cacheBadWord() {
		$badword = new badwordOrg();
		$badword_array = array_combine($badword->badword(),array_fill(0,count($badword->badword()),'*'));
		$this->mem->set($this->badwordKey, $badword_array);
	}


	/********************************其它功能缓存****************************************/
	
	/*
		获取用户IP
		@return varchar 
	*/
	protected function getUserIp() {
		return $_SERVER["REMOTE_ADDR"];
	}
}
?>
