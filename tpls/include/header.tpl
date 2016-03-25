<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags always come first -->
    <meta charset="utf-8">
    <{if !empty($artContent.title)}>
    	<title><{$artContent.title}>_雪峰个人PHP博客_PHP交流</title>
    <{else}>
  		<title><{$masterMessage.blog_title}></title>
    <{/if}>
	<meta name="keywords" content="<{$masterMessage.blog_keywords}>">
	<meta name="description" content="<{$masterMessage.blog_description}>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.rawgit.com/twbs/bootstrap/v4-dev/dist/css/bootstrap.css">
    <link rel="stylesheet" href="public/css/style.css">
    <!--google-analytics-->
    <script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-75598329-1', 'auto');
	  ga('send', 'pageview');

	</script>
   	<script type="text/javascript" src="public/ueditor/third-party/SyntaxHighlighter/shCore.js"></script>   
<link rel="stylesheet" href="public/ueditor/third-party/SyntaxHighlighter/shCoreDefault.css" type="text/css" />
	<script>   
		SyntaxHighlighter.all() //执行代码高亮   
	</script>
</head>
<style type="text/css">
	body{
		font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
		font-size:14px;
		line-height: 20px;
		color:#666;
	}
	.breadcrumb > li{
		font-size:2em;
	}
</style>
<body>
<div class="container-fluid">
	<div class="row ">
		<nav class="navbar navbar-light bg-faded">
			<div class="col-md-offset-1 col-md-10">
				<a class="navbar-brand" href="#">刘雪峰</a>
				<ul class="nav navbar-nav">
					<li class="nav-item">
						<a class="nav-link" href="/">首页</a>
					</li>
					<{foreach from=$nav item=v key=k}>
					<li class="nav-item">
						<a class="nav-link" href="<{$v.title|strtolower}>.html"><{$v.title}></a>
					</li>
					<{/foreach}>
				</ul>
			</div>
		</nav>
	</div>
	<!--分割线-->
	<div class="tlag_line"></div>
	<div class="col-md-offset-1 col-md-10">
	<div class="row">
		<div class="col-md-3">
			<div class="index_my_style">
				<div></div>
				<div style="height:300px;">
					<a href="#">
						<img src="public/image/myface.jpg" class="img-circle index_avatar"></a>
					<h5>
						<a href="#"><{$masterMessage.name}></a>
					</h5>
					<p>年龄<{$masterMessage.age}></p>
					<p><{$masterMessage.work_name}></p>
					<p><{$masterMessage.start_work_time}>年实际开发经验</p>
					<p>目前在<{$masterMessage.now_company_name}></p>
				</div>
			</div>
		</div>