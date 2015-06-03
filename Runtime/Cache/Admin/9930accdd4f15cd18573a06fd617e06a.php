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
<link rel="stylesheet" type="text/css" href="/github/201506bbjie/Public/Admin/css/common.css?v=1433294785" />
<script type="text/javascript" src="/github/201506bbjie/Public/cdn/common/common.js?v=1433294785"></script>




		<link type="text/css" rel="stylesheet" href="/github/201506bbjie/Public/cdn/theme/fix_<?php echo ((isset($cfg["theme"]) && ($cfg["theme"] !== ""))?($cfg["theme"]):"simplex"); ?>.css" />
		
		

	<link rel="stylesheet" type="text/css" media="all" href="/github/201506bbjie/Public/cdn/jquery-datetimepicker/jquery.datetimepicker.css">
	<script type="text/javascript" src="/github/201506bbjie/Public/cdn/jquery-datetimepicker/jquery.datetimepicker.js"></script>
	
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
			<!-- 过滤\查询按钮 -->
			<div class="filter-controls">
				<!--<a class="btn btn-sm btn-primary" href="<?php echo U('Admin/Wxaccount/add');?>"><i class="fa fa-plus"></i>添加</a>-->
			</div>
			<table class="table table-striped table table-hover  table-condensed">
				<thead>
					<tr>
						<th>
							<input type="checkbox" class="selectall" onclick="myUtils.selectall(this,'.selectitem');" />
						</th>
						<th>
							公众号名称
						</th>
						<th>
							头像
						</th>
						<th>
							Token
						</th>
						<th>
							添加时间
						</th>
						<th>
							操作
						</th>
					</tr>
				</thead>
				<tbody>
					<?php if(empty($list)): ?><tr>
							<td colspan="6" class="text-center"><?php echo L('NO_DATA');?></td>
						</tr>
						<?php else: ?>
						<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
								<td>
									<input type="checkbox" value="<?php echo ($vo["id"]); ?>" name="ids[]" class="selectitem" />
								</td>
								<td>
									<a href="<?php echo U('Admin/Wxaccount/edit',array('id'=>$vo['id']));?>"><?php echo ($vo["wxname"]); ?></a>
								</td>
								<td>
									<a href="<?php echo ($vo["headerpic"]); ?>" target="_blank" title="点击查看原图" data-toggle="tooltip" ><img class="avatar" style="max-width:45px;" src="<?php echo ($vo["headerpic"]); ?>" alt="头像" /></a>
								</td>
								<td>
									<?php echo ($vo["token"]); ?>
								</td>
								<td>
									<?php echo (date('Y-m-d h:i:s',$vo["createtime"])); ?>
								</td>
								<td>
									<!--<a href="<?php echo U('Admin/Wxaccount/set',array('id'=>$vo['id']));?>" class="btn btn-sm btn-primary"><i class="fa fa-weixin"></i>设为当前公众号</a>-->
									<a href="<?php echo U('Admin/Wxaccount/edit',array('id'=>$vo['id']));?>" class="btn btn-sm btn-default"><i class="fa fa-edit"></i>编辑</a>
									<!--<a href="<?php echo U('Admin/Wxaccount/delete',array('id'=>$vo['id']));?>" class="btn btn-sm btn-danger"><i class="fa fa-trash-o"></i>删除</a>-->
								</td>
							</tr><?php endforeach; endif; else: echo "" ;endif; endif; ?>
				</tbody>
			</table>
			<div><?php echo ($show); ?></div>
		</div>

		<script type="text/javascript">
			$(function() {
				$('#startdatetime').datetimepicker({
					lang: 'ch',
					format: 'Y-m-d',
					timepicker: false,
				});
				$('#enddatetime').datetimepicker({
					lang: 'ch',
					format: 'Y-m-d',
					timepicker: false,
				});
			});
		</script>
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