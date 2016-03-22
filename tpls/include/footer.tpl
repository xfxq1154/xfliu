	</div>
</div>
</div>
<!--分割线-->
<div class="tlag_line"></div>
<div class="container-fluid" style="background-color:#eceeef;padding:20px;">
	<div class="row">
		<div class="col-md-offset-1 col-md-11">
  		<p>刘雪峰的PHP网站©2016.</p>
  		<p>保留所有原创日志的权利.</p>
  		<p>转载请注明出处：<a href="#">http://www.grphp.com</a></p>
  		<p>Powered by <a href="http://v4.bootcss.com/">bootstrap4.0</a></p>
		</div>
	</div>
</div>
</body>

<script src="https://cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
<script src="https://cdn.rawgit.com/twbs/bootstrap/v4-dev/dist/js/bootstrap.js"></script>
<script type="text/javascript" charset="utf-8" src="public/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="public/ueditor/ueditor.all.min.js"> </script>
<!--建议手动加在语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->
<!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->
<!-- 实例化编辑器 -->
    <script type="text/javascript">
        var ue = UE.getEditor('container');
        ue.ready(function(){
          $("#submit-button").click(function() {
            var html = ue.getContent();
            $.ajax({
              url: "index.php?c=article&m=ueditorContent",
              type: "POST",
              data: {content:html},
              dataType: "json",
              success:function(data) {
                if(data.success == "ok"){
                  $(".media-list").prepend("<li class='media'><a class='media-left' href='#'><img class='media-object img-circle' src='public/image/myface.jpg' style='height:40px;'></a><div class='media-body'> <strong>Jacon Thornton:</strong>"+html+"</div><hr /></li>");
                }
              },
              error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.status);
                alert(XMLHttpRequest.readyState);
                alert(textStatus);
              }
            });
          });
        });
        //隐藏显示提交按钮
        $(function(){
          $("#call-back").click(function(){
            $(this).css('display', 'none');
            $(".call-mack-message-button").css('display', 'block');
          });
        })

    </script>
<script type="text/javascript" charset="utf-8" src="public/ueditor/lang/zh-cn/zh-cn.js"></script>
</html>