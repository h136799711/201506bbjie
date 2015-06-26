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
	 * 资金充值
	 * */
	 
	 public function sj_tbhd(){
	 	$headtitle="宝贝街-活动";
		$this->assign('head_title',$headtitle);
		$user=session('user');
		$this->assign('username',$user['info']['username']);
		$this->display();
	 }
	  public function rws(){
	 	$headtitle="宝贝街-任务书";
		$this->assign('head_title',$headtitle);
		$user=session('user');
		$this->assign('username',$user['info']['username']);
		$this->display();
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
	
}
