<?php 
class articleAction extends Action{

	function __construct() {
		
		parent::__construct();
	}


	//筛选类别 语言类别 需要分页类
	public function articleList() {
		$id = intval($_GET['art_id']);
		if(  $id <= 0 ) {
			//报错 否则返回数据
			//该页面不存在
		}

		//获取内容
		$artContent = $this->getArtContent( $id );

		//获取回复信息
		$artCallBack = $this->artGetTotalCallBackMessage( $id );
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

	//验证art_id 是否存在

}

 ?>