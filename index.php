<?php 
	/*
 * Copyright (C) 2014 G-Wearable Inc.
 * All rights reserved.
 */
	session_start(); 
    //其实要我说啊 入口文件也要改改
	header("Content-Type:text/html;charset=utf-8");  //设置系统的输出字符为utf-8
	date_default_timezone_set("PRC"); //格林威治时间



    $config = "./config.inc.php";

    if( file_exists( $config ) ){

        require_once($config);
    }

	//包含smarty类文件
	function __autoload($className){

		if(strtolower (substr($className,-6)) == "action" ){

			require_once("Action/".$className.".class.php");

		}elseif(strtolower (substr($className,-5)) == "model" ){

			require_once("Model/".$className.".class.php");

		}elseif(strtolower (substr($className,-3)) == "org"){

			require_once("Org/".$className.".class.php");

        }elseif(file_exists("libs/".ucfirst($className).".class.php")){

            require_once("libs/".ucfirst($className).".class.php");
        
        }elseif(file_exists("libs/sysplugins/".strtolower($className).".php")){
            
            include_once("libs/sysplugins/".strtolower($className).".php");
        
        }
	}




	$c = empty( $_GET['c'] ) ? 'index' : $_GET['c'];
	
	$method = empty( $_GET['m'] ) ? 'index' : $_GET['m'];
    $c.='Action';

    //实例化一个对象
	$action = new $c();
	//调用对象的方法

    //在调用方法的时候需要检查一下TOKEN 就在这检查吧
	$action->$method();


 ?>