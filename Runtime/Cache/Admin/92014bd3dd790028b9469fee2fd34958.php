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
<link rel="stylesheet" type="text/css" href="/github/201506bbjie/Public/Admin/css/common.css?v=1433294965" />
<script type="text/javascript" src="/github/201506bbjie/Public/cdn/common/common.js?v=1433294965"></script>




		<link type="text/css" rel="stylesheet" href="/github/201506bbjie/Public/cdn/theme/fix_<?php echo ((isset($cfg["theme"]) && ($cfg["theme"] !== ""))?($cfg["theme"]):"simplex"); ?>.css" />
		
		
	<link src="/github/201506bbjie/Public/cdn/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet" type="text/css" />
	
	<link type="text/css" rel="stylesheet" href="/github/201506bbjie/Public/cdn/select2/4.0.0/css/select2.min.css" />
	<link type="text/css" rel="stylesheet" href="/github/201506bbjie/Public/cdn/select2/4.0.0/css/cosmo-skin.css" />
	
		<!--[if lt IE 9]>
		<script type="text/javascript" src="/github/201506bbjie/Public/cdn/respond/1.4.2/respond.min.js"></script>
		<![endif]--> 
		
	</head>

	<body class="theme-<?php echo ((isset($cfg["theme"]) && ($cfg["theme"] !== ""))?($cfg["theme"]):"simplex"); ?>">
		
		
	<?php echo W('Menus/topbar');?>
	<div class="admin-main container-fluid">
		<?php echo W('Menus/left');?>
		<div class="admin-main-content">
			<?php echo W('Menus/breadcrumb');?>
			<!-- form -->
			<form class="form-horizontal well validateForm">
				<fieldset>
					<legend>
						<?php if(isset($parentMenu)): echo ($parentMenu["title"]); ?>-<?php echo L('VIEW_SUBMENU');?>
							<?php else: echo L('MENU'); endif; ?>-<?php echo L('BTN_ADD');?></legend>
					<div class="form-group">
						<label for="inputtitle" class="col-md-2 col-lg-2 control-label"><?php echo L('TITLE');?></label>
						<div class="col-md-10 col-lg-10">
							<input type="text" class="required form-control input-short" name="title" id="inputtitle" placeholder="<?php echo L('PLACEHOLDER_TITLE');?>">
						</div>
					</div>
					<div class="form-group">
						<label for="inputtitle" class="col-md-2 col-lg-2 control-label"><?php echo L('VIEW_URL');?></label>
						<div class="col-md-10 col-lg-10">
							<input type="text" class="required form-control input-normal" name="url" id="inputtitle" placeholder="<?php echo L('PLACEHOLDER_URL');?>">
						</div>
					</div>
					<div class="form-group">
						<label for="inputpid" class="col-md-2 col-lg-2 control-label">所属上级菜单</label>
						<div class="col-md-10 col-lg-10">
							<select name="pid" class="form-control input-normal select2">
								<option value="0">顶级菜单</option>
								<?php if(is_array($menus)): $i = 0; $__LIST__ = $menus;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option <?php if(($pid) == $vo["id"]): ?>selected="selected"<?php endif; ?> value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["title_show"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="inputhide" class="col-md-2 col-lg-2 control-label"><?php echo L('VIEW_ISHIDE');?></label>
						<div class="col-md-10 col-lg-10 input-number">
							<div class="checkbox">
								<label>
									<input type="checkbox" class=" 
							 " id="inputhide" name="hide" value="0">
								</label>
							</div>

						</div>
					</div>
					<div class="form-group">
						<label for="inputis_dev" class="col-md-2 col-lg-2 control-label"><?php echo L('VIEW_ISDEV');?></label>
						<div class="col-md-10 col-lg-10 input-number">
							<div class="checkbox">
								<label>
									<input type="checkbox" class="
							 " id="inputis_dev" name="is_dev" value="0">
								</label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="inputtip" class="col-md-2 col-lg-2 control-label"><?php echo L('VIEW_TIP');?></label>
						<div class="col-md-10 col-lg-10 ">
							<textarea class="form-control input-normal" rows="3" name="tip"></textarea>

						</div>
					</div>
					<div class="form-group">
						<label for="inputsort" class="col-md-2 col-lg-2 control-label"><?php echo L('SORT');?></label>
						<div class="col-md-10 col-lg-10 input-number">
							<input type="text" class="required form-control
							 input-number" id="inputsort" name="sort" value="0">
						</div>
					</div>
					<div class="form-group">
						<label for="btns" class="col-md-2 col-lg-2 control-label">&nbsp;</label>
						<div class="col-lg-10 col-md-10">
							<a target-form="validateForm" class="ajax-post btn btn-primary" href="<?php echo U('Admin/Menu/add',array('pid'=>I('pid',0)));?>" autofocus="autofocus"><i class="fa fa-save"></i> <?php echo L('BTN_SAVE');?></a>
							<a class="btn btn-default" href="<?php echo U('Admin/Menu/index',array('pid'=>I('get.pid',0)));?>"><i class="fa fa-times-circle"></i> <?php echo L('BTN_CANCEL');?></a>
						</div>
					</div>
				</fieldset>
			</form>
			<!-- form -->
		</div>

	</div>

		
	
	<script src="/github/201506bbjie/Public/cdn/jquery-validation/1.13.1/jquery.validate.min.js"></script>
	<script src="/github/201506bbjie/Public/cdn/jquery-validation/1.13.1/localization/messages_zh.min.js"></script>
	<script src="/github/201506bbjie/Public/cdn/select2/4.0.0/js/select2.min.js"></script>
	<script src="/github/201506bbjie/Public/cdn/select2/4.0.0/js/i18n/zh-CN.js"></script>
	<script src="/github/201506bbjie/Public/cdn/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
	<script>
		$(function() {
			$("#inputsort").TouchSpin({
				initval: 0
			});
			$(".validateForm").validate();
			$(".select2").select2({
				language:"zh-CN"
			});
		});
	</script>

		<div class="admin-footer well text-center">
			<p><?php echo C('WEBSITE_OWNER');?> <a href="http://www.miitbeian.gov.cn"><?php echo C('WEBSITE_ICP');?></a></p>
			<p>&copy;2013-<?php echo date('Y');?> Version <?php echo C('APP_VERSION');?></p>
			<p>{__RUNTIME__}</p>
		</div>
		
	</body>

</html>