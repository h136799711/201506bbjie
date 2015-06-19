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
class IndexController extends HomeController {
	/*
	 * $headtitle="试民中心-任务";
		$this->assign('head_title',$headtitle);
	 * */
	public function register_sm(){
		$headtitle="宝贝街-试民注册";
		$this->assign('head_title',$headtitle);
		$this->display();
	}
	public function register_sj(){
		$headtitle="宝贝街-商家注册";
		$this->assign('head_title',$headtitle);
		$this->display();
	}
	
	public function index(){
		$headtitle="宝贝街-首页";
		$this->assign('head_title',$headtitle);
		$users=session('user_sm');
		$this->assign('username',session('user_sm')['info']['username']);
		$this->display();
	}
	
	public function xieyi(){
		$this->display();
	}
	public function smzc(){
		$username=I('post.user_name');
		$password=I('post.password');
		$mobile=I('post.phone_tel');
		$email=$username."@qq.com";
		$yqr=I('post.yaoqingren');
		$result = apiCall(HomePublicApi::User_Register, array($username, $password, $email,$mobile));
//		dump($result);
		if($result['status']){
			$uid=$result['info'];
			$entity=array(
				'uid'=>$uid,
				'referrer_id'=>1,
				'referrer_name'=>$yqr,
				'taobao_account'=>'',
				'aliwawa'=>'',
				'daily_task_money'=>1000,
				'dtree_job'=>'',
				'personal_signature'=>'',
				'brief_introduction'=>'',
				'address'=>'',
				'store_name'=>'',
			);
			$result1 = apiCall(HomePublicApi::Bbjmember_Add, array($entity));
//			dump($result1);
			if($result1['status']){
				$user=array(
					'uid'=>$uid,
					'nickname'=>$username,
					'status'=>1,
					'realname'=>'',
					'idnumber'=>'',
					'update_time'=>time(),
				);
				$result2 = apiCall(HomePublicApi::Member_Add, array($user));
//				dump($result2);
				if($result2['status']){
					$group=array(
						'uid'=>$uid,
						'group_id'=>14,
					);
//					dump($group);
					$result3 = apiCall(HomePublicApi::Group_Add, array($group));
//					dump($result3);
					if($result3['status']){
						$this->display('login');
					}
				}
			}
		}

	}
	public function sjzc(){
		$username=I('post.user_name');
		$password=I('post.password');
		$mobile=I('post.phone_tel');
		$email=$username."@qq.com";
		$yqr=I('post.yaoqingren');
		$result = apiCall(HomePublicApi::User_Register, array($username, $password, $email,$mobile));
		if($result['status']){
			$uid=$result['info'];
			//TODO :处理邀请人
			$entity=array(
				'uid'=>$uid,
				'referrer_id'=>1,
				'referrer_name'=>$yqr,
				'taobao_account'=>'',
				'address'=>'',
				'aliwawa'=>'',
				'store_name'=>'',
				'store_url'=>'',
				'linkman'=>'',
				'linkman_tel'=>'',
				'task_linkman'=>'',
				'task_linkman_tel'=>'',
				'task_linkman_qq'=>'',
				'waybill_show'=>'',
				'linkman_qq'=>'',
				'linkman_otherlink'=>'',
				'create_time'=>time(),
				'update_time'=>time(),
			);
			$result1 = apiCall(HomePublicApi::Bbjmember_Seller_Add, array($entity));
			session('sjid',$result1['info']);
			if($result1['status']){
				$user=array(
					'uid'=>$uid,
					'nickname'=>$username,
					'status'=>1,
					'realname'=>'',
					'idnumber'=>'',
					'update_time'=>time(),
				);
				$result2 = apiCall(HomePublicApi::Member_Add, array($user));
				if($result2['status']){
					$group=array(
						'uid'=>$uid,
						'group_id'=>15,
					);
//					dump($group);
					$result3 = apiCall(HomePublicApi::Group_Add, array($group));
					if($result3['status']){
						$this->display('register_sj_kz');
					}
				}
			}
		}
	}
	public function sjzc_kz(){
		
		$id=session('sjid');
		$entity=array(
			'store_name'=>I('post.dpname',''),
			'aliwawa'=>I('alww',''),
			'linkman_qq'=>I('post.qq'),
			'linkman'=>I('post.lxr'),
			'address'=>I('post.jydz'),
		);
		$result1 = apiCall(HomePublicApi::Bbjmember_Seller_SaveByID, array($id,$entity));
		if ($result1['status']) {
			$headtitle="宝贝街-登录";
			$this->assign('head_title',$headtitle);
			$this->display('login');
		}
	}
	/**
	 * 登录地址
	 */
	public function login(){
		if(IS_GET){
			$headtitle="宝贝街-登录";
			$this->assign('head_title',$headtitle);
			$this->display();
		}else{
			//检测用户
			
			
			$username = I('post.username', '', 'trim');
			$password = I('post.password', '', 'trim');
			
			$result = apiCall(HomePublicApi::User_Login, array('username' => $username, 'password' => $password));
//			dump($result);
			//调用成功
			if ($result['status']) {
				$uid = $result['info'];
				$users=apiCall(HomePublicApi::User_GetInfo, array($username));
				$userid=$users['info']['id'];
				$map="uid=".$userid;					
				$group=apiCall(HomePublicApi::Group_QueryNpPage, array($map));
				$groupid=$group['info'][0]['group_id'];
				if($groupid==14){
					session('user_sm',$users);
					$this->assign('username',$users['info']['username']);
					$this -> display('sm_manager');
				}else{
					session('user_sj',$users);
					$user_sj=session('user_sj');
					$id=$user_sj['info']['id'];
					$map=array(
						'uid'=>$id,
					);					
					$sj=apiCall(HomePublicApi::Bbjmember_Seller_Query, array($map));
					$this->assign('username',$user_sj['info']['username']);
					$this->assign('sj',$sj['info']);
					$this->assign('username',$users['info']['username']);
					$this->display('Usersj/index');
				}
			} else{
				$this->assign('error','请仔细核对您的账号和密码');
				$this -> display('login');
				
			}
		}
	}
	
	public function exits(){
		session('[destroy]'); // 删除session
		$this->display('login');
	}
	
	public function manager_rw(){
		$headtitle="试民中心-任务";
		$this->assign('head_title',$headtitle);
		$this->display();
	}
	
	public function sm_manager(){
		$username=I('username');
		$users=apiCall(HomePublicApi::User_GetInfo, array($username));
		$userid=$users['info']['id'];
		$map="uid=".$userid;					
		$group=apiCall(HomePublicApi::Group_QueryNpPage, array($map));
		$groupid=$group['info'][0]['group_id'];
		if($groupid==14){
			session('user_sm',$users);
			$this->assign('username',$users['info']['username']);
			$this->display();
		}else{
			session('user_sm',$users);
			$this->assign('username',$users['info']['username']);
			$this->display('Usersj/index');
		}
		
	}
	
	
	
	
	
}

