<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016 杭州博也网络科技, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------

namespace Weixin\Plugin;

/**
 * 未知关键词响应插件
 */
class UnknownPlugin extends  WeixinPlugin{
	
	/**
	 * @param $data 通常包含是微信服务器返回来的信息
	 * @return 返回 Wechat可处理的数组
	 */
	function process($data){
		addWeixinLog($data,'[UnknownPlugin]');
		
		if(empty($data['fans']) ){
			addWeixinLog("fans参数为empty", "[UnknownPlugin]".__LINE__);
			return array("Unknown插件[调用失败],缺少粉丝信息","text");
		}
		
		if(empty($data['wxaccount']) ){
			addWeixinLog("wxaccount参数为empty", "[UnknownPlugin]".__LINE__);
			return array("Unknown插件[调用失败],缺少公众号信息","text");
		}
		$content = $data['data']['Content'];
		
		return array("你说的是：".$content,"text");

	}

}
