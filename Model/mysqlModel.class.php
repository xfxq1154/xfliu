<?php 
/*********************************************************************************
 * $Author: 刘雪峰 (xfliu@g-wearables.com)                                       *
 * $Date:   2014-10-25 12:00:00                                                  * 
 ** ******************************************************************************
 * $model:  数据库类,直接调用即可                                                *
 * $ps:     今天是周六 应该会有加班费吧                                          *
 * *******************************************************************************/

		class mysqlModel{
		protected $host;      /*服务器主机地址*/
		protected $user;      /*数据库用户名*/
		protected $password;  /*数据库密码*/
		protected $charset;   /*数据库字符集*/
		protected $port;      /*数据库端口号*/
		protected $dbName;    /*数据库名*/
		protected $prifix;    /*数据库表前缀*/	
		protected $tabName;   /*数据库表名*/
		protected $link;      /*数据库连接资源*/
		protected $field;     /*存储当前的表结构信息*/

		function __construct( $tabName = "" , $charset = "utf8" ){

			$this ->host     = DB_HOST; //初始化服务器主机地址 默认localhost
			$this ->user     = DB_USER; 
			$this ->password = DB_PASSWORD;
			$this ->charset  = $charset;
			$this ->prot     = DB_PORT; //端口号 默认3306
			$this ->prifix   = DB_PRIFIX; //设置表前缀
			$this ->dbName   = DB_NAME;
			//初始化表名
			if(!empty($tabName)){

				$this ->tabName = $this ->prifix.$tabName; //goccia_user
				
			} else { //集成该类使用 不需要表名 
				
				/*get_class 获取类名*/
				$this ->tabName = $this ->prifix.strtolower(substr(get_class($this) , 0 , -5)); //UserModel

			}

			//初始化数据库连接
			$this ->link = $this ->connect();

			//在初始化对象
			$this ->field = $this->getField();


		}

		//连接数据库方法
		function connect(){

			$conn = mysql_connect( $this->host , $this->user , $this->password );

			if(mysql_errno()){

				return false;

			}

			mysql_set_charset( $this->charset );

			mysql_select_db($this->dbName);

			return $conn;
		}

		//增删改操作
		function exec($sql){

			//执行SQL语句
			$result = mysql_query($sql);

			if( $result && mysql_affected_rows() ){

				//使用受影响行代替返的布尔值
				$data['success'] = "ok";
				$data['lastId']  = mysql_insert_id();
				return $data;

			} else {

				//执行增删改失败
				return false;

			}

		}

		//查询操作
		function query($sql){

			$result = mysql_query($sql);

			if($result && mysql_affected_rows()){

				while( $row = mysql_fetch_assoc($result)){

					//把单挑数据放入数组 组成二维数组
					$rows[] = $row;

				}

				return $rows;
			} else {
				//查询失败
				return false;

			}

		}

		//获取数据库的数据结构 防止瞎JB传
		//第一次从数据库
		//以后从文件中取
		//dsec goccia_user
		function getField(){

			//假设缓存字段的文件已经存在
			//组合缓存文件的路径
			$cache = 'cache/' . $this->tabName . '.php';

			//检测缓存文件是否存在
			if(file_exists($cache)){
				//如果存在缓存文件 则读取文件
				
				return include $cache;

			} else { //如果不存在则写缓存文件 再读取文件

				$sql    = 'desc ' . $this->tabName;// 组合查询表结构的SQL语句

				$result = $this->query($sql);

				$field  = $this->writeField($result);
				//返回表字段信息
				return $field;

			} 
			
		}

		function writeField($data){
			
			$fields = array();
			
			foreach($data as $value){

				//保存主键
				if($value['Key'] == 'PRI'){

					$fields['_pk'] = $value['Field'];

				}

				//保存自增键
				if($value['Extra'] == 'auto_increment'){

					$fields['_auto'] = $value['Field'];

				}

				$fields[] = $value['Field']; //$field[0] = 'id';

			}

			//准备缓存文件的内容(字符串类型) 需要一个return 这样读取的时候可以直接获取缓存内容
			//这里的var_export需要两个参数 第二个不写的话就输出不保存
			$str = " <?php \n return " . var_export($fields,true) . " \n ?>";

			//书写缓存文件
			file_put_contents('cache/'.$this->tabName .'.php',$str);

			//返回字段信息
			return $fields;
		}

		//添加操作
		function add($data , $message = array()){

			//用来组合字段的变量
			$keys   = '';
			$values = '';

			foreach($data as $key => $value){

				//过滤非法字段
				if( ! in_array($key , $this->field)){
					//如果传入的字段不存在 就过滤掉
					continue;
				}
				//组合字段
				$keys   .= $key . ',';
				//组合值
				$values .= "'".$value."',";

			}

			$keys   = trim(rtrim($keys   , ','));
			$values = trim(rtrim($values , ','));

			$sql = "INSERT INTO {$this->tabName}({$keys}) VALUES({$values})";
			//执行插入操作 并且返回其结果
			return $this->exec($sql , $message);

		}

		//删除操作
		function del(){


		}
		//更新操作
		function update($data , $where ='' , $order = '' , $limit = ''){

			$update = "";

			foreach($data as $key => $value){

				if( ! in_array($key , $this->field)){

					continue;

				}

				$update .= $key . '=' . "'{$value}',";
			}

			$update = trim(rtrim($update , ','));
			//准备where条件 
			if( ! empty($where)){

				$where = ' WHERE ' . $where;

			}

			//准备order条件
			//可能需要更新大于100 并且 desc 的条件
			if( ! empty($order)){

				$order = ' ORDER BY '.$order; // order by id desc

			}

			//准备limit条件
			if( ! empty($limit)){

				$limit = ' LIMIT '.$limit;

			}

			$sql = "UPDATE {$this->tabName} SET {$update} {$where} {$order} {$limit}";

			return $this->exec($sql);

		}
	
		//拼合查询所需的sql语句
		protected function selectSql($field = array() , $where = '' , $order = '' , $limit = ''){
			
			//准备查询的字段
			if( empty($field) ){
				//如果$field 为空值  则默认查询所有
				$f = @join(',' , @array_unique($this->field));

			} else {
				//如果非空值  则传入什么字段查什么字段
				//join() 函数把数组元素组合为一个字符串。
				//join() 函数是 implode() 函数的别名。
				//跟缓存取交集array_intersect  并且一个字段只能出现一次array_unique
				$f = @join(',' , @array_intersect(@array_unique($field) , $this->field));

			}

			//准备where条件 
			if( ! empty($where)){

				$where = ' WHERE ' . $where;

			}

			//准备order条件
			//可能需要更新大于100 并且 desc 的条件
			if( ! empty($order)){

				$order = ' ORDER BY '.$order; // order by id desc

			}

			//准备limit条件
			if( ! empty($limit)){

				$limit = ' LIMIT '.$limit;

			}

			$sql = "SELECT {$f} FROM {$this->tabName} {$where} {$order} {$limit}";
			
			return $sql;

		}

		//查询所有操作
		function select($field = array() , $where = '' , $order = '' , $limit = ''){
			//拼合sql语句
			$sql = $this->selectSql($field , $where , $order , $limit);
			/*return $sql;
			exit;
*/
			return $this ->query($sql);

		}

		//查询出一条
		function find($field = array() , $where = '' , $order = '' , $limit = ''){

			$sql = $this->selectSql( $field , $where , $order , $limit );

			$result = $this->query($sql);
			//返回第一条就OK了
			return $result[0];

		}

		function total($where = ''){
			//准备where条件 
			if( ! empty($where)){

				$where = ' WHERE ' . $where;

			}
			//设置查询使用的字段
			if( empty($this->field['_pk'] )){

				$f = $this->field[0]; // 如果这个表没有主键 则使用第0个

			} else {

				$f = $this->field['_pk']; //存在主键的恶化 则使用主见为计数键

			}
			
			/*SELECT COUNT(id) FROM goccia_user WHERE id > 1000*/
			$sql = "SELECT COUNT({$f}) AS total FROM {$this->tabName} {$where}";

			$result = $this->query($sql);

			return $result[0]['total'];

		}

		//查询最大值
		function max($field , $where = ''){
			//准备where条件 
			if( ! empty($where)){

				$where = ' WHERE ' . $where;

			}
			/*SELECT max(id) FROM goccia_user WHERE 最大值*/
			/*SELECT min(id) FROM goccia_user WHERE 最小值*/
			/*SELECT avg(id) FROM goccia_user WHERE 平均值*/
			$sql = "SELECT max({$field}) as big FROM {$this->tabName} {$where}";

			$result = $this->query($sql);

			//返回查询的最大值
			return $result[0]['big'];
		}


	}
 ?>