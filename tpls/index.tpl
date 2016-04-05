<{include file="include/header.tpl"}>

		<!--内容列表 倒序-->
		<div class="col-md-6">
			<{foreach from=$list item=v key=k}>
			<div class="media content-border">
				<div class="media-left">
					<img class="media-object" src="public/image/myface.jpg" style="height:40px;">
				</div>
				<div class="media-body next-line">
					<a href="article-articleList-<{$v.id}>.html">
						<h4 class="media-heading"><{$v.title}></h4>
					</a>
					<p class="content-text">
						<{$v.content|strip_tags}>
					</p>
				</div>
				<div class="media-other">
					<span>
						<a href="article-articleList-<{$v.id}>.html">
							阅读全文
						</a>
					</span>
					<span>
						浏览次数(<{$v.cookies}>)
					</span>
					<span>
						评论(<{$v.comments}>)
					</span>
					<span>
						PYTHON
					</span>
				</div>
			</div>
			<{/foreach}>
		</div>	
		<!--点击量之类的-->
		<div class="col-md-3">
			<div class="card">
				<div class="card-header">联系方式</div>
				<div class="card-block">
				    <blockquote class="card-blockquote">
				      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
				      <footer>Someone famous in <cite title="Source Title">Source Title</cite></footer>
				    </blockquote>
			  	</div>
			</div>
			<div class="card">
				<div class="card-header">热评</div>
				<div class="card-block">
					<blockquote class="card-blockquote">
						<{foreach from=$hot item=v key=k}>
						<a href="article-articleList-<{$v.id}>.html">
							<p>
								<{$v.title}>
							</p>
						</a>
						<{/foreach}>
					</blockquote>
				</div>
			</div>
			<div class="card">
				<div class="card-header">最新回复</div>
				<div class="card-block">
					<blockquote class="card-blockquote">
						<{foreach from=$newback item=v key=k}>
						<a href="article-articleList-<{$v.id}>.html">
							<p>
								<{$v.title}>
							</p>
						</a>
						<{/foreach}>
					</blockquote>
				</div>
			</div>
		</div>
<{include file="include/footer.tpl"}>