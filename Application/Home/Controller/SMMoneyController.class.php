<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 嘟嘟 <99701759@qq.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Home\Controller;
use Think\Controller;
use Think\Storage;
use Home\Api\HomePublicApi;
/*
 * 资金提现
 */
class SMMoneyController extends HomeController {
	
	/*
	 * 资金提现
	 * */
	 public function deposit(){
	 	$money=I('money','0.000');
		$user=session('user_sm');
		$entity=array(
			'uid'=>$user['info']['id'],
			'defray'=>$money.'.000',
			'income'=>'0.000',
			'create_time'=>time(),
			'notes'=>'用于提现',
			'dtree_type'=>3,
			'status'=>2,
		);
		 $result = apiCall(HomePublicApi::FinAccountBalanceHis_Add,array($entity));
		 if($result['status']){
		 	$map=array('uid'=>$user['info']['id'],);
			 $id=$user['info']['id'];
			$rets = apiCall(HomePublicApi::Bbjmember_Query,array($map));
		 	$ap=array(
				'coins'=>$rets['info'][0]['coins']-$money,
			);
			$return = apiCall(HomePublicApi::Bbjmember_SaveByID,array($id,$ap));
			if($return['status']){
				$this->success('你的提现请求已经提交，正在审核...',U('Home/Usersm/sm_bbqz'));
			}
//		 	
		 }
	 }
	 
	
	 
}