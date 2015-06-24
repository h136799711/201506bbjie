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
class SJMoneyController extends HomeController {

	/*
	 * 资金充值
	 * */
	public function deposit() {
		$money = I('money', '0.000');
		$skzh = I('skzh', '');
		$jy_num = I('jy_num', '');
		$jypz = I('jypz', '');
		$user = session('user_sj');
		$entity = array('uid' => $user['info']['id'], 'income' => $money . '.000', 'defray' => '0.000', 'create_time' => time(), 'notes' => '用于充值', 'dtree_type' => 1, 'status' => 2, );
		$result = apiCall(HomePublicApi::FinAccountBalanceHis_Add, array($entity));
		if ($result['status']) {
			$map = array('uid' => $user['info']['id'], );
			$id = $user['info']['id'];
			$rets = apiCall(HomePublicApi::Bbjmember_Seller_Query, array($map));
			$ap = array('coins' => $rets['info'][0]['coins'] + $money, );
			$return = apiCall(HomePublicApi::Bbjmember_Seller_SaveByID, array($id, $ap));
			if ($return['status']) {
				$this -> success('你的充值请求已经提交，正在审核...', U('Home/Usersm/sm_bbqz'));
			}
			//
		}
	}

	public function vip() {
		$money = I('money', '');
		$user = session('user_sj');
		$map = array('uid' => $user['info']['id'], );
		$id = $user['info']['id'];
		$pwd = I('pwd', '');
		$result = apiCall(HomePublicApi::User_GetbyID, array($id));
		$password = $result['info']['password'];
		$pp = think_ucenter_md5($pwd, UC_AUTH_KEY);
		if ($password == $pp) {
			$rets = apiCall(HomePublicApi::Bbjmember_Seller_Query, array($map));
			$lv = I('lv', '1');
			$ap = array('coins' => $rets['info'][0]['coins'] - $money, 'vip_level' => $lv, );
			$return = apiCall(HomePublicApi::Bbjmember_Seller_SaveByID, array($id, $ap));
			if ($return['status']) {
				$entity = array('uid' => $user['info']['id'], 'defray' => $money . '.000', 'income' => '0.000', 'create_time' => time(), 'notes' => '用于开通会员', 'dtree_type' => 4, 'status' => 1, );
				$result = apiCall(HomePublicApi::FinAccountBalanceHis_Add, array($entity));
				if ($result['status']) {

					$this -> success('恭喜！你的服务已经成功开通...', U('Home/Usersj/index'));
				}

			}
		} else {
			$this -> error('密码错误  ，无法进行此操作');
		}

	}

	public function addbank() {
		$pwd = I('pwd', '');
		$user_sj = session('user_sj');
		$uid = $user_sj['info']['id'];
		//think_ucenter_md5($password, UC_AUTH_KEY)
		$result = apiCall(HomePublicApi::User_GetbyID, array($uid));
		$password = $result['info']['password'];
		$pp = think_ucenter_md5($pwd, UC_AUTH_KEY);
		if ($password == $pp) {
			$entity = array('uid' => $user_sj['info']['id'], 'bank_name' => I('bank', ''), 'bank_account' => I('bank_num', ''), 'create_time' => time(), 'status' => 0, 'notes' => '', 'cardholder' => I('name', ''), 'province' => I('sheng', ''), 'city' => I('shi', ''), );
			$map = array('uid' => $user_sm['info']['id'], );
			$info = apiCall(HomePublicApi::FinBankaccount_Query, array($map));
			if ($info['info'] == null) {
				$add = apiCall(HomePublicApi::FinBankaccount_Add, array($entity));
				$this -> success('绑定成功', U('Home/Usersj/sj_zjgl'));
			} else {
				$id = $info['info'][0]['id'];
				$update = apiCall(HomePublicApi::FinBankaccount_SaveByID, array($id, $entity));
				$this -> success('修改成功', U('Home/Usersj/sj_zjgl'));
			}
		} else {
			$this -> error('登录密码错误！', U('Home/Usersj/sj_zjgl'));
		}
	}

}
