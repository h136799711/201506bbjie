<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh">

	<head>
		<meta charset="utf-8">
		<title><?php echo ((isset($seo["title"]) && ($seo["title"] !== ""))?($seo["title"]):L('TITLE')); ?>-<?php echo C('WEBSITE_TITLE');?></title>
		<meta name="viewport" content="target-densitydpi=device-dpi,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<meta name="keywords" content="<?php echo ((isset($seo["keywords"]) && ($seo["keywords"] !== ""))?($seo["keywords"]):" "); ?>" />
		<meta name="description" content="<?php echo ((isset($seo["description"]) && ($seo["description"] !== ""))?($seo["description"]):" "); ?>" />
		<meta name="author" content="<?php echo ((isset($cfg["owner"]) && ($cfg["owner"] !== ""))?($cfg["owner"]):" itboye "); ?>" />
		<!-- 系统基本样式 -->
		<link type="text/css" rel="stylesheet" href="/github/201506bbjie/Public/cdn/bootstrap/3.3.0/css/bootstrap.min.css" />
		<link type="text/css" rel="stylesheet" href="/github/201506bbjie/Public/cdn/fontawesome/4.3.0/css/font-awesome.min.css?V=4.3.0" />
		<!-- 自定义主题 -->
		<link type="text/css" rel="stylesheet" href="/github/201506bbjie/Public/cdn/theme/<?php echo ((isset($cfg["theme"]) && ($cfg["theme"] !== ""))?($cfg["theme"]):"simplex"); ?>.min.css" />
		
		<!-- 脚本 -->
		<script type="text/javascript" src="/github/201506bbjie/Public/cdn/jquery/1.11.0/jquery.min.js"></script>
		<script type="text/javascript" src="/github/201506bbjie/Public/cdn/bootstrap/3.3.0/js/bootstrap.min.js"></script>

		
<!-- nprogress plugin -->
<link rel="stylesheet" type="text/css" href="/github/201506bbjie/Public/cdn/nprogress/nprogress.css" />
<script type="text/javascript" src="/github/201506bbjie/Public/cdn/nprogress/nprogress.js"></script>
<!-- scojs plugin -->
<link rel="stylesheet" type="text/css" href="/github/201506bbjie/Public/cdn/sco1.0.2-8/css/scojs.css" />
<script type="text/javascript" src="/github/201506bbjie/Public/cdn/sco1.0.2-8/js/sco.modal.js"></script>
<script type="text/javascript" src="/github/201506bbjie/Public/cdn/sco1.0.2-8/js/sco.confirm.js"></script>
<script type="text/javascript" src="/github/201506bbjie/Public/cdn/sco1.0.2-8/js/sco.message.js"></script>
<!-- 自定义模块通用样式 -->
<link rel="stylesheet" type="text/css" href="/github/201506bbjie/Public/Admin/css/common.css?v=1433291805" />
<script type="text/javascript" src="/github/201506bbjie/Public/cdn/common/common.js?v=1433291805"></script>




		<link type="text/css" rel="stylesheet" href="/github/201506bbjie/Public/cdn/theme/fix_<?php echo ((isset($cfg["theme"]) && ($cfg["theme"] !== ""))?($cfg["theme"]):"simplex"); ?>.css" />
		
		
	<link type="text/css" rel="stylesheet" href="/github/201506bbjie/Public/cdn/comp/login.css?c=1433291805" />
	<script src="/github/201506bbjie/Public/cdn/jquery-validation/1.13.1/jquery.validate.min.js"></script>
	<script src="/github/201506bbjie/Public/cdn/jquery-validation/1.13.1/localization/messages_zh.min.js"></script>
	<style type="text/css">
		img.verifyimg{
			cursor: pointer;
		}
	</style>
	
		<!--[if lt IE 9]>
		<script type="text/javascript" src="/github/201506bbjie/Public/cdn/respond/1.4.2/respond.min.js"></script>
		<![endif]--> 
		
	</head>

	<body class="theme-<?php echo ((isset($cfg["theme"]) && ($cfg["theme"] !== ""))?($cfg["theme"]):"simplex"); ?>">
		
		
	<!-- 登录框 -->
	<div class="login">
		<div class="modal">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title text-center login-status">
						登录
					</h4>
					</div>

					<div class="modal-body">
						<form action="" class="loginForm form clearfix validate-form" method="post">
							<div class="form-group form-group-sm">
								<div class="input-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<input type="text" class="required form-control  username" name="username" placeholder="<?php echo L('PLACEHOLDER_USERNAME');?>" />
									<i class="fa fa-user"></i>
								</div>
							</div>
							<div class="form-group form-group-sm">
								<div class="input-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<input type="password" class="form-control required password" name="password" placeholder="<?php echo L('PLACEHOLDER_PASSWORD');?>" /><i class="fa fa-lock"></i>
								</div>
							</div>
							<div class="form-group form-group-sm">
								<div class="input-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<input type="text" class="form-control required verify" name="verify" placeholder="<?php echo L('PLACEHOLDER_VERIFY');?>" /><i class="fa fa-barcode"></i>
								</div>
							</div>
							<div class="form-group form-group-sm ">
								<div class="input-group clearfix col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<img onclick="refresh_verify('.verifyimg');" src="<?php echo U('Admin/Public/verify',array('r'=>time()));?>" class="verifyimg"  alt="验证码" title="点击换一张"/>
								</div>
							</div>
							<div class="form-group form-group-sm">
								<button type="button" url="<?php echo U('Admin/Public/checkLogin');?>" target-form="loginForm" data-loading-text="<?php echo L('BTN_LOGIN');?>..." class="ajax-post btn btn-primary col-xs-12 col-sm-12 col-md-12 col-lg-12" autofocus="autofocus">
<?php echo L('BTN_LOGIN');?></button>
							</div>
						</form>

						<!--<div class="text-center">
							<button type="button" class="btn btn-link" onclick="myUtils.redirectTo('<?php echo U('Admin/Public/register');?>');"><?php echo L('BTN_REGISTER');?></button>
						</div>
						<div class="text-center">
							<button type="button" class="btn btn-link" onclick="myUtils.redirectTo('<?php echo U('Admin/Public/forgotPassword');?>');"><?php echo L('BTN_FORGET_PWD');?></button>
						</div>-->


					</div>
				</div>
			</div>
		</div>
	</div>

		
	<script>
		$(document).ajaxComplete(function(){ refresh_verify(".verifyimg"); });
		
		function refresh_verify(verify){
				verify = verify || ".verify";
				var verifySrc = $(verify).attr("src");
				if (verifySrc && verifySrc.indexOf('?') > 0) {
					$(verify).attr("src", verifySrc + '&r=' + Math.random());
				} else {
					$(verify).attr("src", verifySrc.replace(/\?.*$/, '') + '?' + Math.random());
				}

		}
		
		 $(document).keydown(function(event) {
			if (event.keyCode == 13) {
				$(".ajax-post").click();
			}
		});
	</script>

		<div class="admin-footer well text-center">
			<p><?php echo C('WEBSITE_OWNER');?> <a href="http://www.miitbeian.gov.cn"><?php echo C('WEBSITE_ICP');?></a></p>
			<p>&copy;2013-<?php echo date('Y');?> Version <?php echo C('APP_VERSION');?></p>
			<p>{__RUNTIME__}</p>
		</div>
		
	</body>

</html>