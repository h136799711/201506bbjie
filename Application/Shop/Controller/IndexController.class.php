<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Shop\Controller;
use Cms\Api\PostApi;
use Common\Api\AccountApi;
use Home\Api\FinFucoinHisApi;
use Home\Api\HomePublicApi;
use Admin\Api\AdminPublicApi;
use Home\Api\ProductExchangeApi;
use Home\Model\FinFucoinHisModel;
use Home\Model\ProductExchangeModel;

class IndexController extends ShopController{
	
	protected function _initialize(){
        parent::_initialize();
        $this->getLastestPost();
	}
	
	/**
	 * 首页
     * @author 老胖子-何必都 <hebiduhebi@126.com>
	 */
	public function index(){


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
            'onshelf'=>1,
        );
        $page=array();
        $page = array('curpage' => I('get.p', 0), 'size' => 10);
        $order = " createtime desc ";


        $fields=array('itboye_wxproduct.id','name','main_img','icon_url','updatetime','p_id','price');


        $result = apiCall('Admin/Wxproduct/queryJoin', array($map, $page, $order, $params,$fields));
        $this -> assign('newProductList', $result['info']['list']); //上新预告

        //dump($result);

        $map = array(
            'g_id'=>35,
            'onshelf'=>1,
        );

        $result = apiCall('Admin/Wxproduct/queryJoin', array($map, $page, $order, $params,$fields));
        $this -> assign('recommendProductList', $result['info']['list']); //推荐福品

        $this->assign('head_title',"宝贝街-首页");
        $this->assign('indexTitle',0);



        $this->display();

	}

    public function exchange(){
        $this->checkLogin();
        $this->reloadUserinfo();
        $pid = I('get.pid',0);

        $result = apiCall('Admin/Wxproduct/getInfo',array(array('id'=>$pid)));

        if(!$result['status']){
            $this->error($result['info']);
        }

        if(is_null($result['info'])){
            $this->error("商品信息获取失败!");
        }
        $product = $result['info'];
        $price = number_format($product['price']/100.0,0,".","");
        $fucoin = $this->userinfo['fucoin'];
        if($fucoin < $price){
            $this->error("您的余额不足!");
        }

        $entity = array(
            'create_time'=>time(),
            'update_time'=>time(),
            'p_id'=>$pid,
            'uid'=>$this->userinfo['id'],
            'sku_notes'=>'',
            'exchange_status'=>ProductExchangeModel::WAIT_CHECK,
        );

        $api = new ProductExchangeApi();

        $result = $api->add($entity);


        if($result['status']){
            $api = new FinFucoinHisApi();
            $notes = "申请兑换了\"".$product['name'].'",减少了 '.$price." ".VIRTUAL_CURRENCY;
            $left_fucoin = $fucoin - $price;
            $api->minus($this->userinfo['id'],$price,$left_fucoin,FinFucoinHisModel::MINUS_EXCHANGE,$notes);
            $this->success("申请成功，请等候审核");
        }else{
            $this->error($result['info']);
        }




    }

	
	//========PC端使用
	
	
	
	//========移动端使用

	
	
	
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
	  * 商品详情
	  * */
	public function spxq(){
        $this->assign('head_title',"宝贝街-商品详情");

		$id = I("get.id");
		$map['id'] = $id;
		$result = apiCall('Admin/Wxproduct/getInfo',array($map));
		$detail = $result['info'];
		$detail['img'] = explode(',',$detail['img']); //分割字符串成数组
		array_pop($detail['img']);//删除最后一个空元素

		$this->assign('detail',$detail);
		$map = array();
		$map['product_id']=$id;

		$this->assign('fubi',$this->userinfo['fucoin']);
		
		$this->display();
	}

    private function checkLogin(){
        if(!is_array($this->userinfo)){
            $this->error("请登录后操作!");
        }
    }

    private function reloadUserinfo(){
        $result = apiCall(AccountApi::GET_INFO,array($this->userinfo['id']));
        if($result['status']){
            $this->userinfo = $result['info'];
            session('user',$this->userinfo);
        }
    }

    private function getLastestPost(){
        $map = array('post_status'=>'publish');
        $order = " post_modified desc ";
        $result = apiCall(PostApi::GET_INFO,array($map, $order));
        $this->assign('zxgg',$result['info']);
    }
	
}

