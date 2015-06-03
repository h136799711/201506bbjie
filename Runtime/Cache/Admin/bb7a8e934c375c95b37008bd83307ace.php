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
<link rel="stylesheet" type="text/css" href="/github/201506bbjie/Public/Admin/css/common.css?v=1433297488" />
<script type="text/javascript" src="/github/201506bbjie/Public/cdn/common/common.js?v=1433297488"></script>




		<link type="text/css" rel="stylesheet" href="/github/201506bbjie/Public/cdn/theme/fix_<?php echo ((isset($cfg["theme"]) && ($cfg["theme"] !== ""))?($cfg["theme"]):"simplex"); ?>.css" />
		
		

	
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
			<div class="table-responsive well">
				<div class="h4">
					<?php if(isset($parentMenu)): ?><a href="<?php echo U('Admin/Menu/index',array('pid'=>$parentMenu['pid']));?>"><?php echo ($parentMenu["title"]); ?></a>-<?php echo L('VIEW_SUBMENU'); endif; ?>
				</div>
				<div class="controls">
					<a href="<?php echo U('Admin/Menu/add',array('pid'=>I('get.pid',0)));?>" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i><?php echo L('BTN_ADD');?></a>
					<?php if(isset($parentMenu)): ?><a href="<?php echo U('Admin/Menu/index',array('pid'=>$parentMenu['pid']));?>" class="btn btn-default btn-sm"><i class="fa fa-reply"></i><?php echo L('BTN_BACK');?></a><?php endif; ?>
				</div>
				<table class="table table-striped table table-hover  table-condensed">
					<thead>
						<tr>
							<th>
								<?php echo L('ID');?>
							</th>
							<th>
								<?php echo L('TITLE');?>
							</th>
							<th>
								<?php echo L('VIEW_ISHIDE');?>
							</th>
							<th>
								<?php echo L('SORT');?>
							</th>
							<th>
								<?php echo L('OPERATOR');?>
							</th>
						</tr>
					</thead>
					<tbody>
						<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
								<td><?php echo ($vo["id"]); ?></td>
								<td><a class="" href="<?php echo U('Admin/Menu/index',array('pid'=>$vo['id']));?>"><?php echo ($vo["title"]); ?></a></td>
								<td><?php echo yesorno($vo['hide']);?></td>
								<td><?php echo ($vo["sort"]); ?></td>
								<td>
									<a href="<?php echo U('Admin/Menu/view',array('id'=>$vo['id']));?>" class="btn btn-default btn-sm"><i class="fa fa-eye"></i><?php echo L('BTN_VIEW');?></a>
									<a href="<?php echo U('Admin/Menu/edit',array('id'=>$vo['id']));?>" class="btn btn-default btn-sm"><i class="fa fa-edit"></i> <?php echo L('BTN_EDIT');?></a>
									<a href="<?php echo U('Admin/Menu/delete',array('id'=>$vo['id'],'pid'=>$vo['pid']));?>" class="btn btn-danger btn-sm ajax-get confirm" ><i class="fa fa-trash-o"></i> <?php echo L('BTN_DELETE');?></a>
								</td>
							</tr><?php endforeach; endif; else: echo "" ;endif; ?>
					</tbody>
				</table>
				<div>
					<?php echo ($show); ?>
				</div>
			</div>
		</div>

	</div>

		


		<div class="admin-footer well text-center">
			<p><?php echo C('WEBSITE_OWNER');?> <a href="http://www.miitbeian.gov.cn"><?php echo C('WEBSITE_ICP');?></a></p>
			<p>&copy;2013-<?php echo date('Y');?> Version <?php echo C('APP_VERSION');?></p>
			<p>{__RUNTIME__}</p>
		</div>
		
	</body>

</html>