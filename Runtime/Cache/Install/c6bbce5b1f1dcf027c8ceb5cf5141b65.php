<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>系统安装</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- Le styles -->
        <link href="/github/201506bbjie/Public/cdn/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet">
        <link href="/github/201506bbjie/Public/Install/css/install.css" rel="stylesheet">

        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
            <script src="/github/201506bbjie/Public/cdn/html5shiv/3.7.2/html5shiv.min.js"></script>
        <![endif]-->
        
        <script src="/github/201506bbjie/Public/cdn/jquery/1.11.0/jquery.min.js"></script>
        <script src="/github/201506bbjie/Public/cdn/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    </head>
	
	
    <body data-spy="scroll" data-target=".bs-docs-sidebar">
        <!-- Navbar 
        ================================================== -->
        <div class="navbar navbar-inverse navbar-fixed-top">
        		<div class="navbar-header">
		      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
		        <span class="sr-only">Toggle navigation</span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		      </button>
		      <a class="navbar-brand" href="#">测评系统</a>
		    </div>
		    <div class="collapse navbar-collapse" id="navbar-collapse">
			     <ul class="nav navbar-nav">
	            		
    <li class="active"><a href="javascript:;">安装协议</a></li>
    <li><a href="javascript:;">环境检测</a></li>
    <li><a href="javascript:;">创建数据库</a></li>
    <li><a href="javascript:;">安装</a></li>
    <li><a href="javascript:;">完成</a></li>

	            	</ul>
            </div>
        </div>

        <div class="jumbotron masthead">
            <div class="container">
                
    <h1>软件系统安装协议</h1>
    <p>版权所有 (c) 2015，保留所有权利。</p>
	
	<p>本软件遵循Apache Licence2开源协议，并且免费使用（但不包括其衍生产品、插件或者服务）。Apache Licence是著名的非盈利开源组织Apache采用的协议。该协议和BSD类似，鼓励代码共享和尊重原作者的著作权，允许代码修改，再作为开源或商业软件发布。需要满足的条件：<br/>
1． 需要给用户一份Apache Licence ；<br/>
2． 如果你修改了代码，需要在被修改的文件中说明；<br/>
3． 在延伸的代码中（修改和有源代码衍生的代码中）需要带有原来代码中的协议，商标，专利声明和其他原来作者规定需要包含的说明；<br/>
4． 如果再发布的产品中包含一个Notice文件，则在Notice文件中需要带有本协议内容。你可以在Notice中增加自己的许可，但不可以表现为对Apache Licence构成更改。</p>

            </div>
        </div>


        <!-- Footer
        ================================================== -->
        <footer class="footer navbar-fixed-bottom">
            <div class="container">
                <div>
                	
    <a class="btn btn-primary btn-large" href="<?php echo U('Install/step1');?>">同意安装协议</a>
    <a class="btn btn-large" href="http://www.itboye.com">不同意</a>
    {__NORUNTIME__}

                </div>
            </div>
        </footer>
    </body>
</html>