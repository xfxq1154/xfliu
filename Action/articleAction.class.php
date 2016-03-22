<?php 
class articleAction extends Action{

	function __construct() {
		
		parent::__construct();
	}


	//筛选类别 语言类别 需要分页类
	public function articleList() {
		$this->display("article.tpl");
	} 

	//提交数据
	public function ueditorContent() {

		if( @$_SESSION['user_id'] ) {
			$data['user_id'] = $_SESSION['user_id'];
		} else {
			$data['user_ip'] = $this->getUserIp();
		}

		$data['content'] = mysql_real_escape_string($_POST['content']);
		$data['article_reply_id'] = 2; //$_POST['reply_id'];
		$data['time'] = time();
		$article_reply = new mysqlModel("article_reply");
		$result = $article_reply->add($data);
		echo json_encode($result);
	}


	//获取用户当前IP
	private function getUserIp() {
		return $_SERVER["REMOTE_ADDR"];
	}
}

 ?>