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
    <li><a href="javascript:;">安装</a></li>
    <li><a href="javascript:;">完成</a></li>

	            	</ul>
            </div>
        </div>

        <div class="jumbotron masthead">
            <div class="container">
                
    <?php
 defined('SAE_MYSQL_HOST_M') or define('SAE_MYSQL_HOST_M', '127.0.0.1'); defined('SAE_MYSQL_HOST_M') or define('SAE_MYSQL_PORT', '3306'); ?>
    <h1>创建数据库</h1>
    <form action="/github/201506bbjie/install.php/Install/step2.shtml" method="post" target="_self">
        <div class="create-database col-lg-12 col-md-12">
            <h2>数据库连接信息</h2>
            <div class="col-lg-12 col-md-12">
                <select class="form-control" name="db[]">
	                <option>mysql</option>
                </select>
                <span class="">数据库连接类型，暂支持为mysql</span>
            </div>
            <div  class="col-lg-12 col-md-12 ">
                <input class="form-control" type="text" name="db[]" value="<?php if(defined("SAE_MYSQL_HOST_M")): echo (SAE_MYSQL_HOST_M); else: ?>127.0.0.1<?php endif; ?>">
                <span >数据库服务器，数据库服务器IP，一般为127.0.0.1</span>
            </div>
            <div  class="col-lg-12 col-md-12">
                <input class="form-control"  type="text" name="db[]" value="<?php if(defined("SAE_MYSQL_DB")): echo (SAE_MYSQL_DB); else: ?>boye_<?php echo date('Y_m_d_H_i_s',time()); endif; ?>">
                <span>数据库名</span>
            </div>
            <div class="col-lg-12 col-md-12">
                <input class="form-control"  type="text" name="db[]" value="<?php if(defined("SAE_MYSQL_USER")): echo (SAE_MYSQL_USER); else: ?>root<?php endif; ?>">
                <span>数据库用户名</span>
            </div>
            <div class="col-lg-12 col-md-12">
                <input class="form-control"  type="password" name="db[]" value="<?php if(defined("SAE_MYSQL_PASS")): echo (SAE_MYSQL_PASS); else: ?>1<?php endif; ?>">
                <span>数据库密码</span>
            </div>

            <div class="col-lg-12 col-md-12">
                <input class="form-control"  type="text" name="db[]" value="<?php if(defined("SAE_MYSQL_PORT")): echo (SAE_MYSQL_PORT); else: ?>3306<?php endif; ?>">
                <span>数据库端口，数据库服务连接端口，一般为3306</span>
            </div>
			
            <div class="col-lg-12 col-md-12">
                <input class="form-control"  type="text" name="db[]" value="itboye_">
                <span>数据表前缀，同一个数据库运行多个系统时请修改为不同的前缀</span>
            </div>
        </div>
		
        <div class="create-database col-lg-12 col-md-12">
            <h2>创始人帐号信息</h2>
            <div class="col-lg-12 col-md-12">
                <input  class="form-control"   type="text" name="admin[]" value="itboye">
                <span class="">用户名</span>
            </div>
            <div class="col-lg-12 col-md-12">
                <input  class="form-control"  type="password" name="admin[]" value="1">
                <span>密码</span>
            </div>
            <div class="col-lg-12 col-md-12">
                <input  class="form-control"  type="password" name="admin[]" value="1">
                <span>确认密码</span>
            </div>
            <div class="col-lg-12 col-md-12">
                <input  class="form-control"  type="text" name="admin[]" value="12345@1634.com">
                <span>邮箱，请填写正确的邮箱便于收取提醒邮件</span>
            </div>
        </div>
        
    </form>

            </div>
        </div>


        <!-- Footer
        ================================================== -->
        <footer class="footer navbar-fixed-bottom">
            <div class="container">
                <div>
                	
    <a class="btn btn-success btn-large" href="<?php echo U('Install/step1');?>">上一步</a>
    <button id="submit" type="button" class="btn btn-primary btn-large" onclick="$('form').submit();return false;">下一步</button>

                </div>
            </div>
        </footer>
    </body>
</html>