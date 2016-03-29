<?php 
    /*
 * Copyright (C) 2014 G-Wearable Inc.
 * All rights reserved.
 */
class Action extends tplOrg{

	protected $mem;
	protected $userCookiePoint;

    function __construct()
    {
    	$this->userCookiePoint = "userCookiePoint";
    	//主页个人信息 可能其他页面也都能用到
    			//获取首页导航
    	$masterMessage = $this->masterMessageFormat( $this->getBlogMasterMessage() ); 
		$nav = $this->getNav();

		$this->assign("nav", $nav);
		$this->assign("masterMessage", $masterMessage[0]);

		//client memcache
		$this->mem = new Memcache;
		$this->mem->connect(MEM_HOST, MEM_PORT);
	
		print_r($this->mem->getServerStatus(MEM_HOST, MEM_PORT));
        parent::__construct();

    }   


	//获取导航
	protected function getNav() {
		$nav_sql = new mysqlModel("nav");
		$field = array("id", "title");
		$where = "status = 1";
		$order = "sort asc";
		$limit = "0, 8";
		$result = $nav_sql->select($field, $where, $order, $limit);
		return $result;
	}

	//获取博客主人个人信息 包括联系方式 建议扔到memcache里面 然后做个更新缓存按钮
	protected function getBlogMasterMessage() {
		$nav_master_message = new mysqlModel("master_message");
		$field = array("name", "start_work_time", "age", "work_name", "telephone_num", "qq_num", "weibo", "now_company_name", "blog_title", "blog_description", "blog_keywords");
		return $nav_master_message->select($field);
	}

	//个人信息时间格式化
	protected function masterMessageFormat($masterMessage) {
		$masterMessage[0]['start_work_time'] = $this->dateFormatYearAndMonth($masterMessage[0]['start_work_time']);
		$masterMessage[0]['age'] = $this->dateFormatYearAndMonth($masterMessage[0]['age']);
		return $masterMessage;
	}

	protected function dateFormatYearAndMonth($time) {
		$year = 365 * 24 * 60 * 60;
		return floor( (time() - $time) / $year );
	}


	protected function _real_escape_string( $str ) {
		return mysql_real_escape_string( $str );
	}

	//查看当前文章的浏览量
	protected function getThisArticlesCookiesCount( $id ) {
		$cookieCount = $this->mem->get($this->userCookiePoint."-".$id);
		if( intval( $cookieCount ) <= 0 ) {
			$cookieCount = 0;
		}
		return $cookieCount;
	}
}
?>
