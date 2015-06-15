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
/*
 * 官网首页
 */
class IndexController extends HomeController {
	
	
	/**
	 * 注销/退出系统
	 */
	public function logout(){
		session('[destroy]');
		$this->success("退出成功!",U("Home/Index/index"));
	}
	
	public function register(){
		$this->display();
	}
	
	public function index(){
		$this->display();
	}
	/**
	 * 登录地址
	 */
	public function login(){
		if(IS_GET){
			$this->theme($this->theme)->display();
		}else{
			//检测用户
			$verify = I('post.verify', '', 'trim');
			$id = I("post.id",1);
			
			if (!$this -> check_verify($verify, $id)) {
				$this -> error("验证码错误!");
			}
			
			$username = I('post.username', '', 'trim');
			$password = I('post.password', '', 'trim');
			
			$result = apiCall('Uclient/User/login', array('username' => $username, 'password' => $password));
//			dump($result);
			//调用成功
			if ($result['status']) {
				$uid = $result['info'];
				$userinfo = array();
				$result = apiCall('Uclient/User/getInfo', array($uid));
				
				if ($result['status'] && is_array($result['info'])) {
					
					$this->setUserinfo($result['info']);
					
					
					$this -> success("登录成功！", U('Home/TestSys/index'));

				} else {
					LogRecord($result['info'], __FILE__.__LINE__);
					$this -> error("登录失败!");
				}

			} else {
				$this -> error($result['info']);
			}
		}
	}
	
	
	
	
	/**
	 * 校验验证码是否正确
	 * @return Boolean
	 */
	public function check_verify($code, $id = 1) {
		$verify = A('Tool/Verify','Controller');
		return $verify->checkCode($code,$id);
	}
		
	private function setUserinfo($userinfo){
		
		$result = apiCall("Admin/Member/getInfo", array(array('uid'=>$userinfo['id'])));
		
		if($result['status'] && is_array($result['info'])){
			foreach($result['info'] as $key=>$vo){
				$userinfo['member_'.$key] = $vo;
			}
//			$userinfo = array_merge($userinfo,$result['info']);	
		}
		
		//存入 session
		session('global_user_sign', data_auth_sign($userinfo));
		session('global_user', $userinfo);
		session("uid", $userinfo['id']);
		//登录模块
		session("LOGIN_MOD", MODULE_NAME);
				
	}
	
//	public function cate(){
//		$cateid = I('get.cateid',0);
//		$map = array('post_category'=>$cateid,'post_status'=>'publish');
//		
//		$result = apiCall("Home/Datatree/getInfo", array(array('id'=>$cateid)));
//		
//		if(!$result['status']){
//			$this->error($result['info']);
//		}
//		
//		if(is_null($result['info'])){
//			$this->error("该分类不存在!");
//		}
//		
//		//----------------------------------------------------
//		$map = array('parentid'=>getDatatree("POST_CATEGORY"));
//		$cates = apiCall("Home/Datatree/queryNoPaging",array($map));
//		if(!$cates['status']){
//			$this->error($cates['info']);
//		}
//		
//		//---------------------------------------
//		
//		$this->assign("title",$result['info']['name']);
//		$page = array('curpage'=>I('get.p',0),'size'=>6);
//		
//		$result = apiCall("Home/Post/query", array($map,$page));
////		dump($result);
//		if(!$result['status']){
//			$this->error($result['info']);
//		}
//
//		$this->assign("cates",$cates['info']);
//		$this->assign("list",$result['info']['list']);
//		$this->assign("show",$result['info']['show']);
//		$this->display("list");
//		
//	}
//	
//	public function view(){
//		$map = array('parentid'=>getDatatree("POST_CATEGORY"));
//		$cates = apiCall("Home/Datatree/queryNoPaging",array($map));
//		if(!$cates['status']){
//			$this->error($cates['info']);
//		}
//		$id = I('get.id',0);
//		$map = array('id'=>$id);
//		$result = apiCall("Home/Post/getInfo", array($map));
//		if(!$result['status']){
//			$this->error($result['info']);
//		}
//		
//		$com=M('Post');
//		$list = $com->where ('id='.$id)->select();
//		$this->assign('lists',$list);
//		$this->assign("cates",$cates['info']);
//		$this->display();
//	}
	
	
}

