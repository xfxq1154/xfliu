<?php 
class adminListAction extends Action{
	
	function __construct() {
		parent::__construct();
	}	

	//查看当前发布的所有的文章
	public function articleList() {
		$this->display();
	}
}
 ?>