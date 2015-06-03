<?php if (!defined('THINK_PATH')) exit();?><form action="<?php echo U('Admin/Config/set');?>" method="post" class="configSetForm col-lg-12 form-horizontal">
	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$config): $mod = ($i % 2 );++$i;?><div class="form-group">
			<label class="control-label"><?php echo ($config["title"]); ?></label>
			<span class="help-block">（<?php echo ($config["remark"]); ?>）</span> 
			<div class="controls">
				<?php switch($config["type"]): case "0": ?><input type="text" class="form-control input-short" name="config[<?php echo ($config["name"]); ?>]" value="<?php echo ($config["value"]); ?>"><?php break;?>
					<?php case "1": ?><input type="text" class="form-control input-normal" name="config[<?php echo ($config["name"]); ?>]" value="<?php echo ($config["value"]); ?>"><?php break;?>
					<?php case "2": ?><textarea class="form-control" rows="5" name="config[<?php echo ($config["name"]); ?>]"><?php echo ($config["value"]); ?></textarea><?php break;?>
					<?php case "3": ?><label class="textarea input-large">
							<textarea class="form-control" rows="5" name="config[<?php echo ($config["name"]); ?>]"><?php echo ($config["value"]); ?></textarea>
						</label><?php break;?>
					<?php case "4": ?><select class="form-control" name="config[<?php echo ($config["name"]); ?>]">
							<?php $_result=parse_config_attr($config['extra']);if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>" <?php if(($config["value"]) == $key): ?>selected<?php endif; ?>><?php echo ($vo); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
						</select><?php break;?>
					<!-- 图片地址 -->
					<?php case "5": ?><link type="text/css" rel="stylesheet" href="/github/201506bbjie/Public/cdn/comp/wxuploader.css?v=1433294790" />
						<link type="text/css" rel="stylesheet" href="/github/201506bbjie/Public/cdn/jquery-uploadify/3.2.1/uploadify.css" />
						<script type="text/javascript" src="/github/201506bbjie/Public/cdn/jquery-uploadify/3.2.1/jquery.uploadify.min.js"></script>
						<input type="hidden"  name="config[<?php echo ($config["name"]); ?>]" id="img_<?php echo ($config["name"]); ?>" value="<?php echo ($config["value"]); ?>" />
						
						<!-- 图片选择DOM结构 -->
						<div data-id="img_<?php echo ($config["name"]); ?>" class="wxuploaderimg clearfix <?php if(!empty($config["value"])): ?>checked<?php endif; ?> " data-maxitems="1">
							<div class="img-preview clearfix" >
								<?php if(!empty($config["value"])): ?><div class="pull-left clearfix img-item">
											<img src="<?php echo ($config["value"]); ?>"  />
											<div class="edit_pic_wrp"><a href="javascript:;" class="fa fa-lg fa-trash js_delete"></a></div>
										</div><?php endif; ?>
							</div>
							<div class="add">
								<i class="fa fa-plus"></i>
							</div>
						</div>
						
						<!-- Modal -->
<div class="modal " id="wxuploadimg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">选择图片</h4>
			</div>
			<div class="modal-body clearfix">
				<div class="col-lg-12 col-md-12 form-inline">
						<input type="text" name="q"  class="form-control" placeholder="输入文件名查找" />
						
						<button class="btn btn-sm btn-primary js_search" type="button" ><i class="fa fa-search"></i>查找</button>	
				</div>
				<div class="col-lg-12 col-md-12">
					
					<div class="btns pull-right">
						<a href="javascript:void(0);" id="upload_picture"><i class="fa fa-upload"></i>本地上传</a>
					</div>
					<div class="imgs-container pull-left">
						<div class="loading">
							<img src="/github/201506bbjie/Public/cdn/common/loading.gif" />
						</div>
						<div class="imgs-list clearfix"></div>
						<div class="pager"></div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="pull-left">已选<span class="js_checked"></span>张,可选<span class="js_total"></span>张</div>
				<button type="button" class="btn btn-primary js_check_img"><i class="fa fa-check"></i>确定</button>
				<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i>取消</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

	window.wxuploadimg = (function() {
		var pager = {
			index: 0,
			size: 10, //页面数
		};
		var checked = 0;
		var hadBind = false;
		/**
		 * 将数据组合成HTML
		 */
		function appendImgList(list) {
			if (list) {
				$cont = $("#wxuploadimg .imgs-list").empty();
				for (var i = 0; i < list.length; i++) {
					var imgsrc = list[i].imgurl;
					if(!imgsrc){
						imgsrc = '<?php echo C("SITE_URL");?>'+list[i].path;
					}
					$item = $("<div class='img-item '><img class='img-responsive  thumbnail js_img_click' src='" + imgsrc  + "'/><p class='img-desc'>"+list[i].ori_name+"</p></div>");
					$cont.append($item);
					
				}
			}else{
				$("#wxuploadimg .imgs-list").html("没有相关图片信息!");
			}
		}
		
	/**
	 * 处理分页点击事件
	 */
	function pagerClick(){
		
		$("#wxuploadimg .imgs-container").click(function(ev){
//			console.log(ev);
			$target = $(ev.target);
			if($target.hasClass("img-selected")){
				$target.removeClass("img-selected");
				checked--;
				$("#wxuploadimg .js_checked").text(parseInt($("#wxuploadimg .js_checked").text())-1);
			}
			if($target.hasClass("js_img_click")){
				if(checked == window.wxuploadimg.setting.MaxChecked){					
					var len = $(".img-preview img",window.wxuploadimg.current).length;
					$.scojs_message('最多可选'+(wxuploadimg.setting.MaxChecked-len)+'张!', $.scojs_message.TYPE_OK);
				}
				
				if(checked < window.wxuploadimg.setting.MaxChecked){
					$target.parent().addClass("img-selected");
					checked++;
					$("#wxuploadimg .js_checked").text(parseInt($("#wxuploadimg .js_checked").text())+1);
				}
			}
//			console.log($target);
				ev.preventDefault();
			if($target.hasClass("num")){
				pager.index = parseInt($target.text());
				queryImgList();
				ev.preventDefault();
			}else if($target.hasClass("next")){
				pager.index = pager.index+1;
				queryImgList();		
				ev.preventDefault();
			}else if($target.hasClass("prev")){
				pager.index = pager.index-1;		
				if(pager.index <0 ){
					pager.index = 0;
				}
				queryImgList();
				ev.preventDefault();
			}
			
			});
		}
		/**
		 * 向服务器请求数据
		 */
		function queryImgList() {
			var q = $("#wxuploadimg input[name='q']").val();
			$.ajax({
				type: "post",
				url: "<?php echo U('Admin/File/picturelist');?>?p="+wxuploadimg.pager.index,
				data: {
					q:q,
					size: wxuploadimg.pager.size
				},
				dataType: "json",
				beforeSend: function() {
					$("#wxuploadimg .imgs-container .loading").removeClass("hidden");
				}
			}).done(function(data) {
				if (data.status) {
					var info = data.info;
					var list = info.list;
					var show = info.show;
					appendImgList(list);
					$("#wxuploadimg .imgs-container .pager").html(show);
				}
			}).always(function() {
				$("#wxuploadimg .imgs-container .loading").addClass("hidden");
			});
		}
		
		function clearSelected(){			
			$("#wxuploadimg .img-item.img-selected").removeClass("img-selected");			
		}
		/**
		 * callback
		 * @param {Object} cont 触发模态框的选择器
		 * @param {Object} callback 选中图片后的触发器
		 */
		function init(setting){
//			console.log(hadBind);
			if(setting.callback){
				wxuploadimg.callBack = setting.callback;
			}
			wxuploadimg.setting = $.extend({},wxuploadimg.setting, setting);
			pager.size = setting.size || pager.size;
			//上传按钮点击处理
			$(".add",wxuploadimg.setting.cont).each(function(index,item){
				$(item).click(function(ev){
//					console.log(this);
					$ele = $(this);
//					if($ele.hasClass('add')){
						window.wxuploadimg.current =  $ele.parent();
						open($(window.wxuploadimg.current).attr("data-maxitems"));					
						clearSelected();
//					}
				});	
			});
			queryImgList();
			if(!hadBind){
				//使用此标志来防止 当调用多次init方法来初始化时，#wxuploadimg绑定了多次click监听器
				pagerClick();
				//选中图片
				$("#wxuploadimg .js_check_img").click(function(){
					
					window.wxuploadimg.setting.callback = wxuploadimg.setting.callback || callback;
					
					window.wxuploadimg.setting.callback.apply(this,$("#wxuploadimg .img-selected img"));
					
					if(checked == wxuploadimg.setting.MaxChecked){
						$(".add",window.wxuploadimg.current).hide();
					}
					$('#wxuploadimg').modal("hide");
					
				});
				// 预览图片
				$(".img-preview",window.wxuploadimg.current).click(function(ev){
					window.wxuploadimg.current = $(ev.target).parents(".wxuploaderimg");
					if($(ev.target).hasClass("js_delete")){
	//					console.log($(ev.target));
						$(ev.target,window.wxuploadimg.current).parents(".img-item").remove();
						var len = $(".img-preview img",window.wxuploadimg.current).length;
//						console.log($(".img-preview img",window.wxuploadimg.current));
//						console.log(len);
						//已全部选择
						if(len == 0){
							$(".img-preview",window.wxuploadimg.current).hide();
							$(window.wxuploadimg.current).removeClass("checked");
							$(".add",window.wxuploadimg.current).show();
						}
						
						//还剩余
						if(len < wxuploadimg.setting.MaxChecked){
							$(".add",window.wxuploadimg.current).show();
						}
						
					}
					ev.preventDefault();
					ev.stopPropagation();
				})
				
				//查找
				$(".js_search").click(function(){
					queryImgList();
				})
			}
			hadBind = true;
		}
		
		function open(maxchecked){
			checked = $(".img-preview img",wxuploadimg.current).length ;
			wxuploadimg.setting.MaxChecked = maxchecked || wxuploadimg.setting.MaxChecked;
			$("#wxuploadimg .js_checked").text(0);
			$("#wxuploadimg .js_total").text(wxuploadimg.setting.MaxChecked - checked);
			$('#wxuploadimg').modal("show");
		}
		function callback(){
			var data = arguments;
			for(var i=0;i<data.length;i++){
				var $ele = $('<div class="pull-left clearfix img-item"><div class="edit_pic_wrp"><a href="javascript:;" class="fa fa-lg fa-trash js_delete"></a></div></div>');
				$(".img-preview",wxuploadimg.current).append($ele).css("display","inline-block");//.show();
				$ele.prepend($(data[i]).clone());
				
			}
			
		}
		return {
			setting: {
				MaxChecked:1,//最多可选图片数
				size: 10, //每页图片数					
				callback:null //默认回调函数
			},
			current:null,
			pager: pager,
			appendImgList:appendImgList,
			pagerClick:pagerClick,
			queryImgList:queryImgList,
			init:init,
		};
		
	})();
	
		//上传图片
		/* 初始化上传插件  */
	$("#wxuploadimg #upload_picture").uploadify({
		'buttonClass': 'btn btn-primary btn-sm',
		"height": "30px",
		"swf": "/github/201506bbjie/Public/cdn/jquery-uploadify/3.2.1/uploadify.swf",
		"fileObjName": "wxshop", //wxshop
		"buttonText": "<i class='fa fa-upload'></i>本地上传",
		"uploader": "<?php echo U('Admin/File/uploadWxshopPicture',array('session_id'=>session_id()));?>",
		"width": 120,
		'removeTimeout': 1,
		'fileTypeExts': '*.jpg; *.png; *.gif;*.jpeg',
		"onUploadSuccess": uploadPicture
	});

	function uploadPicture(file, data) {
		var data = $.parseJSON(data);
		var src = '';
		if (data.status) {
			var imgsrc = data.imgurl;
			if(!imgsrc){
				imgsrc = '<?php echo C("SITE_URL");?>'+data.path;
			}
			$item = $("<div class='img-item '><img class='img-responsive  thumbnail js_img_click' src='" + imgsrc + "'/><p class='img-desc'>"+data.ori_name+"</p></div>");
			$(".imgs-list").prepend($item);
			
		} else {
			$.scojs_message(data.info, $.scojs_message.TYPE_OK);
		}
	}
	$(function(){			
		var init = 'true';
		if(init == 'true'){
			wxuploadimg.init({cont:".wxuploaderimg"});
		}
	})
</script>
						<script type="text/javascript">
							function config_set_get_data(){
								$(".wxuploaderimg").each(function(index,item){
//									console.log(item);
									var data_id = $(item).attr("data-id");
//									console.log(data_id);
									$("#"+data_id).val($("img",item).attr("src"));
								});
								
								return false;
							}
							$(function(){
								
							})
						</script>
								<!-- 图片选择DOM结构 --><?php break; endswitch;?>

			</div>
		</div><?php endforeach; endif; else: echo "" ;endif; ?>
	<div class="form-item">
		<label class="item-label"></label>
		<div class="controls">
			<?php if(empty($list)): else: ?>
				<button type="submit" onclick="return config_set_get_data();" class="btn btn-primary submit-btn ajax-post" target-form="configSetForm"><?php echo L('BTN_SAVE');?></button><?php endif; ?>
		</div>
	</div>
</form>
{__NORUNTIME__}