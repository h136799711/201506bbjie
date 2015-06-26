<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Admin\Controller;

class ShopController extends AdminController{
	
	/**
	 * 商城配置
	 */
	public function config(){
		if(IS_GET){
			$map = array('name'=>"WXPAY_OPENID");
			$result = apiCall("Admin/Config/getInfo", array($map));
			if($result['status']){
				$this->assign("wxpayopenid",	$result['info']['value']);
				$this->display();
			}
		}elseif(IS_POST){
			
			$openids = I('post.openids','');
			
			$config = array("WXPAY_OPENID"=>$openids);
			$result = apiCall("Admin/Config/set", array($config));
			if($result['status']){
				C('WXPAY_OPENID',$openids);
				
				$this->success(L('RESULT_SUCCESS'),U('Shop/config'));
			}else{
				if(is_null($result['info'])){
					$this->error("无更新！");
				}else{
					$this->error($result['info']);
				}
			}
			
		}
	}
	
	
	public function index(){
		$parent = I('parent',0);
		$preparent = I('preparent',-1);
		$level =  I('level',0);
		$map = array(
			'parent'=>$parent
		);
		$name = I('name','');
		$params = array(			
			'parent'=>$parent
		);
		
		if(!empty($name)){
			$map['name'] = array('like',"%$name%");
			$params['name'] = $name;
		}
		
		$result = apiCall("Admin/Category/getInfo", array(array('id'=>$parent)));
		if(!$result['status']){
			$this->error($result['info']);
		}
		$parent_vo = $result['info'];
		
		$result = apiCall("Admin/Category/getInfo", array(array('id'=>$preparent)));
		$prepreparent = "";
		if($result['status']){
			$prepreparent = $result['info']['parent'];
		}
		
		
		$page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
		
		$order = " id asc ";
		//
		$result = apiCall("Admin/Category/query",array($map,$page,$order,$params));
		
		//
		if($result['status']){
			$this->assign('level',$level);
			$this->assign('parent_vo',$parent_vo);
			$this->assign('prepreparent',$prepreparent);
			$this->assign('preparent',$preparent);
			$this->assign('parent',$parent);
			$this->assign('name',$name);
			/*$this->assign('show',$result['info']['show']);
			$this->assign('list',$result['info']['list']);*/
			$this->display();
		}else{
			LogRecord('INFO:'.$result['info'],'[FILE] '.__FILE__.' [LINE] '.__LINE__);
			$this->error(L('UNKNOWN_ERR'));
		}
	}
	
}
