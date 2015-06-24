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
class UsersjController extends HomeController {
	
	public function index(){
		$headtitle="宝贝街-商家中心";
		$this->assign('head_title',$headtitle);
		$user_sj=session('user_sj');
		$id=$user_sj['info']['id'];
		$map=array(
			'uid'=>$id,
		);					
		$sj=apiCall(HomePublicApi::Bbjmember_Seller_Query, array($map));
		$this->assign('username',$user_sj['info']['username']);
		$this->assign('sj',$sj['info']);
		$this->display();
	}
	public function sj_zhxx(){
		$headtitle="宝贝街-账号信息";
		$this->assign('head_title',$headtitle);
		$user_sj=session('user_sj');
		$this->assign('username',$user_sj['info']['username']);
		$map=array(
			'uid'=>$user_sj['info']['id'],
		);
		$result=apiCall(HomePublicApi::Bbjmember_Seller_GetInfo, array($map));
//		dump($result);
		$this->assign('entity',$result['info']);
		$this->display();
	}
	public function edit1(){
		$id=I('id',0);
		$sheng=I('sheng','');$shi=I('shi','');$qu=I('qu','');$address=I('address');
		$entity=array(
			'aliwawa'=>I('aliwawa',''),
			'store_name'=>I('store_name',''),
			'store_url'=>I('store_url',''),
			'address'=>$sheng.$shi.$qu.$address,
		);
//		dump($entity);
		$result=apiCall(HomePublicApi::Bbjmember_Seller_SaveByID, array($id,$entity));
		if($result['status']){
			$this->success('修改成功',U('Home/Usersj/sj_zhxx'));
		}else{
			$this->error($result['info']);
		}
	}
	public function edit2(){
		$id=I('id',0);
		$entity=array(
			'task_linkman'=>I('rwfzr',''),
			'task_linkman_tel'=>I('fzrdh',''),
			'task_linkman_qq'=>I('fzrqq',''),
			'waybill_show'=>I('ydxs'),
		);
//		dump($entity);
		$result=apiCall(HomePublicApi::Bbjmember_Seller_SaveByID, array($id,$entity));
		if($result['status']){
			$this->success('修改成功',U('Home/Usersj/sj_zhxx'));
		}else{
			$this->error($result['info']);
		}
	}
	public function edit3(){
		$id=I('id',0);
		$entity=array(
			'linkman'=>I('lxr',''),
			'linkman_tel'=>I('tel',''),
			'linkman_qq'=>I('qq',''),
			'linkman_otherlink'=>I('qt',''),
		);
//		dump($entity);
		$result=apiCall(HomePublicApi::Bbjmember_Seller_SaveByID, array($id,$entity));
		if($result['status']){
			$this->success('修改成功',U('Home/Usersj/sj_zhxx'));
		}else{
			$this->error($result['info']);
		}
	}
	public function sj_zhaq(){
		$headtitle="宝贝街-账号安全";
		$this->assign('head_title',$headtitle);
		$user_sj=session('user_sj');
		$this->assign('username',$user_sj['info']['username']);
		$id=$user_sj['info']['id'];
//		dump($map);
		$result=apiCall(HomePublicApi::User_GetUser, array($id));
		$this->assign('entity',$result['info']);
		$this->display();
	}
	public function email(){
		$id=I('id','');
		$entity=array('email'=>I('email',''));
//		dump($entity);
		$result=apiCall(HomePublicApi::User_SaveByID, array($id,$entity));
		if($result['status']){
			$this->success('修改成功',U('Home/Usersj/sj_zhaq'));
		}else{
			$this->error($result['info']);
		}
	}
	public function phone(){
		$id=I('id','');
		$entity=array('mobile'=>I('phone',''));
//		dump($entity);
		$result=apiCall(HomePublicApi::User_SaveByID, array($id,$entity));
		if($result['status']){
			$this->success('修改成功',U('Home/Usersj/sj_zhaq'));
		}else{
			$this->error($result['info']);
		}
	}
	public function sj_xgmm(){
		if(IS_GET){
			$headtitle="宝贝街-修改密码";
			$this->assign('head_title',$headtitle);
			$user_sj=session('user_sj');
			$this->assign('username',$user_sj['info']['username']);
			$this->display();
		}else{
			$user_sj=session('user_sj');
			$uid=$user_sj['info']['id'];
			$pwd=I('old_password','');
			$data=array('password'=>I('password',''),);
			$result=apiCall(HomePublicApi::User_EditPwd, array($uid,$pwd,$data));
			if($result['status']){
			$this->success('修改成功',U('Home/Usersj/sj_xgmm'));
			}else{
				$this->error('请检查您输入的原密码');
			}
		}
		
	}
	public function sj_znxx(){
		$headtitle="宝贝街-站内消息";
		$this->assign('head_title',$headtitle);
		$user_sj=session('user_sj');
		$this->assign('username',$user_sj['info']['username']);
		$this->display();
	}
	public function sj_zzgl(){
		$headtitle="宝贝街-资金管理";
		$this->assign('head_title',$headtitle);
		$user_sj=session('user_sj');
		$this -> assign('username', $user_sj['info']['username']);
		$this -> display();
	}
	public function sj_viptd(){
		$headtitle="宝贝街-VIP通道";
		$this->assign('head_title',$headtitle);
		$user_sj=session('user_sj');
		$uid = $user_sj['info']['id'];
		$map = array('uid' => $uid, );
		$result = apiCall(HomePublicApi::Bbjmember_Seller_Query, array($map));
		$this -> assign('coins', $result['info'][0]['coins']);
		$this->assign('username',$user_sj['info']['username']);
//		dump($result);
		$this->display();
	}
	public function sj_sfkt(){
		$headtitle="宝贝街-商家课堂";
		$this->assign('head_title',$headtitle);
		$user_sj=session('user_sj');
		$this->assign('username',$user_sj['info']['username']);
		$this->display();
	}
	public function sj_zjgl(){
		$headtitle="宝贝街-资金管理";
		$this->assign('head_title',$headtitle);
		$user_sj=session('user_sj');
		$uid = $user_sj['info']['id'];
		$map = array('uid' => $uid, );
		$info = apiCall(HomePublicApi::FinBankaccount_Query, array($map));
		$result = apiCall(HomePublicApi::Bbjmember_Seller_Query, array($map));
		$user = apiCall(HomePublicApi::User_GetUser, array($uid));
		$page = array('curpage' => I('get.p', 0), 'size' => 6);
		$jyjl = apiCall(HomePublicApi::FinAccountBalanceHis_QueryAll, array($map, $page));
		$all = apiCall(HomePublicApi::FinAccountBalanceHis_Query, array($map));
		$jilus = $all['info'];
		foreach ($jilus as $key => $value) {
			if ($value['dtree_type'] == 3) {
				$sum += $value['defray'];
			}
		}
		$this -> assign('jilu', $jyjl['info']['list']);
		$this -> assign('sum', $sum);
		$this -> assign('show', $jyjl['info']['show']);
		$this -> assign('email', $user['info']['email']);
		$this -> assign('phone', $user['info']['mobile']);
		$this -> assign('coins', $result['info'][0]['coins']);
		$this -> assign('bank', $info['info'][0]);
		$this->assign('username',$user_sj['info']['username']);
		$this->display();
	}
	
	public function exits(){
		session('[destroy]'); // 删除session
		$this->display('Index/login');
	}
	public function tanchuceng(){
		$this->display();
	}
}