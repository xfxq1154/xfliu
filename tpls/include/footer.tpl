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
    <script type="text/javascript">
        var ue = UE.getEditor('container');
        
        ue.ready(function(){
          $("#submit-button").click(function() {
            var html = ue.getContent();
            var art_id = $(".art_id").val();
            if ( art_id > 0) {
              $.ajax({  
                url: "article-submitComment-<{$artContent.id}>.html",
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
            }
          });
        });
        //隐藏显示提交按钮
        $(function(){
          $("#call-back").click(function(){
            $(this).css('display', 'none');
            $(".call-mack-message-button").css('display', 'block');
          });
        })

        SyntaxHighlighter.all(); //执行代码高亮

        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-75598329-1', 'auto');
          ga('send', 'pageview');   

    </script>
<script type="text/javascript" charset="utf-8" src="public/ueditor/lang/zh-cn/zh-cn.js"></script>
</html>