<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Home\Controller;
use Think\Controller;
use Think\Storage;
use Home\Api\HomePublicApi;
/*
 * 官网首页
 */
class UsersmController extends HomeController {

	public function index() {
		$user = session('user_sm');
		$this -> assign('username', $user['info']['username']);
		$userid = $user['info']['id'];
		//		dump($userid);
		$map = array('uid' => $userid, );
		$result = $result = apiCall(HomePublicApi::Member_Query, array($map));
		$results = apiCall(HomePublicApi::Bbjmember_Query, array($map));
		$this -> assign('info', $results['info']);
		$this -> assign('mum', $result['info']);
		$this -> display('manager_info');
	}

	public function manager_rw() {
		$user_sm = session('user_sm');
		$this -> assign('username', $user_sm['info']['username']);
		$headtitle = "试民中心-任务";
		$this -> assign('head_title', $headtitle);
		$this -> display();
	}

	public function sm_bbqz() {
		$headtitle = "宝贝街-宝贝钱庄";
		$this -> assign('head_title', $headtitle);
		$user_sm = session('user_sm');
		$this -> assign('username', $user_sm['info']['username']);
		$this -> display();
	}

	public function sm_ydsp() {
		$headtitle = "宝贝街-预定商品";
		$this -> assign('head_title', $headtitle);
		$user_sm = session('user_sm');
		$this -> assign('username', $user_sm['info']['username']);
		$this -> display();
	}

	public function sm_schd() {
		$headtitle = "宝贝街-收藏活动";
		$this -> assign('head_title', $headtitle);
		$user_sm = session('user_sm');
		$this -> assign('username', $user_sm['info']['username']);
		$this -> display();
	}

	public function sm_dhsp() {
		$headtitle = "宝贝街-兑换商品";
		$this -> assign('head_title', $headtitle);
		$user_sm = session('user_sm');
		$this -> assign('username', $user_sm['info']['username']);
		$this -> display();
	}

	public function sm_bbhd() {
		$headtitle = "宝贝街-宝贝活动";
		$this -> assign('head_title', $headtitle);
		$user_sm = session('user_sm');
		$this -> assign('username', $user_sm['info']['username']);
		$this -> display();
	}

	public function sm_aqzx() {
		$headtitle = "宝贝街-宝贝活动";
		$this -> assign('head_title', $headtitle);
		$user_sm = session('user_sm');
		$this -> assign('username', $user_sm['info']['username']);
		$this -> assign('phone', $user_sm['info']['mobile']);
		$this -> assign('email', $user_sm['info']['email']);
		$this -> display();
	}

	public function sm_xfyd() {
		$headtitle = "宝贝街-幸福一点";
		$this -> assign('head_title', $headtitle);
		$user_sm = session('user_sm');
		$this -> assign('username', $user_sm['info']['username']);
		$this -> display();
	}

	public function sm_xzgl() {
		$headtitle = "宝贝街-勋章管理";
		$this -> assign('head_title', $headtitle);
		$user_sm = session('user_sm');
		$this -> assign('username', $user_sm['info']['username']);
		$this -> display();
	}

	public function sm_znxx() {
		$headtitle = "宝贝街-站内消息";
		$this -> assign('head_title', $headtitle);
		$user_sm = session('user_sm');
		$this -> assign('username', $user_sm['info']['username']);
		$this -> display();
	}

	public function address() {
		$user = session('user_sm');
		if (IS_GET) {
			$uid = $user['info']['id'];
			$map = array('uid' => $uid, );
			$result = apiCall(HomePublicApi::Address_Query, array($map));
			$this -> assign('address', $result['info']);
			$this -> display('manager_address');
		} else {
			$ars = array('uid' => $user['info']['id'], 
						'country' => "中国", 'province' => I('sheng'), 
						'city' => I('shi'), 'area' => I('qu'), 
						'detail' => I('address', ''), 'contact_name' => I('name', ''),
						 'mobile' => I('mobile', ''), 'telphone' => I('phone', ''),
						  'post_code' => I('yb', ''), 'create_time' => time(), );
			$result = apiCall(HomePublicApi::Address_Add, array($ars));

			if ($result['status']) {
				$this -> success("操作成功！", U('Home/Usersm/address'));
			}
		}

	}

	public function add() {
		$user = session('user_sm');
		$id = $user['info']['id'];
		$year = I('year', 0);
		$month = I('month', 0);
		$day = I('day', 0);
		$bir = $year . '-' . $month . '-' . $day;
		//		dump($bir);
		$sm = array('birthday' => $bir, 'sex' => I('sex', 0), 'qq' => I('qq', '1'), 'realname' => I('realname', ''), );
		$sheng = I('sheng');
		$shi = I('shi');
		$qu = I('qu');
		$smm = array('dtree_job' => I('zhiye', ''), 'personal_signature' => I('grqm', ''), 'brief_introduction' => I('grjj', ''), 'address' => $sheng . $shi . $qu . I('address', ''), );
		//		dump($smm);
		//		dump($smm);
		$result = apiCall(HomePublicApi::Member_SaveByID, array($id, $sm));
		if ($result['status']) {
			$results = apiCall(HomePublicApi::Bbjmember_SaveByID, array($id, $smm));
			if ($results['status']) {
				$this -> success("操作成功！", U('Home/Usersm/index'));
			}
		}
	}

	public function edit() {
		if (IS_GET) {
			$user = session('user_sm');
			$id = I('id');
			$map = array('id' => $id, );
			$uid = $user['info']['id'];
			$map1 = array('uid' => $uid, );
			$result1 = apiCall(HomePublicApi::Address_Query, array($map1));
			//			dump($result);
			$this -> assign('address', $result1['info']);
			$result = apiCall(HomePublicApi::Address_Query, array($map));
			$this -> assign('addres', $result['info']);
			$this -> display('manager_edit');
		} else {
			$id = I('id', 0);
			$ars = array('country' => "中国", 'province' => I('sheng'), 'city' => I('shi'), 'area' => I('qu'), 'detail' => I('address', ''), 'contact_name' => I('name', ''), 'mobile' => I('mobile', ''), 'telphone' => I('phone', ''), 'post_code' => I('yb', ''), );
			$result = apiCall(HomePublicApi::Address_SaveByID, array($id, $ars));

			if ($result['status']) {
				$this -> success("修改成功！", U('Home/Usersm/address'));
			}
		}

	}

	public function del() {

		$id = I('id');
		$map = array('id' => $id, );
		//		dump($map);
		$result = apiCall(HomePublicApi::Address_Del, array($map));
		$this -> success("删除成功！", U('Home/Usersm/address'));
	}

}
