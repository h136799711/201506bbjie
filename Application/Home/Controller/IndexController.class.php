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
		$order = " post_modified desc ";
		$result = apiCall('Admin/Post/queryNoPaging',array($map, $order, $fields));
		$this->assign('zxgg',$result['info'][0]);
		
		
		$headtitle="宝贝街-首页";
		$this->assign('head_title',$headtitle);
		$users=session('user_sm');
		

		$this->assign('username',session('user_sm')['info']['username']);
		$this->display();
	}
	
	public function flzc(){
		$order = " post_modified desc ";
		$result = apiCall('Admin/Post/queryNoPaging',array($map, $order, $fields));
		$this->assign('zxgg',$result['info'][0]);
		
		$headtitle="宝贝街-福品专场";
		$this->assign('head_title',$headtitle);
		$users=session('user_sm');
		$this->assign('username',session('user_sm')['info']['username']);
		$this->display();
	}
	
	public function xfyd(){
		$order = " post_modified desc ";
		$result = apiCall('Admin/Post/queryNoPaging',array($map, $order, $fields));
		$this->assign('zxgg',$result['info'][0]);
		
		$headtitle="宝贝街-幸福一点";
		$this->assign('head_title',$headtitle);
		$users=session('user_sm');
		$this->assign('username',session('user_sm')['info']['username']);
		$this->display();
	}
	
	public function sjh(){
		$order = " post_modified desc ";
		$result = apiCall('Admin/Post/queryNoPaging',array($map, $order, $fields));
		$this->assign('zxgg',$result['info'][0]);
		
		
		$headtitle="宝贝街-试江湖";
		$this->assign('head_title',$headtitle);
		$users=session('user_sm');
		$this->assign('username',session('user_sm')['info']['username']);
		$this->display();
	}
	
	public function chg(){
		$order = " post_modified desc ";
		$result = apiCall('Admin/Post/queryNoPaging',array($map, $order, $fields));
		$this->assign('zxgg',$result['info'][0]);
		
		
		$headtitle="宝贝街-茶话馆";
		$this->assign('head_title',$headtitle);
		$users=session('user_sm');
		$this->assign('username',session('user_sm')['info']['username']);
		$this->display();
	}

	public function bzzx(){
		
		$headtitle="宝贝街-帮助中心";
		$this->assign('head_title',$headtitle);
		$users=session('user_sm');
		$this->assign('username',session('user_sm')['info']['username']);
		$this->display();
	}
	
	public function spxq(){
		//查询最新通知
		$order = " post_modified desc ";
		$result = apiCall('Admin/Post/queryNoPaging',array($map, $order, $fields));
		$this->assign('zxgg',$result['info'][0]);
		
		
		$headtitle="宝贝街-商品详情";
		$this->assign('head_title',$headtitle);
		$users=session('user_sm');
		$this->assign('username',session('user_sm')['info']['username']);
		$this->display();
	}
	
	public function gfgg(){
		//查询最新通知
		$order = " post_modified desc ";
		$result = apiCall('Admin/Post/queryNoPaging',array($map, $order, $fields));
		$this->assign('zxgg',$result['info'][0]);
		
		//查询公告标题
		$map1['level']=1;
		$result1=apiCall("Admin/Datatree/query",array($map1,$page1,$order1,$params1));
		$this->assign('ggTitle',$result1['info']['list']);
		
		/*foreach($result1['info']['list'] as $a=>$b){
			echo $a."==".$b[id]."<br>";
		}*/
	
		
		$post_category=I('post_category');
		
		$map2['post_category']=$post_category;
		$page2 = array('curpage' => I('get.p', 0), 'size' => 2);
		$result2=apiCall("Admin/Post/query",array($map2,$page2,$order2,$params2));
		
		
		$this->assign('list',$result2['info']['list']);
		$this->assign('show',$result2['info']['show']);
		
		$map3['hidden_value']=$post_category;
		$result3=apiCall("Admin/Datatree/queryNoPaging",array($map3, $order3, $fields3));
		$this->assign('ggct',$result3['info'][0]);
		
		
		
		$headtitle="宝贝街-官方公告";
		$this->assign('head_title',$headtitle);
		$users=session('user_sm');
		$this->assign('username',session('user_sm')['info']['username']);
		$this->display();
	}
	
	public function gfggxx(){
		$order = " post_modified desc ";
		$result = apiCall('Admin/Post/queryNoPaging',array($map, $order, $fields));
		$this->assign('zxgg',$result['info'][0]);
	
	
		$headtitle="宝贝街-官方公告信息";
		$this->assign('head_title',$headtitle);

		//查询公告标题
		$map1['level']=1;
		$result1=apiCall("Admin/Datatree/query",array($map1,$page1,$order1,$params1));
		$this->assign('ggTitle',$result1['info']['list']);
		
		//根据id查询公告文章
		$id=I('id');
		$map['id']=$id;
		$result = apiCall('Admin/Post/queryNoPaging',array($map, $order, $fields));
		
		$this->assign('gg',$result['info'][0]);
		
		
		$map2['hidden_value']=$result['info'][0]['post_category'];
		$result2=apiCall("Admin/Datatree/queryNoPaging",array($map2, $order2, $fields2));
		$this->assign('ggct',$result2['info'][0]);
		
		
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
	
	
	
	public function sm_manager(){
		
		$users=session('user_sm');
		$uid=$users['info']['id'];
		$id=$uid;
		$user=apiCall(HomePublicApi::User_GetUser, array($id));
		$this->assign('username',$user['info']['username']);
		$this->assign('phone',$user['info']['mobile']);
		$this->display();
	}       
	
	
	
	
	
}

