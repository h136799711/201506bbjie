<?php if (!defined('THINK_PATH')) exit();?><ol class="breadcrumb">
  <li><a href="<?php echo U('Admin/Index/index');?>"><i class="fa fa-home"></i><?php echo L('HOME_PAGE');?></a></li>
  <?php if(is_array($breadcrumb)): $i = 0; $__LIST__ = $breadcrumb;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo ($vo["url"]); ?>"><?php echo ($vo["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
</ol>
{__NORUNTIME__}