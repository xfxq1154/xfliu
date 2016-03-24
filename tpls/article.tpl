<{include file="include/header.tpl"}>
	<div class="col-md-9">
		<div class="media content-border">
			<div class="media-body">
				<h1 class="media-heading"><{$artContent.title}></h1>
				<div class="row">
					<div class="col-md-6">
						<p>浏览次数:<span></span></p>
					</div>
					<div class="col-md-6" style="text-align:right">
						<p>发布时间:<{$artContent.time|date_format:'%Y-%m-%d %H:%M:%S'}></p>
					</div>
				</div>
				<{$artContent.content}>
				<hr />
				<p>发表评论:</p>
				<ul class="media-list m-b">
					<{if is_array($artCallBack)}>
						<{foreach from=$artCallBack item=v key=k}>
						<li class="media">
							<a class="media-left" href="#">
								<img class="media-object img-circle" src="http://7xs3gv.com1.z0.glb.clouddn.com/default_face.jpg" style="height:40px;"></a>
							<div class="media-body"> <strong><{$v.user_ip}></strong>
								<{$v.content}>
							</div>
							<hr />
						</li>
						<{/foreach}>
					<{else}>
						<li class="media reply-empty">
							<p>还没有人发表评论</p>
							<hr />
						</li>
					<{/if}>

					<div class="media">
						<div class="call-mack-message-button">
							<div id="container" name="content" style="width:100%"></div>
							<button id="submit-button" class="btn btn-info">提交</button>
						</div>							
						<button type="button" id="call-back" class="btn btn-info">回复</button>
					</div>
				</ul>	
			</div>
		</div>
		<!--评论系统-->
	</div>
	<{include file="include/footer.tpl"}>