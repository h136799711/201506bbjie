<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------


namespace Tool\Controller;

use Think\Controller;

class TaobaoController extends Controller{
	
	/**
	 * 读取商品页面信息
	 */
	public function read(){
		if(IS_POST){
			$url = I('post.url','','urldecode');
			$which = $this->whichUrl($url);
			
			switch($which){
				
				case 1:
					$return_info = $this->getTaobao($url);
					break;
				case 2:
					$return_info = $this->getTmall($url);
					break;
				default:
					$this->error("请输入正确的淘宝商品详情页地址!");
					break;
			}
			
			
			if(empty($return_info['title']) || empty($return_info['main_img']) || empty($return_info['wangwang']) )	{
				$this->error("无法识别此链接!");
			}
			
			$this->success($return_info);
		
			
		}else{
			$this->display();
		}
	}
	
	/**
	 * 判断是什么链接，淘宝？天猫？
	 * 检测规则
	 * 1. 域名 是否taobao.com|tmall.com
	 * 2. ...
	 * 3. ...
	 */
	private function whichUrl($url){
		//TODO: 是否为合法的淘宝商品详情页链接
		if(!(strpos($url, "tmall.com") === false)){
			return 2;
		}
		if(!(strpos($url, "taobao.com") === false)){
			return 1;
		}
		
		
		//
		return 0;
	}
	private function getTmall($url){
			$html = file_get_contents($url);
			$html = iconv("gb2312", "utf-8//IGNORE",$html); 
			$match = array();
//			dump($html);
			$return_info = array(
				'title'=>'',
				'main_img'=>'',
				'wangwang'=>'',
			);
			
			$wangwang_pattern = '/<li class="shopkeeper"(.*?)>(.*?)<a(.*?)>(.*?)<\/a>?/is';
			preg_match($wangwang_pattern, $html,$match);
			
//			var_dump($match);
			$return_info['wangwang'] = $match[4];
			
			
			
			$title_pattern = '/<meta name="keywords" content="(.*?)"(.*?)\/>/is';
//			dump($html);
			preg_match($title_pattern, $html,$match);
//			if($match){
				$return_info['title'] = $match[1];
			
			
			$mainimg_pattern = '/<img id="J_ImgBooth"(.*?)src="(.*?)"(.*?)>/is';
			
			preg_match($mainimg_pattern, $html,$match);
			$return_info['main_img'] = $match[2];
			
			
			return $return_info;
			
			
	}
	
	private function getTaobao($url){
		
			$html = file_get_contents($url);
			$html = iconv("gb2312", "utf-8//IGNORE",$html); 
//			var_dump($html);
			$match = array();
			
			$return_info = array(
				'title'=>'',
				'main_img'=>'',
				'wangwang'=>'',
			);
			$wangwang_pattern = '/<a class="tb-seller-name" (.*?)>(.*?)<\/a>/is';
			//echo $html;
			preg_match($wangwang_pattern, $html,$match);
			
			//var_dump($match[2]);
			$return_info['wangwang'] = $match[2];
			$mainimg_pattern = '/<img id="J_ImgBooth"(.*?)data-src="(.*?)"(.*?)>/is';
			
			preg_match($mainimg_pattern, $html,$match);
			$return_info['main_img'] = $match[2];
			
			
//			$title_pattern = '/<h3 class="tb-main-title" data-title="(.*?)"(.*?)>/is';
			$title_pattern = '/<meta name="keywords" content="(.*?)"(.*?)\/>/is';
//			dump($html);
			preg_match($title_pattern, $html,$match);
			
			$return_info['title'] = $match[1];
			
			
			return $return_info;
	}
}
