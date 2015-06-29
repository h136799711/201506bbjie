<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Shop\Controller;
use Home\Api\HomePublicApi;
use Admin\Api\AdminPublicApi;

class IndexController extends ShopController{
	
	protected function _initialize(){
		//dump("ddd");
		//parent::_initialize();
		
		if(isMobile()){
			//手机访问
			//dump("ddd");
			$showStartPage = true;
			$last_entry_time = cookie("last_entry_time");
			if(empty($last_entry_time)){
				//一小时过期
				cookie("last_entry_time",time(),3600);
				$last_entry_time = time();			
			}elseif(time() - $last_entry_time < 20*60){
				$showStartPage = false;
			}else{
				//一小时过期
				cookie("last_entry_time",time(),3600);
			}
			
			$this->assign("showstartpage",$showStartPage);
		
			
		}else{
			//电脑端访问
			
			
			
		}
		
		
		
	}
	
	/**
	 * 首页
	 */
	public function index(){
		if(isMobile()){
			//dump("dddd");
			$map = array('uid'=>$this->wxaccount['uid'],'storeid'=>-1,'position'=>C("DATATREE.SHOP_INDEX_BANNERS"));
			
			$page = array('curpage'=>0,'size'=>8);
			$order = "createtime desc";
			$params = false;
			
			$result = apiCall("Shop/Banners/query",array($map,$page,$order,$params));
	//		dump($result);
			if(!$result['status']){
				$this->error($result['info']);
			}
			
			$this->assign("banners",$result['info']['list']);
			
			$map= array('parentid'=>C("DATATREE.STORE_TYPE"));
			$result = apiCall("Admin/Datatree/query",array($map,$page,$order,$params));
			
			if(!$result['status']){
				$this->error($result['info']);
			}
			$this->assign("store_types",$result['info']['list']);
			
			// 获取推荐商品
			$result = $this->getProducts();
			if($result['status'] && is_array($result['info'])){
				$this->assign("recommend_products",$result['info']['list']);
			}
			
			$ads  = $this->getAds();
			
			$this->assign("ads",$ads['info']['list']);
			
			//获取推荐店铺
			$result = $this->getRecommendStore();
			
			$this->assign("rec_stores",$result['info']['list']);
			
			//获取首页4格活动
			$result = $this->getFourGrid();
	//		
			$this->assign("fourgrid",$result['info']['list']);
	//		
			$this->display();
		}else{
			//电脑端访问
			
			$order = " post_modified desc ";
			$result = apiCall(AdminPublicApi::Post_QueryNoPaging,array($map, $order));
			$this->assign('zxgg',$result['info'][0]);
			
			
			//dump($result['info'][0]);
			$map=array();
			$map["parent"]=0;
			$result=apiCall(AdminPublicApi::Category_Query, array($map));
			$this->assign('categoryParent',$result['info']);
			
			$map="parent!=0";
			$result=apiCall(AdminPublicApi::Category_Query, array($map));
			$this->assign('categoryChildrens',$result['info']);
			//公告
			$map = array();
			//$map = array('uid'=>34);
			//dump(UID);
			$map['position'] = 18;
		
			$page = array('curpage' => I('get.p', 0), 'size' => 4);
			$order = " createtime desc ";
			//
			$result = apiCall('Admin/Banners/queryWithPosition', array($map, $page, $order, $params));
			//
			$this -> assign('lunboList', $result['info']['list']);
			
			
			
			
			$map = array();
			$map['position'] = 20;
			$page=array();
			$page = array('curpage' => I('get.p', 0), 'size' => 3);
			$order = " createtime desc ";
			//
			$result = apiCall('Admin/Banners/queryWithPosition', array($map, $page, $order, $params));
			//
			$this -> assign('hotTypeList', $result['info']['list']);
			
			$map = array();
			$map = array(
				'g_id'=>34,
			);
			$page=array();
			$page = array('curpage' => I('get.p', 0), 'size' => 10);
			$order = " createtime desc ";
		//
			$result = apiCall('Admin/Wxproduct/queryJoin', array($map, $page, $order, $params));
			
			
			//recommend
			$this -> assign('newProductList', $result['info']['list']); //上新预告
			
			
			$map = array(
				'g_id'=>35,
			);
			$result = apiCall('Admin/Wxproduct/queryJoin', array($map, $page, $order, $params));
			$this -> assign('recommendProductList', $result['info']['list']); //推荐福品
			//dump($result);
		
		
			$headtitle="宝贝街-首页";
			$this->assign('head_title',$headtitle);
			$this->assign('indexTitle',0);
			$users=$_SESSION['Home']['user'];
			$this->assign('username',$users['info']['username']);
			$this->display();
		}
	}

	
	//========PC端使用
	
	
	
	//========移动端使用
	/**
	 * 获取首页4格活动
	 * 
	 */
	 private function getFourGrid(){
		$page = array('curpage'=>0,'size'=>4);
	 	$map = array('parentid'=>getDatatree("INDEX_4_ACTIVTIY"));
		$order = " sort desc";
		$result = apiCall("Admin/Datatree/query", array($map,$page,$order));
	
		if(!$result['status']){
			$this->error($result['info']);
		}
		
		return $result;
	 }
	
	/**
	 * 广告
	 */
	private function getAds(){
		
		$page = array('curpage'=>0,'size'=>2);
		$map = array('position'=>getDatatree("SHOP_INDEX_ADVERT"));
		$result = apiCall("Admin/Banners/query", array($map,$page));
		if(!$result['status']){
			$this->error($result['info']);
		}
		
		return $result;
	}
	/**
	 * 推荐店铺
	 */
	private function getRecommendStore(){
		
		$page = array('curpage'=>0,'size'=>4);
		$map = array('position'=>getDatatree("SHOP_INDEX_RECOMMEND_STORE"));
		$result = apiCall("Admin/Banners/query", array($map));
		if(!$result['status']){
			$this->error($result['info']);
		}
		
		return $result;
	}
	
	/** 
	 *  
	 */ 
	public function getProducts(){
		
		$page = array('curpage'=>0,'size'=>10);
		$order = "updatetime desc";
		$map = array('onshelf'=>\Common\Model\WxproductModel::STATUS_ONSHELF);
		$group_id = getDatatree("WXPRODUCTGROUP_RECOMMEND");
		
		$result = apiCall("Shop/Wxproduct/queryByGroup", array($group_id,$map));
		if(!$result['status']){
			LogRecord($result['info'], __FILE__.__LINE__);	
		}
		
		return $result;
	}
	
	
	
	/*
	  * 福品专场
	  * */
	public function flzc(){
		$this->assign('indexTitle',1);
		$order = " post_modified desc ";
		$result = apiCall(AdminPublicApi::Post_QueryNoPaging,array($map, $order));
		$this->assign('zxgg',$result['info'][0]);
		
		$headtitle="宝贝街-福品专场";
		$this->assign('head_title',$headtitle);
		$users=$_SESSION["Home"]['user'];
		$this->assign('username',$users['info']['username']);
		$this->display();
	}
	/*
	  * 幸福一点
	  * */
	public function xfyd(){
		$this->assign('indexTitle',2);
		$order = " post_modified desc ";
		$result = apiCall(AdminPublicApi::Post_QueryNoPaging,array($map, $order));
		$this->assign('zxgg',$result['info'][0]);
		
		$headtitle="宝贝街-幸福一点";
		$this->assign('head_title',$headtitle);
		$users=$_SESSION["Home"]['user'];
		$this->assign('username',$users['info']['username']);
		$this->display();
	}
	/*
	  * 试江湖
	  * */
	public function sjh(){
		$this->assign('indexTitle',3);
		
		$order = " post_modified desc ";
		$result = apiCall(AdminPublicApi::Post_QueryNoPaging,array($map, $order));
		$this->assign('zxgg',$result['info'][0]);
		
		
		$headtitle="宝贝街-试江湖";
		$this->assign('head_title',$headtitle);
		$users=$_SESSION["Home"]['user'];
		$this->assign('username',$users['info']['username']);
		$this->display();
	}
	/*
	  * 茶话馆
	  * */
	public function chg(){
		$this->assign('indexTitle',4);
		
		$order = " post_modified desc ";
		$result = apiCall(AdminPublicApi::Post_QueryNoPaging,array($map, $order));
		$this->assign('zxgg',$result['info'][0]);
		
		
		$headtitle="宝贝街-茶话馆";
		$this->assign('head_title',$headtitle);
		$users=$_SESSION["Home"]['user'];
		$this->assign('username',$users['info']['username']);
		$this->display();
	}

	/*
	  * 商品详情
	  * */
	public function spxq(){
		//查询最新通知
		$order = " post_modified desc ";
		$result = apiCall(AdminPublicApi::Post_QueryNoPaging,array($map, $order));
		$this->assign('zxgg',$result['info'][0]);
		
		
		$id=I("id");
		//dump($id);
		$map['id']=$id;
		$result = apiCall('Admin/Wxproduct/queryNoPaging',array($map));
		$detail=$result['info'][0];
		//dump($detail['img']);
		$detail['img']=explode(',',$detail['img']); //分割字符串成数组
		array_pop($detail['img']);//删除最后一个空元素
		//dump($detail);
		$this->assign('detail',$detail);
		
		//dump($id);
		$map=array();
		$map['product_id']=44;
		$result = apiCall('Admin/WxproductSku/queryNoPaging',array($map));
		//dump($result);
		
		$headtitle="宝贝街-商品详情";
		$this->assign('head_title',$headtitle);
		$users=$_SESSION["Home"]['user'];
		$this->assign('username',$users['info']['username']);
		$this->display();
	}
	
}

