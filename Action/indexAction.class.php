<?php 


/*
	@author 刘雪峰 (Xuefeng Liu) 
	@E-mail 273063623@qq.com
*/

class indexAction extends Action{

	protected $type;
	protected $artPage;

	function __construct() {

		$this->artPage = ARTICLE_LIST_PAGE; //分页

		parent::__construct();
		if( isset( $_GET['nav_id'] ) ) {
			$this->type = $this->mem->get($this->navCacheKey."-".$_GET['nav_id']);
		}
	}

	public function index() {
		$page = new pageModel();
		//博客列表
		$articleList = $this->getBlogArticleList( $this->type );
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
	*/
	protected function getBlogArticleListPage() {
		//先获取页数

	}


	/*
		从数据库中获取缓存的技术列表
		@return array
	*/
	protected function getBlogArticleList( $type = NULL ) {
		$typeWhere = "";
		if( $type ) {
			$typeWhere = " AND type = {$type}";
		}

		$article = new mysqlModel('article');
		$field = array("id", "title", "content", "type");
		$where = " status = 1 ".$typeWhere;
		$order = " sort DESC, time DESC";
		
		$result = $article->select($field, $where, $order);
		//获取的数据建议存入缓存
		if( $result ) {
			$result = $this->insertCacheMessage($result);
		}
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
					) AS r
				ON 
					a.id = article_reply_id
				AND
					a.status = 1
				ORDER BY 
					article_count
				DESC LIMIT 0, 5 ";
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
					a.title,
					b.content
				FROM 
					blog_article AS a,
					blog_article_reply AS b
				WHERE
					a.id = b.article_reply_id
				AND
					a.status = 1
				ORDER BY 
					b.time 
				DESC LIMIT 0, 5"; 
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