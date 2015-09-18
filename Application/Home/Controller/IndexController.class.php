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
		
		$this->redirect('Shop/Index/index');
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
		$order = " post_modified desc ";
		$result = apiCall(AdminPublicApi::Post_QueryNoPaging,array($map, $order));
		$this->assign('zxgg',$result['info']);
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
		$result=apiCall(AdminPublicApi::Datatree_QueryNoPaging,array($map));
		//dump($post_category);
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
		$yqr=I('post.yaoqingren','');
		$result = apiCall(HomePublicApi::User_Register, array($username, $password, $email,$mobile));
//		dump($yqr);
		if($yqr=='' && $yqr==null){
			$where=" username ='test1' ";
			$fu=M('ucenterMember')->where($where)->select();
			$id=$fu[0]['id'];
		}else{
			$where="username ='$yqr'";
			$fu=M('ucenterMember')->where($where)->select();
			$id=$fu[0]['id'];
		}
		if($result['status']){
			$uid=$result['info'];
			$entity=array(
				'uid'=>$uid,
				'referrer_id'=>$id,
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
					'qq'=>I('qq',0),
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
					$result3 = apiCall(HomePublicApi::Group_Add, array($group));
					$this->success('注册成功',U('Home/Index/login'));
				}
			}
		}

	}
	/*
	 * 删除站内信息
	 * */
	public function delsjxx(){
		$mbid=I('mbid',0);
		$map=array('msg_status'=>2);
		$result=apiCall(AdminPublicApi::Msgbox_SavebyId, array($mbid,$map));
		if($result['status']){
			$this->success('操作成功！',U('Home/Usersj/sj_znxx'));
		}
	}
	/*
	 * 删除站内信息
	 * */
	public function delsmxx(){
		$mbid=I('mbid',0);
		$map=array('msg_status'=>2);
		$result=apiCall(AdminPublicApi::Msgbox_SavebyId, array($mbid,$map));
		if($result['status']){
			$this->success('操作成功！',U('Home/Usersm/sm_znxx'));
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
		$yqr=I('post.yaoqingren','');
		$result = apiCall(HomePublicApi::User_Register, array($username, $password, $email,$mobile));
//		dump($yqr);
		if($yqr=='' && $yqr==null){
			$where=" username ='test1' ";
			$fu=M('ucenterMember')->where($where)->select();
			$id=$fu[0]['id'];
		}else{
			$where="username ='$yqr'";
			$fu=M('ucenterMember')->where($where)->select();
			$id=$fu[0]['id'];
		}
		if($result['status']){
			$uid=$result['info'];
			$entity=array(
				'uid'=>$uid,
				'referrer_id'=>$id,
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
				'exp'=>0,
				'coins'=>'0.000',
				'vip_level'=>0,
				'auth_status'=>0,
				'linkman_otherlink'=>'',
				'create_time'=>time(),
				'update_time'=>time(),
			);
			$result1 = apiCall(HomePublicApi::Bbjmember_Seller_Add, array($entity));
//			
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
//					dump($result3);
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
				if($group['status']){
					$groupid=$group['info'][0]['group_id'];
					if($groupid==14){
						$return=M('bbjmember')->where('uid='.$uid)->setInc('exp','4');
						session('user',$users);
						$groupid=$group['info'][0]['group_id'];
						$user=session('user');
						$id=$user['info']['id'];
						$map=array(
							'uid'=>$id,
						);	
						$result1=apiCall(HomePublicApi::Bbjmember_Query, array($map));
						$user=apiCall(HomePublicApi::User_GetUser, array($id));
						$this->assign('username',$user['info']['username']);
						$this->assign('phone',$user['info']['mobile']);
						$order = " post_modified desc ";
						$result = apiCall(AdminPublicApi::Post_QueryNoPaging,array($ma,$order));
						$this->assign('taobao',$result1['info'][0]['taobao_account']);
						$this->assign('user',$result1['info'][0]);
						$this->assign('head_img',$result1['info'][0]['head_img']);
						$this->assign('zxgg',$result['info'][0]);
						$this->assign('info',$result['info']);
						$this->checklevel();
						$this->getcount();
						$this->success('登录成功!',U('Home/Index/sm_manager'));
					}else{
						$return=M('bbjmemberSeller')->where('uid='.$uid)->setInc('exp','4');
						session('user',$users);
						$headtitle="宝贝街-商家中心";
						$this->assign('head_title',$headtitle);
						$user=session('user');
						$id=$user['info']['id'];
						$map=array(
							'uid'=>$id,
						);					
						$order = " post_modified desc ";
						$result = apiCall(AdminPublicApi::Post_QueryNoPaging,array($map, $order));
						$this->assign('info',$result['info']);
						$sj=apiCall(HomePublicApi::Bbjmember_Seller_Query, array($map));
						$this->assign('money',$sj['info'][0]['coins']);
						$this->assign('username',$user['info']['username']);
						$this->assign('head_img',$sj['info'][0]['head_img']);
						$this->assign('sj',$sj['info'][0]);
						$sj=A('usersj');
						$sj->is_auth();
						$sj->getcount();
						$sj->checklevel();
							$this->success('登录成功!',U('Home/Usersj/index'));
					}
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
		$this->ajaxReturn($results['info'],'json');
	 }

	 /*
	  * 上传头像
	  * */
	public function uploadPicture(){
		if(IS_POST){
	        /* 返回标准数据 */
	        $return  = array('status' => 1, 'info' => '上传成功', 'data' => '');
	
	        /* 调用文件上传组件上传文件 */
	        $Picture = D('Picture');
	        $pic_driver = C('PICTURE_UPLOAD_DRIVER');
	        $info = $Picture->upload(
	            $_FILES,
	            C('PICTURE_UPLOAD'),
	            C('PICTURE_UPLOAD_DRIVER'),
	            C("UPLOAD_{$pic_driver}_CONFIG")
	        ); //TODO:上传到远程服务器
	
	        /* 记录图片信息 */
	        if($info){
	            $return['status'] = 1;
	            $return = array_merge($info['download'], $return);
	        } else {
	            $return['status'] = 0;
	            $return['info']   = $Picture->getError();
	        }
	
	        /* 返回JSON数据 */
	        $this->ajaxReturn($return);
		}
		
	}
	/*
	  * 试民首页
	  * */
	public function sm_manager(){
		$users=session('user');
		$uid=$users['info']['id'];
		$id=$uid;
		$mapp="uid=".$uid;					
		$group=apiCall(HomePublicApi::Group_QueryNpPage, array($mapp));
		$groupid=$group['info'][0]['group_id'];
		$user=session('user');
		$id=$user['info']['id'];
		$map=array(
			'uid'=>$id,
		);	
		$result1=apiCall(HomePublicApi::Bbjmember_Query, array($map));
		$user=apiCall(HomePublicApi::User_GetUser, array($id));
		$this->assign('username',$user['info']['username']);
		$this->assign('phone',$user['info']['mobile']);
		$order = " post_modified desc ";
		$result = apiCall(AdminPublicApi::Post_QueryNoPaging,array($ma,$order));
		$this->assign('taobao',$result1['info'][0]['taobao_account']);
		$this->assign('user',$result1['info'][0]);
		$this->assign('head_img',$result1['info'][0]['head_img']);
		$this->assign('zxgg',$result['info'][0]);
		$this->assign('info',$result['info']);
		$this->getcount();
		$this->checklevel();
		$this->display();
		
		
	}   
	public function checklevel(){
		$user=session('user');
		$map=array('uid'=>$user['info']['id']);
		$result=apiCall(HomePublicApi::Bbjmember_Query,array($map));
		if($result['status']){
			$exp=$result['info'][0]['exp'];
			if($exp<100){
				$this->assign('level',1);
				$this->assign('exp',$exp-0);
			}else if($exp>=100 && $exp<200){
				$this->assign('level',2);
				$this->assign('exp',$exp-100);
			}else if($exp>=200 && $exp<300){
				$this->assign('level',3);
				$this->assign('exp',$exp-200);
			}else if($exp>=300 && $exp<400){
				$this->assign('level',4);
				$this->assign('exp',$exp-300);
			}else if($exp>=400 && $exp<500){
				$this->assign('level',5);
				$this->assign('exp',$exp-400);
			}else if($exp>=500 && $exp<600){
				$this->assign('level',6);
				$this->assign('exp',$exp-500);
			}else if($exp>=600 && $exp<700){
				$this->assign('level',7);
				$this->assign('exp',$exp-600);
			}
		}
	} 
/*
	 * 获取统计数据
	 * */
	public function getcount(){
		$user=session('user');
		$count_bh=0;$count_zajx=0;$count_qx=0;$count_sh=0;$count_fk=0;$count_qrsh=0;
		$map=array('uid'=>$user['info']['id']);
		$result=apiCall(HomePublicApi::Task_His_Query, array($map));
		for ($i=0; $i <count($result['info']) ; $i++) {
			if($result['info'][$i]['do_status']==4){
				$count_fk=$count_fk+1;
			}
			if($result['info'][$i]['do_status']==3){
				$count_sh=$count_sh+1;
			}
			if($result['info'][$i]['do_status']==8){
				$count_bh=$count_bh+1;
			}
			if($result['info'][$i]['do_status']==1){
				$count_zajx=$count_zajx+1;
			}
			if($result['info'][$i]['do_status']==0){
				$count_qx=$count_qx+1;
			}
			if($result['info'][$i]['do_status']==7){
				$count_qrsh=$count_qrsh+1;
			}
		}
		$this->assign('bh',$count_bh);
		$this->assign('za',$count_zajx);
		$this->assign('qx',$count_qx);
		$this->assign('sh',$count_sh);
		$this->assign('fk',$count_fk);
		$this->assign('qrsh',$count_qrsh);
		 
	}   
	public function posts(){
		
		$order = " post_modified desc ";
		$result = apiCall(AdminPublicApi::Post_QueryNoPaging,array($ma,$order));
		$this->assign('zxgg',$result['info'][0]);
	}
}

