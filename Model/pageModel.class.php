<?php 
/*
	@name 分页类
	@author 刘雪峰 (Xuefeng Liu) 
	@E-mail 273063623@qq.com
	@web www.grphp.com
	@ps 基于bootstrap4.0
*/

class pageModel{
	protected $page_total;    /*总页数*/
	protected $page_num;      /*当前页数*/
	protected $art_total;     /*文章总数*/
	protected $page_first;    /*起始页数*/
	protected $page_end;      /*结尾页数*/
	protected $page_size;     /*每页显示文章数量*/
	protected $page_url;

	public function __construct($art_total = 130, $page_num = 0, $page_url = "") {
		$this->page_size = ARTICLE_LIST_PAGE;
		$this->art_total = $art_total;
		$this->page_num = $this->verPageNum( $page_num );
		$this->page_total = $this->pageTotal();
		$this->page_url = $page_url;

	}

	//计算总页数
	public function setPage() {
		echo $this->pageNext();
	}

	//验证页数是否正确
	private function verPageNum( $page ) {
		$page = $this->isNumber( $page );
		if ( 1 > $page || $page > $this->art_total ) {
			$page = 1;
		}
		return $page;
	}

	//过滤数据 防止sql注入
	private function isNumber( $var ) {
		$num = 1;
		if( is_int( $var ) || is_string( $var ) ) {
			$num = intval($var);
		}
		return $num;
	}

	//总页数
	private function pageTotal() {
		return ceil( $this->art_total / $this->page_size );
	}

	//根据当前页数 
	protected function pageNum() {
		$str = "";
		for( $i = 1; $i <= $this->page_total; $i++) {
			$str.="<li><a href='/xfliu/" . $i . "'>".$i."</a></li>";
		}
		return $str;
	}

	//上一页
	protected function pagePrev() {
		if($this->page_num > 1) {
			$page_num = $this->page_num - 1;
			$prev = $this->pagePrevAndNextHerf( $page_num, "Next", $page_num );
		} else {
			$prev = $this->pageDisabled("Previous", "上一页");
		}
		return $prev;
	}

	//下一页
	protected function pageNext() {

		if($this->page_num < $this->page_total) {
			$page_num = $this->page_num + 1;
			$prev = $this->pagePrevAndNextHerf($page_num, "Next", $page_num);
		} else {
			$prev = $this->pageDisabled("Next", "下一页");
		}
		echo $prev;
		return $prev;
	}


	private function pagePrevAndNextHerf($page_num, $class, $name, $model = NULL) {
		if( $model ) {
			$model = "/".$model."/";
		}

		return "<li><a href=''/xfliu/" . $model . $page_num . "' aria-label='" . $class . "'><span aria-hidden='true'>&laquo;</span><span class='sr-only'>" . $name . "</span></a></li>";
	}

	private function pageDisabled($class, $name) {
		return "<li class='disabled'><a href='#' aria-label='" . $class . "'><span aria-hidden='true'>&laquo;</span><span class='sr-only'>" . $name . "</span></a></li>";
	}

	//首页
	protected function pageFirst() {

	}

	//尾页
	protected function pageLast() {

	}

	//分页输出
	public function pageShow() {

	}

}


 ?>