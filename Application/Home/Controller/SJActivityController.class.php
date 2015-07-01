<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 青 <99701759@qq.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Home\Controller;
use Think\Controller;
use Think\Storage;
use Home\Api\HomePublicApi;
/*
 * 资金提现
 */
class SJActivityController extends HomeController {

	
	 /*
	  * 淘宝活动
	  * */
	 public function sj_tbhd(){
	 	$headtitle="宝贝街-活动";
		$this->assign('head_title',$headtitle);
		$user=session('user');
		$this->assign('username',$user['info']['username']);
		$this->display();
	 }
	 /*
	  * 任务书
	  * */
	  public function rws(){
	 	$headtitle="宝贝街-任务书";
		$this->assign('head_title',$headtitle);
		$user=session('user');
		$this->assign('username',$user['info']['username']);
		$this->display();
	 }
	  /*
	  * 创建任务第一步
	  * */
	  public function activity_1(){
	  	$headtitle="宝贝街-创建任务";
		$this->assign('head_title',$headtitle);
		$user=session('user');
		$this->assign('username',$user['info']['username']);
		$this->display();
	  }
	  /*
	  * 创建任务第二步
	  * */
	  public function activity_2(){
	  	$headtitle="宝贝街-创建任务";
		$this->assign('head_title',$headtitle);
		$user=session('user');
		$this->assign('username',$user['info']['username']);
		$this->display();
	  }
	  /*
	  * 创建任务第三步
	  * */
	  public function activity_3(){
	  	$headtitle="宝贝街-创建任务";
		$this->assign('head_title',$headtitle);
		$user=session('user');
		$this->assign('username',$user['info']['username']);
		$this->display();
	  }
	  /*
	   * 商品管理
	   * */
	  public function productmanager(){
	  	$headtitle="宝贝街-商品管理";
		$this->assign('head_title',$headtitle);
		$user=session('user');
		$map=array('uid'=>$user['info']['id'],'is_on_sale'=>1);
		$mwe=array('uid'=>$user['info']['id'],'is_on_sale'=>0,);
		$sj=apiCall(HomePublicApi::Bbjmember_Seller_Query, array($map));
		$pro=apiCall(HomePublicApi::Product_QueryAll, array($map));
		$prduct=apiCall(HomePublicApi::Product_QueryAll, array($mwe));
		$this->assign('downpro',$prduct['info']['list']);
		$this->assign('showdown',$product['show']);
		$this->assign('showall',$pro['show']);
		$this->assign('products',$pro['info']['list']);
		$this->assign('aliwawa',$sj['info'][0]['aliwawa']);
		$this->assign('username',$user['info']['username']);
	  	$this->display();
	  }
	  /*
	   * 商品下架
	   * */
	   public function downpro(){
	   	   $id=I('id',0);
		   $map=array('is_on_sale'=>0,);
		   $result=apiCall(HomePublicApi::Product_SaveByID, array($id,$map));
		   if ($result['status']) {
				$this -> success('更新成功', U('Home/SJActivity/productmanager'));
			}else{
				$this -> error($result['info']);
			}
	   }
	   /*
	    * 更新商品
	    * */
	    public function productedit(){
	    	$user=session('user');
	    	$id=I('id','');
			$mapa=array('id'=>$id,);
			if(IS_GET){
				$pro=apiCall(HomePublicApi::Product_Query, array($mapa));
				$this->assign('id',$pro['info'][0]['id']);
				$this->assign('product',$pro['info'][0]);
				$map=array('uid'=>$user['info']['id'],'is_on_sale'=>1);
				$mwe=array('uid'=>$user['info']['id'],'is_on_sale'=>0,);
				$sj=apiCall(HomePublicApi::Bbjmember_Seller_Query, array($map));
				$pro=apiCall(HomePublicApi::Product_QueryAll, array($map));
				$prduct=apiCall(HomePublicApi::Product_QueryAll, array($mwe));
				$this->assign('downpro',$prduct['info']['list']);
				$this->assign('showdown',$product['show']);
				$this->assign('showall',$pro['show']);
				$this->assign('products',$pro['info']['list']);
				$this->assign('aliwawa',$sj['info'][0]['aliwawa']);
				$this->assign('username',$user['info']['username']);
//				dump($pro['info'][0]);
				$this->display('productmanager');
			}else{
				$entity=array(
				'price'=>I('price',''),
				'position'=>trim(I('weizhi','')),
				'update_time'=>time(),
				);
				
//				dump($id);
				//dump($entity);
				$result=apiCall(HomePublicApi::Product_SaveByID, array($id,$entity));
				if ($result['status']) {
					$this -> success('更新成功', U('Home/SJActivity/productmanager'));
				}else{
					$this -> error($result['info']);
				}
			}
			
	    }
		/*
		 * 商品搜索管理
		 * TODO:新增搜索
		 * */
		public function productsele(){
		$headtitle="宝贝街-商品搜索管理";
		$this->assign('head_title',$headtitle);
		$user=session('user');
		$map=array('uid'=>$user['info']['id'],'status'=>1);
		$mwe=array('uid'=>$user['info']['id'],'status'=>0,);
		$product=apiCall(HomePublicApi::Product_QueryAll, array($map));
		$pro=apiCall(HomePublicApi::Product_QueryAll, array($mapp));
		$prduct=apiCall(HomePublicApi::Product_QueryAll, array($mwe));
		$this->assign('prduct',$prduct['info']['list']);
		$this->assign('prshow',$prduct['show']);
		$this->assign('product',$product['info']['list']);
		$this->assign('prooshow',$product['show']);
		$this->assign('proshow',$pro['show']);
		$this->assign('pro',$pro['info']['list']);
		$this->assign('username',$user['info']['username']);
//		dump($prduct);
		$this->display();
		}
	   /*
	    * 商品上架
	    * */
	    public function uppro(){
	   	   $id=I('id',0);
		   $map=array('is_on_sale'=>1,);
		   $result=apiCall(HomePublicApi::Product_SaveByID, array($id,$map));
		   if ($result['status']) {
				$this -> success('更新成功', U('Home/SJActivity/productmanager'));
			}else{
				$this -> error($result['info']);
			}
	   }
		/*
		 * 商品删除
		 * */
		 public function delpro(){
	   	   $id=I('id',0);
		   $map=array('id'=>$id,);
		   $result=apiCall(HomePublicApi::Product_Del, array($map));
		   if ($result['status']) {
				$this -> success('删除成功', U('Home/SJActivity/productmanager'));
			}else{
				$this -> error($result['info']);
			}
	   }
	  /*
	   * 获取商品信息
	   * */
	   public function addproduct(){
	   		$user=session('user');
			$gaoji=array(
				'xinghao1'=>I('xinghao1',''),
				'price1'=>I('price1',''),
				'xinghao2'=>I('xinghao2',''),
				'price2'=>I('price2',''),
				'xinghao3'=>I('xinghao3',''),
				'price3'=>I('price3',''),
			);
			$a=serialize($gaoji);
			if($gaoji['xinghao1']=='' && $gaoji['xinghao2']=='' && $gaoji['xinghao3']==''){
					$entity=array(
		   			'uid'=>$user['info']['id'],
					'link'=>I('post.url',''),
					'price'=>I('post.price',''),
					'has_model_num'=>0,
					'position'=>trim(I('post.weizhi','')),
					'title'=>I('title',''),
					'main_img'=>I('img',''),
					'wangwang'=>I('alww',''),
					'create_time'=>time(),
					'update_time'=>time(),
					'status'=>1,
					'model_num_cfg'=>'',
					'is_on_sale'=>1,
					
				);
				$result=apiCall(HomePublicApi::Product_Add, array($entity));
				if ($result['status']) {
					$this -> success('商品添加成功', U('Home/SJActivity/productmanager'));
				}else{
					$this -> error($result['info']);
				}
			}else{
					$entity=array(
		   			'uid'=>$user['info']['id'],
					'link'=>I('post.url',''),
					'price'=>I('post.price',''),
					'has_model_num'=>1,
					'position'=>trim(I('post.weizhi','')),
					'title'=>I('title',''),
					'main_img'=>I('img',''),
					'wangwang'=>I('alww',''),
					'create_time'=>time(),
					'update_time'=>time(),
					'status'=>1,
					'model_num_cfg'=>$a,
					'is_on_sale'=>1,
					
				);
				$result=apiCall(HomePublicApi::Product_Add, array($entity));
				if ($result['status']) {
					$this -> success('商品添加成功', U('Home/SJActivity/productmanager'));
				}else{
					$this -> error($result['info']);
				}
			}
	   		
//		dump($entity);
	   }
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
//	public function () {
//		$money = I('money', '0.000');
//		$skzh = I('skzh', '');
//		$jy_num = I('jy_num', '');
//		$jypz = I('jypz', '');
//		$user = session('user');
//		$entity = array('uid' => $user['info']['id'], 'income' => '000', 'defray' => $money . '.000', 'create_time' => time(), 'notes' => '用于提现', 'dtree_type' => 3, 'status' => 2, );
//		$result = apiCall(HomePublicApi::FinAccountBalanceHis_Add, array($entity));
//		if ($result['status']) {
//			$map = array('uid' => $user['info']['id'], );
//			$id = $user['info']['id'];
//			$rets = apiCall(HomePublicApi::Bbjmember_Seller_Query, array($map));
//			$ap = array('coins' => $rets['info'][0]['coins'] - $money, );
//			$return = apiCall(HomePublicApi::Bbjmember_Seller_SaveByID, array($id, $ap));
//			if ($return['status']) {
//				$this -> success('你的充值请求已经提交，正在审核...', U('Home/Usersj/sj_zjgl'));
//			}
//			//
//		}
//	}

	//创建搜索
	public function createsearch(){
		$headtitle="宝贝街-创建搜索";
		$this->assign('head_title',$headtitle);
		$user=session('user');
		$product=apiCall(HomePublicApi::Product_QueryAll, array($map));
		$this->assign('prduct',$prduct['info']['list']);
		$this->assign('username',$user['info']['username']);
		//dump($product);
		$this->display();
	}
	
}
