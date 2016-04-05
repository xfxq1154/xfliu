<?php 


/*
	@author 刘雪峰 (Xuefeng Liu) 
	@E-mail 273063623@qq.com
*/

class indexAction extends Action{

	function __construct() {
		
		parent::__construct();

	}

	public function index() {

		//博客列表
		$articleList = $this->getBlogArticleList();
		//最新回复
		$newCallBack = $this->getNewCallBack();
		//最热帖子
		$hotArticle = $this->getHotArticleList();

		$this->assign("hot", $hotArticle);
		$this->assign("newback", $newCallBack);
		$this->assign("list", $articleList);
		$this->display('index.tpl');
	}


	/*
		获取技术文章列表
		@return array
		@TODO 缺少分页
	*/
	protected function getBlogArticleList() {
		$article = new mysqlModel('article');
		$field = array("id", "title", "content");
		$where = " status = 1";
		$order = " sort DESC, id DESC";
		//$limit = "";
		$result = $article->select($field, $where, $order);
		//获取的数据建议存入缓存
		$result = $this->insertCacheMessage($result);
		return $result;
	} 


	/*
		增加缓存信息
		@params array result
		@return array
	*/
	private function insertCacheMessage($result) {
		//先判断缓存数据是否存在
		$count = count($result);
		for( $i = 0 ; $i < $count ; $i++ ){
			//增加浏览总量
			$result[$i]['cookies'] = $this->getCacheCount($this->userCookiePoint, $result[$i]['id']);
			//增加评论总量
			$result[$i]['comments'] = $this->getCacheCount($this->commentPoint, $result[$i]['id']);
		}
		return $result;
	}


	/*
		最热帖子
		@return array
		@TODO 添加缓存
	*/
	protected function getHotArticleList() {
		$lastWeekTime = $this->getLastWeekTime();
		//查询出
		$mysql = new mysqlModel();
		$sql = "SELECT
					a.id,
					a.title
				FROM
					blog_article AS a
				INNER JOIN
					(SELECT 
					  article_reply_id,
					  COUNT(article_reply_id) as article_count
					FROM
					  blog_article_reply 
					WHERE
					  `time` > $lastWeekTime
					GROUP BY article_reply_id 
					ORDER BY article_count DESC 
					LIMIT 0, 5 ) AS r
				ON 
					a.id = article_reply_id
				ORDER BY 
					article_count
				DESC";
		$result = $mysql->query($sql);
		return $result;
	}


	/*
		最新回复
		@return array
		@TODO 添加缓存
	*/
	protected function getNewCallBack() {
		$lastWeekTime = $this->getLastWeekTime();
		$mysql = new mysqlModel();
		//还需要返回评论内容啊啊啊啊
		$sql ="SELECT 
					a.id, 
					a.title 
				FROM 
					blog_article AS a 
				INNER JOIN 
					( SELECT 
						article_reply_id 
					FROM 
						blog_article_reply 
					ORDER BY 
						`time` 
					DESC LIMIT 5) AS r 
				ON 
					a.id = article_reply_id 
				ORDER BY 
					a.id 
				DESC"; 
		$result = $mysql->query($sql);
		
		return $result;
	}


	/*
		获取上个星期的时间
		@return varchar
	*/
	private function getLastWeekTime() {
		return date('Y-m-d', strtotime('-1 week'));
	}


}

?>