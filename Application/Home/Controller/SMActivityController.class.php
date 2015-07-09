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


class SMActivityController extends CheckLoginController {

	
	public function changestatus(){
		$user=session('user');
		$map=array('uid'=>$user['info']['id']);
		$id=$user['info']['id'];
		$result=apiCall(HomePublicApi::Bbjmember_Query,array($map));
		if($result['info'][0]['task_status']==0){
			$mapa=array('task_status'=>1);
			$result=apiCall(HomePublicApi::Bbjmember_SaveByID,array($id,$mapa));
			if($result['status']){
				$this->success('状态设置成功',U('Home/Index/sm_manager'));
			}
		}else{
			$mapa=array('task_status'=>0);
			$result=apiCall(HomePublicApi::Bbjmember_SaveByID,array($id,$mapa));
			if($result['status']){
				$this->success('状态设置成功',U('Home/Index/sm_manager'));
			}
		}
	}
	
	public function gettask(){
		$user=session('user');
		$mapa=array('task_do_type'=>1,'task_status'=>1,'task_succ_status'=>0);
		$result=apiCall(HomePublicApi::Task_Query,array($mapa));
		if($result['info']==NULL){
			$this->error('暂无可接任务，请稍候再试',U('Home/Index/sm_manager'));
		}else{
			$entity=array(
				'start_time'=>time(),
				'enter_way'=>0,
				'task_cnt'=>1,
				'create_time'=>time(),
				'search_way_id'=>0,
				'task_id'=>$result['info'][0]['id'],
				'uid'=>$user['info']['id'],
			);
			$result3=apiCall(HomePublicApi::TaskPlan_Add,array($entity));
			if($result3['status']){
				$id=$result['info'][0]['id'];
				$map=array('id'=>$result['info']);
				$en=array('task_succ_status'=>1);
				
				$a=apiCall(HomePublicApi::Task_SaveByID,array($id,$en));
				if($a['status']){
				
					$this->success('成功接收任务，正在跳转任务界面',U('Home/Usersm/sm_bbhd'));
				}
				
			}
		}
		
	}
	public function rws(){
		$headtitle = "宝贝街-任务书";
		$this -> assign('head_title', $headtitle);
		$user = session('user');
		$this -> assign('username', $user['info']['username']);
		$id=I('id',0);
		$map=array('task_id'=>$id);
		$result=apiCall(HomePublicApi::TaskHasProduct_Query, array($map));
		$mapp=array('id'=>$result['info'][0]['pid']);
		$mapa=array('pid'=>$result['info'][0]['pid']);
		$return=apiCall(HomePublicApi::Product_Query, array($mapp));
		$returns=apiCall(HomePublicApi::ProductSearchWay_Query, array($mapa));
		$this->assign('pd',$return['info'][0]);
		$this->assign('search',$returns['info'][0]);
		$this->display();
	}
	

}
