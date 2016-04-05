<?php 


/*
	@author 刘雪峰 (Xuefeng Liu) 
	@E-mail 273063623@qq.com
*/

	
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