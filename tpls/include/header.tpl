<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags always come first -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.rawgit.com/twbs/bootstrap/v4-dev/dist/css/bootstrap.css">
    <link rel="stylesheet" href="/xfliu/public/css/style.css">
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
					<{foreach from=$nav item=v key=k}>
					<li class="nav-item">
						<a class="nav-link" href="<{$v.id}>"><{$v.title}></a>
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