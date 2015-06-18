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
	
	public function index(){
		$user=session('user_sm');
		$this->assign('username',$user['info']['username']);
		$userid=$user['info']['id'];
		$this->display('manager_info');
	}
	public function address(){
		$user=session('user_sm');
		if(IS_GET){
			$uid=$user['info']['id'];
			$map=array(
				'uid'=>$uid,
			);
			$result=apiCall(HomePublicApi::Address_Query, array($map));
//			dump($result);
			$this->assign('address',$result['info']);
			$this->display('manager_address');
		}else{
			$ars=array(
				'uid'=>$user['info']['id'],
				'country'=>"中国",
				'province'=>I('sheng'),
				'city'=>I('shi'),
				'area'=>I('qu'),
				'detail'=>I('address',''),
				'contact_name'=>I('name',''),
				'mobile'=>I('mobile',''),
				'telphone'=>I('phone',''),
				'post_code'=>I('yb',''),
				'create_time'=>time(),
			);
			$result = apiCall(HomePublicApi::Address_Add, array($ars));
			if($result['status']){
				$this->success("操作成功！",U('Home/Usersm/address'));
			}
		}
		
	}
	public function add(){
		$user=session('user_sm');
		$id=$user['info']['id'];
		$year=I('year',0);
		$month=I('month',0);
		$day=I('day',0);
		$bir=$year.'-'.$month.'-'.$day;
//		dump($bir);
		$sm=array(
			'birthday'=>$bir,
			'sex'=>I('sex',0),
			'qq'=>I('qq','1'),
		);
		$smm=array(
			'dtree_job'=>I('zhiye',''),
			'personal_signature'=>I('grqm',''),
			'brief_introduction'=>I('grjj',''),
			'address'=>I('address',''),
		);
//		dump($sm);
//		dump($smm);
		$result = apiCall(HomePublicApi::Member_SaveByID, array($id,$sm));
		if($result['status']){
			$results=apiCall(HomePublicApi::Bbjmember_SaveByID, array($id,$smm));
				if($results['status']){
						$this->success("操作成功！",U('Home/Usersm/index'));
				}
		}
	}
}