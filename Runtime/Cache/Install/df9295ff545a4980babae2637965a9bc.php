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
    <li class="active"><a href="javascript:;">环境检测</a></li>
    <li class="active"><a href="javascript:;">创建数据库</a></li>
    <li class="active"><a href="javascript:;">安装</a></li>
    <li><a href="javascript:;">完成</a></li>

	            	</ul>
            </div>
        </div>

        <div class="jumbotron masthead">
            <div class="container">
                
    <h1>安装</h1>
    <div id="show-list" class="install-database">
    </div>
    <script type="text/javascript">
        var list   = document.getElementById('show-list');
        function showmsg(msg, classname){
            var li = document.createElement('p'); 
            li.innerHTML = msg;
            classname && li.setAttribute('class', classname);
            list.appendChild(li);
            document.scrollTop += 30;
        }
    </script>

            </div>
        </div>


        <!-- Footer
        ================================================== -->
        <footer class="footer navbar-fixed-bottom">
            <div class="container">
                <div>
                	
    <button class="btn btn-warning btn-large disabled">正在安装，请稍后...</button>

                </div>
            </div>
        </footer>
    </body>
</html>