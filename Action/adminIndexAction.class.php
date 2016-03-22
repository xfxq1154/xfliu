<?php 
class adminIndexAction extends Action{

	function __construct(){
		parent::__construct();
	}

	//后台主页面
	public function index() {
		$this->display("admin.tpl");
	}

	//文本提交
	public function getTechnicalArticles() {
		$data['title'] = $this->_real_escape_string( $_POST['title'] );
		$data['content'] = $this->_real_escape_string( $_POST['content'] );
		$data['time'] = time();

		$article = new mysqlModel("article");
		$result = $article->add($data);
		//判断之后再说 最好做个跳转 直接跳转到发布页
		if ( $result ) {
			echo "ok";
		}
	}

}
 
 ?>
