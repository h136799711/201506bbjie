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
	
	
	
	public function register_sm(){
		$this->display();
	}
	public function register_sj(){
		$this->display();
	}
	
	public function index(){
		$users=session('user_sm');
//		dump(session('user_sm')['info']['username']);
		$this->assign('username',session('user_sm')['info']['username']);
//		if()
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
						'group_id'=>14,
					);
//					dump($group);
					$result3 = apiCall(HomePublicApi::Group_Add, array($group));
					if($result3['status']){
						$this->success('login');
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
						$this->display('login');
					}
				}
			}
		}
	}
	/**
	 * 登录地址
	 */
	public function login(){
		if(IS_GET){
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
				}
					

				

			} 
		}
	}
	
	public function exits(){
		session('[destroy]'); // 删除session
		$this->display('login');
	}
	
	public function manager_rw(){
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
		}
		
	}
	
	
	
	
	
}

