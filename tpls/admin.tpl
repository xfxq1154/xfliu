<{include file="include/header.tpl"}>
	<div class="col-md-9">
		<!--后台写入系统-->
		<div class="media">
			<form action="adminIndex-submitBlog.html" method="POST">
				<fieldset class="form-group">
					<label for="exampleInputEmail1">标题</label>
					<input type="text" name="title" class="form-control">
				</fieldset>
				<fieldset class="form-group">
					<label for="exampleInputEmail1">内容</label>
					<div id="container" name="content" style="width:100%;height:300px;"></div>
					<button class="btn btn-info">提交</button>
				</fieldset>
			</form>
		</div>
	</div>
<{include file="include/footer.tpl"}>