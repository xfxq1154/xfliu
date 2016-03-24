<?php 
class indexAction extends Action{

	function __construct() {
		
		parent::__construct();
	}

	public function index() {
		$articleList = $this->getBlogArticleList();

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

	//获取文章
	protected function getHotArticleList() {

	}

	//获取最新回复
	protected function getNewCallBack() {

	}

}

?>