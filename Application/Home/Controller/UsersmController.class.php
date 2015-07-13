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
use Admin\Api\AdminPublicApi;
/*
 * 试民操作
 */
class UsersmController extends CheckLoginController {
	/*
	 * 试民资料
	 * */
	public function index() {
		
		$user = session('user');
		$this -> assign('username', $user['info']['username']);
		$userid = $user['info']['id'];
		//		dump($userid);
		$map = array('uid' => $userid, );
		$result = apiCall(HomePublicApi::Member_Query, array($map));
		$results = apiCall(HomePublicApi::Bbjmember_Query, array($map));
		$this -> assign('info', $results['info']);
		$this -> assign('mum', $result['info']);
		$this -> assign('cs_xx', 'sed');
		$order = " post_modified desc ";
		$result = apiCall(AdminPublicApi::Post_QueryNoPaging,array($ma,$order));
		$this->assign('zxgg',$result['info'][0]);
//		dump($result);
		$this -> display('manager_info');
	}
	/*
	 * 试民任务设置
	 * */
	public function manager_rw() {
		$user = session('user');
		$this -> assign('username', $user['info']['username']);
		$headtitle = "试民中心-任务";
		$order = " post_modified desc ";
		$result = apiCall(AdminPublicApi::Post_QueryNoPaging,array($ma,$order));
		$this->assign('zxgg',$result['info'][0]);
		$this -> assign('head_title', $headtitle);
		$this -> assign('cs_rw', 'sed');
		$this -> display();
	}
	/*
	 * 试民钱庄
	 * */
	public function sm_bbqz() {
		$headtitle = "宝贝街-宝贝钱庄";
		$this -> assign('head_title', $headtitle);
		$user = session('user');
		$uid = $user['info']['id'];
		$map = array('uid' => $uid, );
		$info = apiCall(HomePublicApi::FinBankaccount_Query, array($map));
		$result = apiCall(HomePublicApi::Bbjmember_Query, array($map));
		$user = apiCall(HomePublicApi::User_GetUser, array($uid));
		$result1 = apiCall(HomePublicApi::Bbjmember_Query, array($map));
		$page = array('curpage' => I('get.p', 0), 'size' => 6);
		$jyjl = apiCall(HomePublicApi::FinAccountBalanceHis_QueryAll, array($map, $page));
		$all = apiCall(HomePublicApi::FinAccountBalanceHis_Query, array($map));
		$jilus = $all['info'];
		foreach ($jilus as $key => $value) {
			if ($value['dtree_type'] == 3) {
				$sum += $value['defray'];
			}
		}
		$order = " post_modified desc ";
		$result = apiCall(AdminPublicApi::Post_QueryNoPaging,array($ma,$order));
		$this->assign('zxgg',$result['info'][0]);
		$this -> assign('jilu', $jyjl['info']['list']);
		$this -> assign('sum', $sum);
		$this -> assign('show', $jyjl['info']['show']);
		if($result['info'][0]['auth_status']==1){
			$this -> assign('email', $user['info']['email']);
			$this -> assign('phone', $user['info']['mobile']);
		}
		$this -> assign('cs_zj', 'sed');
		$this -> assign('coins', $result['info'][0]['coins']);
		$this -> assign('bank', $info['info'][0]);
		$this -> assign('username', $user['info']['username']);
		$this -> display();
	}
	/*
	 * 设置淘宝账号
	 * */
	public function settaobao(){
		$user = session('user');
		$id=$user['info']['id'];
		$entity=array('taobao_account'=>I('taobao','无'));
		$result = apiCall(HomePublicApi::Bbjmember_SaveByID,array($id,$entity));
		if($result['status']){
			$this->success('绑定成功',U('Home/Index/sm_manager'));
		}
		
	}
	/*
	 * 预定商品
	 * */
	public function sm_ydsp() {
		$headtitle = "宝贝街-预定商品";
		$this -> assign('head_title', $headtitle);
		$user = session('user');
		$this -> assign('username', $user['info']['username']);
		$this -> assign('cs_yd', 'sed');
		$order = " post_modified desc ";
		$result = apiCall(AdminPublicApi::Post_QueryNoPaging,array($ma,$order));
		$this->assign('zxgg',$result['info'][0]);
		$this -> display();
	}
	/*
	 * 因祸得福
	 * */
	public function sm_yhdf() {
		$headtitle = "宝贝街-因祸得福";
		$this -> assign('head_title', $headtitle);
		$user = session('user');
		$this -> assign('username', $user['info']['username']);
		$order = " post_modified desc ";
		$result = apiCall(AdminPublicApi::Post_QueryNoPaging,array($ma,$order));
		$this->assign('zxgg',$result['info'][0]);
		$this -> display();
	}
/*
	 * 师傅课堂
	 * */
	public function sm_sfkt() {
		$headtitle = "宝贝街-试福课堂";
		$this -> assign('head_title', $headtitle);
		$user = session('user');
		$this -> assign('username', $user['info']['username']);
		$order = " post_modified desc ";
		$result = apiCall(AdminPublicApi::Post_QueryNoPaging,array($ma,$order));
		$this->assign('zxgg',$result['info'][0]);
		$this -> display();
	}
	/*
	 * 收藏活动
	 * */
	public function sm_schd() {
		$headtitle = "宝贝街-收藏活动";
		$this -> assign('head_title', $headtitle);
		$user = session('user');
		$this -> assign('cs_sc', 'sed');
		$order = " post_modified desc ";
		$result = apiCall(AdminPublicApi::Post_QueryNoPaging,array($ma,$order));
		$this->assign('zxgg',$result['info'][0]);
		$this -> assign('username', $user['info']['username']);
		$this -> display();
		
	}
	/*
	 * 兑换商品
	 * */
	public function sm_dhsp() {
		$headtitle = "宝贝街-兑换商品";
		$this -> assign('cs_dh', 'sed');
		$this -> assign('head_title', $headtitle);
		$user = session('user');
		$order = " post_modified desc ";
		$result = apiCall(AdminPublicApi::Post_QueryNoPaging,array($ma,$order));
		$this->assign('zxgg',$result['info'][0]);
		$this -> assign('username', $user['info']['username']);
		$this -> display();
	}
	/*
	 * 活动
	 * */
	public function sm_bbhd() {
		$headtitle = "宝贝街-宝贝活动";
		$this -> assign('cs_sf', 'sed');
		$this -> assign('head_title', $headtitle);
		$user = session('user');
		$order = " post_modified desc ";
		$result = apiCall(AdminPublicApi::Post_QueryNoPaging,array($ma,$order));
		$map=array('uid'=>$user['info']['id'],'do_status'=>1);
		$result1=apiCall(HomePublicApi::Task_His_Query,array($map));
		$mapp=array('id'=>$result1['info'][0]['task_id']);
		$result2=apiCall(HomePublicApi::Task_Query,array($mapp));
		$this->assign('zxgg',$result['info'][0]);
		for ($i=0; $i <count($result2['info']) ; $i++) {
			$map3=array('task_id'=>$result2['info'][$i]['id']);
			$result3[]=apiCall(HomePublicApi::TaskHasProduct_Query,array($map3));
		}
		$this -> assign('username', $user['info']['username']);
		$goods=apiCall(HomePublicApi::Product_Query,array($ddd));
		$this->assign('goods',$goods['info']);
		$this->assign('tspro',$result3);
		$this->assign('tp',$result1['info']);
		$this->assign('task',$result2['info']);
		$this -> display();
	}
	/*
	 * 试民安全资料认证
	 * */
	public function sm_aqzx() {
		$headtitle = "宝贝街-安全中心";
		$this -> assign('head_title', $headtitle);
		$user = session('user');
		$order = " post_modified desc ";
		$result = apiCall(AdminPublicApi::Post_QueryNoPaging,array($ma,$order));
		$this->assign('zxgg',$result['info'][0]);
		$this -> assign('username', $user['info']['username']);
		$this -> assign('phone', $user['info']['mobile']);
		$this -> assign('email', $user['info']['email']);
		$this -> assign('cs_aq', 'sed');
		$this -> display();
	}
	/*
	 * 幸福一点
	 * */
	public function sm_xfyd() {
		$headtitle = "宝贝街-幸福一点";
		$this -> assign('head_title', $headtitle);
		$user = session('user');
		$order = " post_modified desc ";
		$result = apiCall(AdminPublicApi::Post_QueryNoPaging,array($ma,$order));
		$this->assign('zxgg',$result['info'][0]);
		$this -> assign('cs_xf', 'sed');
		$this -> assign('username', $user['info']['username']);
		$this -> display();
	}
	/*
	 * 勋章管理
	 * */
	public function sm_xzgl() {
		$headtitle = "宝贝街-勋章管理";
		$this -> assign('head_title', $headtitle);
		$user = session('user');
		$order = " post_modified desc ";
		$result = apiCall(AdminPublicApi::Post_QueryNoPaging,array($ma,$order));
		$this->assign('zxgg',$result['info'][0]);
		$this -> assign('cs_xz', 'sed');
		$this -> assign('username', $user['info']['username']);
		$this -> display();
	}
	/*
	 * 站内消息
	 * */
	public function sm_znxx() {
		$headtitle = "宝贝街-站内消息";
		$this -> assign('head_title', $headtitle);
		$user = session('user');
		$order = " post_modified desc ";
		$result = apiCall(AdminPublicApi::Post_QueryNoPaging,array($ma,$order));
		$this->assign('zxgg',$result['info'][0]);
		$this -> assign('cs_xiaox', 'sed');
		$this -> assign('username', $user['info']['username']);
		$this -> display();
	}
	/*
	 * 试民添加银行卡信息
	 * */
	public function addbank() {
		$pwd = I('pwd', '');
		$user = session('user');
		$uid = $user['info']['id'];
		//think_ucenter_md5($password, UC_AUTH_KEY)
		$result = apiCall(HomePublicApi::User_GetbyID, array($uid));
		$password = $result['info']['password'];
		$pp = think_ucenter_md5($pwd, UC_AUTH_KEY);
		if ($password == $pp) {
			$entity = array('uid' => $user['info']['id'], 'bank_name' => I('bank', ''), 'bank_account' => I('bank_num', ''), 'create_time' => time(), 'status' => 0, 'notes' => '', 'cardholder' => I('name', ''), 'province' => I('sheng', ''), 'city' => I('shi', ''), );
			$map = array('uid' => $user['info']['id'], );
			$info = apiCall(HomePublicApi::FinBankaccount_Query, array($map));
			if ($info['info'] == null) {
				$add = apiCall(HomePublicApi::FinBankaccount_Add, array($entity));
				$this -> success('绑定成功', U('Home/Usersm/sm_bbqz'));
			} else {
				$id = $info['info'][0]['id'];
				$update = apiCall(HomePublicApi::FinBankaccount_SaveByID, array($id, $entity));
				$this -> success('修改成功', U('Home/Usersm/sm_bbqz'));
			}
		} else {
			$this -> error('登录密码错误！', U('Home/Usersm/sm_bbqz'));
		}
	}
	/*
	 * 试民收货地址管理
	 * */
	public function address() {
		$user = session('user');
		if (IS_GET) {
			$headtitle = "宝贝街-收货地址";
			$this -> assign('head_title', $headtitle);
			$this -> assign('username', $user['info']['username']);
			$user = session('user');
			$uid = $user['info']['id'];
			$map = array('uid' => $uid, );
			$result = apiCall(HomePublicApi::Address_Query, array($map));
			$this -> assign('address', $result['info']);
			$order = " post_modified desc ";
			$result = apiCall(AdminPublicApi::Post_QueryNoPaging,array($ma,$order));
			$this->assign('zxgg',$result['info'][0]);
			$this -> assign('cs_dz', 'sed');
			$this -> display('manager_address');
		} else {
			$ars = array('uid' => $user['info']['id'], 'country' => "中国", 'province' => I('sheng'), 'city' => I('shi'), 'area' => I('qu'), 'detail' => I('address', ''), 'contact_name' => I('name', ''), 'mobile' => I('mobile', ''), 'telphone' => I('phone', ''), 'post_code' => I('yb', ''), 'create_time' => time(), );
			$result = apiCall(HomePublicApi::Address_Add, array($ars));

			if ($result['status']) {
				$this -> success("操作成功！", U('Home/Usersm/address'));
			}
		}

	}
	
	 
	/*
	 * 试民资料添加
	 * */
	public function add() {
		$user = session('user');
		$id = $user['info']['id'];
		$year = I('year', 0);
		$month = I('month', 0);
		$day = I('day', 0);
		$bir = $year . '-' . $month . '-' . $day;
		//		dump($bir);
		$sm = array('birthday' => $bir, 'sex' => I('sex', 0), 'qq' => I('qq', '1'), 'realname' => I('realname', ''), );
		$sheng = I('sheng');
		$shi = I('shi');
		$qu = I('qu', '');
		$smm = array('dtree_job' => I('zhiye', ''), 'personal_signature' => I('grqm', ''), 'brief_introduction' => I('grjj', ''), 'address' => $sheng . $shi . $qu . I('address', ''), );
		
		$result = apiCall(HomePublicApi::Member_SaveByID, array($id, $sm));
		if ($result['status']) {
			$results = apiCall(HomePublicApi::Bbjmember_SaveByID, array($id, $smm));
			if ($results['status']) {
				$this -> success("操作成功！", U('Home/Usersm/index'));
			}
		}
	}
	/*
	 * 试民收货地址修改
	 * */
	public function edit() {
		if (IS_GET) {
			$user = session('user');
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
	/*
	 * 试民收货地址删除
	 * */
	public function del() {

		$id = I('id');
		$map = array('id' => $id, );
		//		dump($map);
		$result = apiCall(HomePublicApi::Address_Del, array($map));
		$this -> success("删除成功！", U('Home/Usersm/address'));
	}
	
	/*
	 * 计时器改变任务状态
	 * */
	public function tasktimeover(){
		$id=I('id');
		$entity=array('do_status'=>0);
		$result=apiCall(HomePublicApi::Task_His_SaveByID,array($id,$entity));
		if($result['status']){
			$this->success('时间超时，由系统取消',U('Home/Usersm/sm_bbhd'));
		}
	}

}
