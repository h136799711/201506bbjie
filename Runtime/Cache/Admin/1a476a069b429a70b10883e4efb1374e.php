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
<link rel="stylesheet" type="text/css" href="/github/201506bbjie/Public/Admin/css/common.css?v=1433294566" />
<script type="text/javascript" src="/github/201506bbjie/Public/cdn/common/common.js?v=1433294566"></script>




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
	<!-- 过滤\查询按钮 -->
	<div class="filter-controls">
		<form action="<?php echo U('Admin/Member/index');?>" class="memberForm form-inline" method="post">
			<div class="form-group">
				<label class="control-label">用户昵称或ID</label>
				<div class="input-group">
						<input class="form-control" type="text" name="nickname" placeholder="请输入用户昵称或用户ID" />
				</div>
			</div>
			<div class="form-group">
				<label class="control-label">&nbsp;</label>
				<div class="input-group">
					<button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i>查询</button>
				</div>
			</div>
		</form>
	</div>
	<!-- 操作按钮 -->
	<div class="btn-controls">
		<a class="btn btn-sm btn-primary" href="<?php echo U('Admin/Member/add');?>"><i class="fa fa-plus"></i><?php echo L('BTN_ADD');?></a>
		<a target-form="selectitem" class="ajax-post btn btn-sm btn-primary" href="<?php echo U('Admin/Member/enable');?>"><i class="fa fa-check-circle"></i><?php echo L('BTN_ENABLE');?></a>
		<a target-form="selectitem" class="ajax-post btn btn-sm btn-primary" href="<?php echo U('Admin/Member/disable');?>"><i class="fa fa-minus-circle"></i><?php echo L('BTN_DISABLE');?></a>
	</div>

	<table class="table table-striped table table-hover  table-condensed">
		<thead>
			<tr>
				<th style="width:40px;">							
					<input type="checkbox" class="selectall" onclick="myUtils.selectall(this,'.selectitem');" /> 
				</th>
				<th>
					UID
				</th>
				<th>
					昵称
				</th>
				<th>
					积分
				</th>
				<th>
					登录次数
				</th>
				<th>
					最后登录时间
				</th>
				<th>
					最后登录IP
				</th>
				<th>
					状态
				</th>
				<th>
					<?php echo L('OPERATOR');?>
				</th>
			</tr>
		</thead>
		<tbody>
			<?php if(empty($list)): ?><tr>
					<td colspan="8" class="text-center"><?php echo L('NO_DATA');?></td>
				</tr>
				<?php else: ?>
				<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
						<td>
							<input type="checkbox" value="<?php echo ($vo["uid"]); ?>" name="uids[]" class="selectitem" /></td>
						
						<td>
							<?php echo ($vo["uid"]); ?></td>
						<td>
							<a href="<?php echo U('Admin/Member/view',array('id'=>$vo['uid']));?>"><?php echo ($vo["nickname"]); ?></a></td>
						<td>
							<?php echo ($vo["score"]); ?></td>
						<td>
							<?php echo ($vo["login"]); ?></td>
						<td>
							<?php echo (date('Y-m-d H:i:s',$vo["last_login_time"])); ?></td>
						<td>
							<?php echo (long2ip($vo["last_login_ip"])); ?></td>

						<td><?php echo (getStatus($vo["status"])); ?></td>
						<td>
							<a href="<?php echo U(CONTROLLER_NAME .'/disable',array('uid'=>$vo['uid']));?>" class="btn btn-danger btn-sm ajax-get" ><i class="fa fa-minus-circle"></i> <?php echo L('BTN_DISABLE');?></a>
							<a href="<?php echo U(CONTROLLER_NAME .'/delete',array('uid'=>$vo['uid']));?>" class="btn btn-danger btn-sm ajax-get confirm" ><i class="fa fa-trash-o"></i> <?php echo L('BTN_DELETE');?></a>
						</td>
					</tr><?php endforeach; endif; else: echo "" ;endif; endif; ?>
		</tbody>
	</table>
	<div><?php echo ($show); ?></div>
</div>
		</div>
		<!-- END admin-main-content -->
	</div>
		<!-- END admin-main-->

		


		<div class="admin-footer well text-center">
			<p><?php echo C('WEBSITE_OWNER');?> <a href="http://www.miitbeian.gov.cn"><?php echo C('WEBSITE_ICP');?></a></p>
			<p>&copy;2013-<?php echo date('Y');?> Version <?php echo C('APP_VERSION');?></p>
			<p>{__RUNTIME__}</p>
		</div>
		
	</body>

</html>