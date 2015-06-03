<?php if (!defined('THINK_PATH')) exit();?><div class="admin-sidebar">
	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

		<?php if(is_array($left_menu)): $i = 0; $__LIST__ = $left_menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(isset($vo['dynamic_hide'])): else: ?>
			<!-- 单个块 START  -->
			<div class="panel panel-default">
				<div class="panel-heading">
				<?php if(empty($vo['url']) or $vo['url'] = '#'): ?><a class="panel-title" href="#collapse<?php echo $vo['id'];?>" data-toggle="collapse" data-parent="#accordion">
					<?php echo ($vo["title"]); ?><i class="fa fa-angle-right"></i>	</a>
				</div>
				<div id="collapse<?php echo $vo['id'];?>" class="panel-collapse collapse in">
					<div class="panel-body">
						<ul class="list-group">
							<?php if(is_array($vo['children'])): $i = 0; $__LIST__ = $vo['children'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$child): $mod = ($i % 2 );++$i; if(isset($child['dynamic_hide'])): else: ?>
								<li class="list-group-item <?php echo isActiveSubMenuURL($child['id']);?>"><a href="<?php echo getURL($child['url'],'activesubmenuid='.$child['id']);?>"><?php echo ($child["title"]); ?></a>
								</li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
						</ul>
					</div>
				</div>
				<?php else: ?>
				<a class="panel-title" href="<?php echo (getURL($vo["url"])); ?>" data-toggle="collapse" data-parent="#accordion"><?php echo ($vo["title"]); ?></a>
				</div><?php endif; ?>			
			</div>
			<!-- 单个块 END  --><?php endif; endforeach; endif; else: echo "" ;endif; ?>

</div>

</div>

{__NORUNTIME__}