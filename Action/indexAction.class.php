<?php 
class indexAction extends Action{

	function __construct() {
		
		parent::__construct();
	}

	public function index() {

		$articleList = $this->getBlogArticleList();
		$newCallBack = $this->getNewCallBack();
		$hotArticle = $this->getHotArticleList();

		$this->assign("hot", $hotArticle);
		$this->assign("newback", $newCallBack);
		$this->assign("list", $articleList);
		$this->display('index.tpl');
	}



	//获取技术文章列表
	protected function getBlogArticleList() {
		$article = new mysqlModel('article');
		$field = array("id", "title", "content");
		$where = " status = 1";
		$order = " sort DESC, id DESC";
		//$limit = "";
		$result = $article->select($field, $where, $order);
		return $result;
	} 

	//先实现 再修改
	//热评
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

	//最新回复 不应该只回复帖子title 还要返回用户说的话
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


	//获得上个星期到
	private function getLastWeekTime() {
		return date('Y-m-d', strtotime('-1 week'));
	}

}

?>