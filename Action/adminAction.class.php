<?php 


/*
	@author 刘雪峰 (Xuefeng Liu) 
	@E-mail 273063623@qq.com
*/

class adminAction extends Action{

	function __construct(){
		parent::__construct();
	}

	//后台主页面
	public function index() {
		$this->display("admin.tpl");
	}


	/*
		提交一个博客
		@params int art_id
		@return varchar
	*/
	public function submitBlog() {

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

	/*
		增加一个清空缓存的方法
		@return void
	*/
	public function memFlush() {
		$this->mem->flush();
		print_r("ok");
	}

}
 
 ?>
