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
use Admin\Api\AdminPublicApi;
/*
 * 官网首页
 */
class IndexController extends HomeController {
	
	 /*
	  * 试民注册界面
	  * */
	public function register_sm(){
		$headtitle="宝贝街-试民注册";
		$this->assign('head_title',$headtitle);
		$this->display();
	}
	/*
	  * 商家注册界面
	  * */
	public function register_sj(){
		$headtitle="宝贝街-商家注册";
		$this->assign('head_title',$headtitle);
		$this->display();
	}
	/*
	  * 首页
	  * */
	public function index(){
		$order = " post_modified desc ";
		$result = apiCall(AdminPublicApi::Post_QueryNoPaging,array($map, $order));
		$this->assign('zxgg',$result['info'][0]);
		
		
		$headtitle="宝贝街-首页";
		$this->assign('head_title',$headtitle);
		$users=session('user');
		

		$this->assign('username',session('user')['info']['username']);
		$this->display();
	}
	/*
	  * 福品专场
	  * */
	public function flzc(){
		$order = " post_modified desc ";
		$result = apiCall(AdminPublicApi::Post_QueryNoPaging,array($map, $order));
		$this->assign('zxgg',$result['info'][0]);
		
		$headtitle="宝贝街-福品专场";
		$this->assign('head_title',$headtitle);
		$users=session('user');
		$this->assign('username',session('user')['info']['username']);
		$this->display();
	}
	/*
	  * 幸福一点
	  * */
	public function xfyd(){
		$order = " post_modified desc ";
		$result = apiCall(AdminPublicApi::Post_QueryNoPaging,array($map, $order));
		$this->assign('zxgg',$result['info'][0]);
		
		$headtitle="宝贝街-幸福一点";
		$this->assign('head_title',$headtitle);
		$users=session('user');
		$this->assign('username',session('user')['info']['username']);
		$this->display();
	}
	/*
	  * 试江湖
	  * */
	public function sjh(){
		$order = " post_modified desc ";
		$result = apiCall(AdminPublicApi::Post_QueryNoPaging,array($map, $order));
		$this->assign('zxgg',$result['info'][0]);
		
		
		$headtitle="宝贝街-试江湖";
		$this->assign('head_title',$headtitle);
		$users=session('user');
		$this->assign('username',session('user')['info']['username']);
		$this->display();
	}
	/*
	  * 茶话馆
	  * */
	public function chg(){
		$order = " post_modified desc ";
		$result = apiCall(AdminPublicApi::Post_QueryNoPaging,array($map, $order));
		$this->assign('zxgg',$result['info'][0]);
		
		
		$headtitle="宝贝街-茶话馆";
		$this->assign('head_title',$headtitle);
		$users=session('user');
		$this->assign('username',session('user')['info']['username']);
		$this->display();
	}
/*
	  * 帮助中心
	  * */
	public function bzzx(){
		
		$headtitle="宝贝街-帮助中心";
		$this->assign('head_title',$headtitle);
		$users=session('user');
		$this->assign('username',session('user')['info']['username']);
		$this->display();
	}
	/*
	  * 商品详情
	  * */
	public function spxq(){
		//查询最新通知
		$order = " post_modified desc ";
		$result = apiCall(AdminPublicApi::Post_QueryNoPaging,array($map, $order));
		$this->assign('zxgg',$result['info'][0]);
		
		
		$headtitle="宝贝街-商品详情";
		$this->assign('head_title',$headtitle);
		$users=session('user');
		$this->assign('username',session('user')['info']['username']);
		$this->display();
	}
	/*
	  * 官方公告
	  * */
	public function gfgg(){
		//查询最新通知
		$order = " post_modified desc ";
		$map=array();
		$result = apiCall(AdminPublicApi::Post_QueryNoPaging,array($map, $order));
		$this->assign('zxgg',$result['info'][0]);
		
		//查询公告标题
		$map['parentid']=21;
		$result=apiCall(AdminPublicApi::Datatree_Query,array($map));
		$this->assign('ggTitle',$result['info']['list']);
		//dump($result['info']['list']);
		$post_category=I('post_category');
		$map=array();
		$map['post_category']=$post_category;
		$page=array();
		$page = array('curpage' => I('get.p', 0), 'size' => 2);
		$result=apiCall(AdminPublicApi::Post_Query,array($map,$page));
		
		
		$this->assign('list',$result['info']['list']);
		$this->assign('show',$result['info']['show']);
		
		$map=array();
		$map['id']=$post_category;
		$result=apiCall(AdminPublicApi::Post_QueryNoPaging,array($map));
		$this->assign('ggct',$result['info'][0]);
		
		
		
		$headtitle="宝贝街-官方公告";
		$this->assign('head_title',$headtitle);
		$users=session('user');
		$this->assign('username',session('user')['info']['username']);
		$this->display();
	}
	/*
	  * 官方公告信息
	  * */
	public function gfggxx(){
		$map=array();
		$order = " post_modified desc ";
		$result = apiCall(AdminPublicApi::Post_QueryNoPaging,array($map, $order));
		$this->assign('zxgg',$result['info'][0]);
	
	
		$headtitle="宝贝街-官方公告信息";
		$this->assign('head_title',$headtitle);

		//查询公告标题
		$map=array();
		$map['parentid']=21;
		$result=apiCall(AdminPublicApi::Datatree_Query,array($map));
		$this->assign('ggTitle',$result['info']['list']);
		
		//根据id查询公告文章
		$map=array();
		$map['id']=I('id');
		$result = apiCall(AdminPublicApi::Post_QueryNoPaging,array($map));
		
		$this->assign('gg',$result['info'][0]);
		
		$map=array();
		$map['id']=$result['info'][0]['post_category'];
		$result=apiCall(AdminPublicApi::Datatree_QueryNoPaging,array($map));
		$this->assign('ggct',$result['info'][0]);
		
		
		$users=session('user');
		$this->assign('username',session('user')['info']['username']);
		$this->display();
	}
	/*
	  * 用户协议
	  * */
	public function xieyi(){
		$this->display();
	}
	/*
	  * 试民注册界面
	  * */
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
				'create_time'=>time(),
				'update_time'=>time(),
				'coins'=>0,
				'fucoin'=>0,
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
						$this->success('注册成功',U('Home/Index/login'));
					}
				}
			}
		}

	}
/*
	  * 商家注册基本信息
	  * */
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
/*
	  * 商家注册详细信息
	  * */
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
					session('user',$users);
					$this->assign('username',$users['info']['username']);
					$this -> display('sm_manager');
				}else{
					session('user',$users);
					$user=session('user');
					$id=$user['info']['id'];
					$map=array(
						'uid'=>$id,
					);					
					$sj=apiCall(HomePublicApi::Bbjmember_Seller_Query, array($map));
					$this->assign('username',$user['info']['username']);
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
	/*
	  * 退出当前账号
	  * */
	public function exits(){
		session('[destroy]'); // 删除session
		$this->display('login');
	}
	/*
	 * 验证用户名是否存在
	 * */
	 public function checkname(){
	 	$username = I("name",''); 
		if(isset($username)){ 
			$results = apiCall(HomePublicApi::User_CheckUserName,array($username));
		}	
//
		$this->ajaxReturn($results['info'],'json');
	 }
	
	/*
	  * 试民首页
	  * */
	public function sm_manager(){
		
		$users=session('user');
		$uid=$users['info']['id'];
		$id=$uid;
		$map="uid=".$id;					
		$group=apiCall(HomePublicApi::Group_QueryNpPage, array($map));
		$groupid=$group['info'][0]['group_id'];
		if($groupid==15){
			$user=apiCall(HomePublicApi::User_GetUser, array($id));
			$this->assign('username',$user['info']['username']);
			$this->assign('phone',$user['info']['mobile']);
			$this->display('Usersj/index');
		}else{
			$user=apiCall(HomePublicApi::User_GetUser, array($id));
			$this->assign('username',$user['info']['username']);
			$this->assign('phone',$user['info']['mobile']);
			$this->display();
		}
		
	}       
	
	
	
	
	
	
	
}

