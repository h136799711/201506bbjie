<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 青<99701759@qq.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Admin\Controller;
use Home\Api\HomePublicApi;

class MoneyInfoController extends AdminController{
	
	public function index(){
		$resultsm=apiCall(HomePublicApi::Bbjmember_Query, array());
		$resultsj=apiCall(HomePublicApi::Bbjmember_Seller_Query, array());
		$page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
		$i=0;
		foreach($resultsm['info'] as $a){
			
			$new[$i]['id']=$a['uid'];
			$new[$i]['coins']=$a['coins'];
			$new[$i]['auth_status']=$a['auth_status'];
			$i++;
		}
		foreach($resultsj['info'] as $b){
			$new[$i]['id']=$b['uid'];
			$new[$i]['coins']=$b['coins'];
			$new[$i]['auth_status']=$b['auth_status'];
			$i++;
		}
		$this->assign('new',$new);
		$result1=apiCall(HomePublicApi::UcenterUser_QueryAll, array($maps,$page));
		$this->assign('user',$result1['info']['list']);
		$this->assign('show',$result1['info']['show']);
//		dump($new);
		$this->display();
	}	
	/*
	 * 账户资金变动记录
	 * */
	public function jiluinfo(){
		$id=array('uid'=>I('id',0));
		$page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
		$result=apiCall(HomePublicApi::FinAccountBalanceHis_QueryAll, array($id,$page));
		$result1=apiCall(HomePublicApi::UcenterUser_Query, array($maps));
		$this->assign('jilu',$result['info']['list']);
		$this->assign('user',$result1['info']);
		$this->assign('show',$result['info']['show']);
		$this->display();
	}
}
