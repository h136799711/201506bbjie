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
			
			
			$map=array();
			$map["parent"]=0;
			$result=apiCall(AdminPublicApi::Category_QueryNoPaging, array($map));
			$this->assign('categoryParent',$result['info']);
			
			$map="parent!=0";
			$result=apiCall(AdminPublicApi::Category_QueryNoPaging, array($map));
			$this->assign('categoryChildrens',$result['info']);
			//公告
			$map = array();
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
			
			$result = apiCall('Admin/Banners/queryWithPosition', array($map, $page, $order, $params));
			
			$this -> assign('hotTypeList', $result['info']['list']);
			
			$map = array();
			$map = array(
				'g_id'=>34,
				'onshelf'=>0,
			);
			$page=array();
			$page = array('curpage' => I('get.p', 0), 'size' => 10);
			$order = " createtime desc ";
			
		/*	
			["id"] => string(2) "23"
        ["product_id"] => string(36) "AD493ED3-901D-4084-BE98-A0ABABEBDF24"
        ["name"] => string(17) "KT可爱喷水瓶"
        ["main_img"] => string(86) "http://localhost/github/201506bbjie/Uploads/WxshopPicture/2015-06-29/5590a6afe07f5.jpg"
        ["img"] => string(87) "http://localhost/github/201506bbjie/Uploads/WxshopPicture/2015-06-29/5590a6d535633.jpg,"
        ["buy_limit"] => string(1) "0"
        ["delivery_type"] => string(2) "-1"
        ["template_id"] => string(0) ""
        ["express_id"] => string(1) "0"
        ["express_price"] => string(3) "0.0"
        ["attrext_ispostfree"] => string(1) "1"
        ["attrext_ishasreceipt"] => string(1) "0"
        ["attrext_isunderguaranty"] => string(1) "0"
        ["attrext_issupportreplace"] => string(1) "0"
        ["loc_country"] => string(6) "中国"
        ["loc_province"] => string(9) "四川省"
        ["loc_city"] => string(9) "内江市"
        ["loc_address"] => string(9) "威远县"
        ["has_sku"] => string(1) "0"
        ["ori_price"] => string(6) "9900.0"
        ["price"] => string(6) "9900.0"
        ["icon_url"] => string(86) "http://localhost/github/201506bbjie/Uploads/WxshopPicture/2015-06-29/5590a6afe07f5.jpg"
        ["quantity"] => string(2) "20"
        ["product_code"] => string(8) "21356465"
        ["detail"] => string(0) ""
        ["createtime"] => string(10) "1435545365"
        ["updatetime"] => string(10) "1435543912"
        ["onshelf"] => string(1) "0"
        ["wxaccountid"] => string(1) "1"
        ["storeid"] => string(1) "0"
        ["properties"] => string(0) ""
        ["sku_info"] => string(0) ""
        ["cate_id"] => string(2) "12"
        ["start_sale_time"] => string(1) "0"
        ["g_id"] => string(2) "34"
        ["p_id"] => string(2) "17"
			*/
			$fields=array('itboye_wxproduct.id','name','main_img','icon_url','updatetime','p_id'); 
			
			
			$result = apiCall('Admin/Wxproduct/queryJoin', array($map, $page, $order, $params,$fields));
			$this -> assign('newProductList', $result['info']['list']); //上新预告
			
			//dump($result);
			
			$map = array(
				'g_id'=>35,
				'onshelf'=>1,
			);
			$result = apiCall('Admin/Wxproduct/queryJoin', array($map, $page, $order, $params,$fields));
			$this -> assign('recommendProductList', $result['info']['list']); //推荐福品
			//
			//dump($result);
			$headtitle="宝贝街-首页";
			$this->assign('head_title',$headtitle);
			$this->assign('indexTitle',0);
			$users=$_SESSION['Home']['user'];
			
			$id=$users['info']['id'];
			
			$map = array(
				'uid'=>$id,
			
			);
			$result=apiCall(HomePublicApi::Group_QueryNpPage, array($map));
			
			$this->assign('group',$result['info'][0]['group_id']);
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
		
		$id=$users['info']['id'];
			
		$map = array(
			'uid'=>$id,
		);
		$result=apiCall(HomePublicApi::Group_QueryNpPage, array($map));
			
		$this->assign('group',$result['info'][0]['group_id']);
		
		$map=array();
		$map["parent"]=0;
		$result=apiCall(AdminPublicApi::Category_QueryNoPaging, array($map));
		$this->assign('categoryParent',$result['info']);
		
		
		
		$cate_id=I("id");
		$product_name=I("searchterm");
		$minPrice=I("minPrice");
		$maxPrice=I("maxPrice");
		if($product_name==""&&$minPrice==""&&$maxPrice==""){
			if($cate_id=="0"||$cate_id==""){
				$map=array(
					//'id'=>0,
					'onshelf'=>1,
				);
			}else{
				$map=array(
					'id'=>$cate_id,
				);
				$result=apiCall(AdminPublicApi::Category_QueryNoPaging,array($map));
				if($result['info'][0]['level']==1){
					$map=array(
						'parent'=>$cate_id,
					);
					$result=apiCall(AdminPublicApi::Category_QueryNoPaging,array($map));
					$ids="";
					foreach($result['info'] as $v){
						$ids.=$v['id'].=',';
					}
					$ids=mb_substr($ids,0,strlen($ids)-1);
					$map = array();
					$map = array(
						'cate_id'=>array("in",$ids),
						'onshelf'=>1,
					);
					
				}else{
					$map = array();
					$map = array(
						'cate_id'=>$cate_id,
						'onshelf'=>1,
					);
			
				}
				
			}
			$this->assign('cpid',$cate_id);
		}else{
			if($product_name!=""){
				$map = array();
				$map = array(
					'name'=>array("like",'%'.$product_name.'%'),
					'onshelf'=>1,
				);
				$this->assign('searchterm',$product_name);
				
			}else{
				//$map = array();
				if($minPrice==""&&$maxPrice!=""){
					$map = array(
					'price'=>array("elt",$maxPrice*100),
					'onshelf'=>1,
					);
				}else if($minPrice!=""&&$maxPrice==""){
					$map = array(
						'price'=>array("egt",$minPrice*100),
						'onshelf'=>1,
					);
				}else{
					$map = array(
						'price'=>array("between",array($minPrice*100,$maxPrice*100)),
						'onshelf'=>1,
					);
				}
				
			}
			
		}
		
		
		
		$page=array();
		$page = array('curpage' => I('get.p', 0), 'size' => 20);
		$order = " createtime desc ";
		$result = apiCall('Admin/Wxproduct/query', array($map, $page, $order, $params));
		$this->assign('page',$result['info']['show']);
		$this->assign('productList',$result['info']['list']);
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
		$id=$users['info']['id'];
			
		$map = array(
			'uid'=>$id,
			//'onshelf'=>1,
		);
		$result=apiCall(HomePublicApi::Group_QueryNpPage, array($map));
		
		$this->assign('group',$result['info'][0]['group_id']);
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
		$id=$users['info']['id'];
			
		$map = array(
			'uid'=>$id,
			//'onshelf'=>1,
		);
		$result=apiCall(HomePublicApi::Group_QueryNpPage, array($map));
		
		//dump($result);
		$this->assign('group',$result['info'][0]['group_id']);
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
		
		
		
		$id=$users['info']['id'];
			
		$map = array(
			'uid'=>$id,
			//'onshelf'=>1,
		);
		$result=apiCall(HomePublicApi::Group_QueryNpPage, array($map));
		
		//dump($result);
		$this->assign('group',$result['info'][0]['group_id']);
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
		$detail['img']=explode(',',$detail['img']); //分割字符串成数组
		array_pop($detail['img']);//删除最后一个空元素
//		dump($detail);
		$this->assign('detail',$detail);
		$map=array();
		$map['product_id']=$id;
		$result = apiCall('Admin/WxproductSku/queryNoPaging',array($map));
		$skuList=$result[info];
		$skuInfo=array();
		foreach($skuList as $key=> $skus){
			$skuIds=explode(';',$skus['sku_id']);
			array_pop($skuIds);
			foreach($skuIds as $sku){
				
				$skuId=explode(':',$sku);
				$map=array();
				$map['id']=$skuId[0];
				$result1 = apiCall('Admin/Sku/queryNoPaging',array($map));
				$skuInfo[$result1['info'][0]['id']]['name']=$result1['info'][0]['name'];
				
				$map=array();
				$map['id']=$skuId[1];
				$result2 = apiCall('Admin/Skuvalue/queryNoPaging',array($map));
				
				$skus['sku']=$skus['sku'].';'.$result1['info'][0]['name'].':'.$result2['info'][0]['name'];
				$skuInfo[$result1['info'][0]['id']]['key']=$result1['info'][0]['id'];
				if(!in_array($result2['info'][0]['name'], $skuInfo[$result1['info'][0]['id']]['value'])){
					
					$skuInfo[$result1['info'][0]['id']]['value'][$result2['info'][0]['id']]=array(
						'key'=>$result2['info'][0]['id'],
						'value'=>$result2['info'][0]['name'],
					);
				}
				
			}
			$skuList[$key]=$skus;
		}
		$user=$_SESSION["Home"]['user'];
		$maps=array('uid'=>$user['info']['id']);
		$results=apiCall(HomePublicApi::Bbjmember_Query,array($maps));
		$this->assign('fubi',$results['info'][0]['fucoin']);
		
		$this->assign('skuInfo',$skuInfo);
		//dump($skuInfo);
		$this->assign(' ',$skuList);
		$headtitle="宝贝街-商品详情";
		$this->assign('head_title',$headtitle);
		$this->assign('username',$user['info']['username']);
		$id=$users['info']['id'];
			
		$map = array(
			'uid'=>$id,
			//'onshelf'=>1,
		);
		$result=apiCall(HomePublicApi::Group_QueryNpPage, array($map));
		
		//dump($result);
		$this->assign('group',$result['info'][0]['group_id']);
		
		$this->display();
	}
	
}

