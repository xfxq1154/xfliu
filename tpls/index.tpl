<{include file="include/header.tpl"}>

		<!--内容列表 倒序-->
		<div class="col-md-6">
			<{if $list neq null}>
			<{foreach from=$list item=v key=k}>
			<div class="media content-border">
				<div class="media-left">
					<img class="media-object" src="http://q1.qlogo.cn/g?b=qq&nk=<{$masterMessage.email}>&s=100" class="img-circle index_avatar" style="height:40px;"/>
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
			<{else}>
			<div class="media content-border">
				<h6 style="text-align:center">敬请期待</h6>
			</div>
			<{/if}>
		</div>	
		<!--点击量之类的-->
		<div class="col-md-3">
			<div class="card">
				<div class="card-header">联系方式</div>
				<div class="card-block">
				    <blockquote class="card-blockquote">
				      <p>邮箱: <{$masterMessage.email}></p>
				      <footer>
				      	QQ: <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<{$masterMessage.email}>&site=qq&menu=yes"><{$masterMessage.qq_num}></a>
				      </footer>
				    </blockquote>
			  	</div>
			</div>
			<div class="card">
				<div class="card-header">热评</div>
				<div class="card-block">
					<blockquote class="card-blockquote">
						<{foreach from=$hot item=v key=k}>
						<a href="article-articleList-<{$v.id}>.html">
							<h6 class="webkit-line-clamp-2">
							  <{$v.title}>
							</h6>
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
							<h6>
							  <p><{$v.content}></p>
							  <small class="text-muted webkit-line-clamp-1"><{$v.title}></small>
							</h6>
						</a>
						<{/foreach}>
					</blockquote>
				</div>
			</div>
		</div>
<{include file="include/footer.tpl"}>