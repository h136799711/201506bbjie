<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>首页</title>
		<link rel="stylesheet" href="/github/201506bbjie/Public/Home/css/amazeui.min.css" />
		<link rel="stylesheet" href="/github/201506bbjie/Public/Home/css/main.css" />
		<script type="text/javascript" src="/github/201506bbjie/Public/Home/js/jquery.min.js"></script>
		<script type="text/javascript" src="/github/201506bbjie/Public/Home/js/main.js"></script>
	</head>
	<body >
		<div class="main">
			
		<div class="down">
			
			<div class="logo">
				<div class="top_sc">
					<div class="top_sc_cen">
						<div class="top_sc_lf">
							<a href=""  onclick="">设为首页</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=""  onclick="">收藏本站</a>
						</div>
					    <div class="top_r">
					      <a href="<?php echo U('Home/Index/login');?>">登录</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					      <a href="">注册</>
					    </div>
				    </div>
				</div>
					
			</div>
			<div class="menu">
				<div class="menu_cen">
					
						
						 	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#">评测系统</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						 	<div class="logo_img">
								<img src="/github/201506bbjie/Public/Home/imgs/logo.png">			
							</div>
				   		 <a href="#">
				   		 	首页</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				   		 	<?php if(is_array($cates)): $i = 0; $__LIST__ = $cates;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="<?php echo U('Home/Index/cate',array('cateid'=>$vo['id']));?>">
										<?php echo ($vo["name"]); ?>
								</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php endforeach; endif; else: echo "" ;endif; ?>
				  	
					<div class="sele">
						<form action="<?php echo U('Home/Index/search');?>" method="post">
							<div class="field" id="searchform">
								  <input type="text" name="text" id="searchterm" placeholder="请输入.." />
								  <button type="submit" id="search">Find!</button>
							</div>
						</form>
					</div>
				  <!--<button class="am-topbar-btn am-topbar-toggle am-btn am-btn-sm am-btn-success am-show-sm-only" data-am-collapse="{target: '#doc-topbar-collapse'}"><span class="am-sr-only">导航切换</span> <span class="am-icon-bars"></span></button>-->
				</div>
				
				    
				  
				
			
			</div>
			<div class="show" >
				<div class="show_cent">
					<div class="show_centl"> <img src="/github/201506bbjie/Public/Home/imgs/1.jpg"><div class="c1">学习好不好,一测便知道</div></div>
					<div class="show_r">
						<div class="show_cent2"><img src="/github/201506bbjie/Public/Home/imgs/2.jpg"><a href=""><div class="c2">学习好不好,一测便知道 </div></a></div>
						<div class="show_cent3"><img src="/github/201506bbjie/Public/Home/imgs/3.jpg"><a href=""><div class="c3">学习好不好,一测便知道</div></a></div>
						<div class="show_cent4"><img src="/github/201506bbjie/Public/Home/imgs/4.jpg"><a href=""><div class="c4">学习好不好,一测便知道</div></a></div>
						<div class="show_cent5"><img src="/github/201506bbjie/Public/Home/imgs/5.jpg"><a href=""><div class="c5">学习好不好,一测便知道</div></a></div>
					</div>
				</div>
				
			</div>
			<div class="bo">
		<?php if(is_array($cates)): $i = 0; $__LIST__ = array_slice($cates,0,2,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($i % 2 );++$i;?><div class="bottom">
				<div class="bottom_news">
					<img src="/github/201506bbjie/Public/Home/imgs/wzlb.png" /><?php echo ($vo2["name"]); ?>
				</div>
				<div class="bottom_news_tx">
					<img src="/github/201506bbjie/Public/Home/imgs/stu.jpg">
					<div class="bottom_ct_tx_tx">
						<img src="/github/201506bbjie/Public/Home/imgs/zxlg.png" />
						<div class="bottom_tx_right">
							<p><a href="">MBTI职业性格测试</a></p>
							
						</div>
						<div class="bottom_bt">
							
						MBTI职业性格测试作为一种对个性的判断和分析，是一个理论模型，从纷繁复杂的个性特征中，归纳提炼出4个关键要素——动力、信息收集、决策方式、生活方式，进行分析判断，从而把不同个性的人区别开来。
						
						</div>
					</div>
				</div>
				<div class="bottom_right" >
					
					<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo2['id'] == $vo['post_category'] ): ?><div class="r1">
								<div class="r1_tx" >
								 <img src="<?php echo ($vo["main_img"]); ?>"/>
								<div>
									<span class="r1_t1"><a href="<?php echo U('Home/Index/view',array('id'=>$vo['id']));?>"><?php echo ($vo["post_title"]); ?></a></span><br>
									<span class="r1_t2"><?php echo (date('Y-m-d H:i:s',$vo["post_date"])); ?> 作者：<?php if(is_array($users)): $i = 0; $__LIST__ = $users;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$user): $mod = ($i % 2 );++$i; if($vo['post_author'] == $user['uid']): echo ($user["realname"]); endif; endforeach; endif; else: echo "" ;endif; ?></span><br>
									<span class="r1_t3"><?php echo ($vo["post_excerpt"]); ?></span><br>
									</div>
								</div>
						</div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
				
				</div>
				
			</div><?php endforeach; endif; else: echo "" ;endif; ?>
			<div class="tt">
				
					<a href="http://www.itboye.com/">&copy;杭州博也网络科技有限公司版权所有</a><br>
					{__RUNTIME__}
				</div>
			
			</div>
		</div>
	</body>
</html>