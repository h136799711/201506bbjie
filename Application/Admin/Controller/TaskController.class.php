<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 青<99701759@qq.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Admin\Controller;
use Home\Api\HomePublicApi;
use Admin\Api\AdminPublicApi;

class TaskController extends AdminController{
	
	public function index(){
		$map=array('exchange_status'=>0);
		$re=apiCall(HomePublicApi::ExchangeProduct_Query,array($map));
		$results=apiCall(AdminPublicApi::Wxproduct_QueryNoPaging,array($maps));
		$result = apiCall(HomePublicApi::Member_Query, array());
//		dump($result);
$this->assign('users',$result['info']);
		$this->assign('exchange',$re['info']);
		$this->assign('product',$results['info']);
		$this->display();
	}
}