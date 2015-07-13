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

	/*
	 * 改变状态
	 * */
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
	/*
	 * 取消任务
	 * */
	public function qxtask(){
		$id=I('id',0);
		$map=array('do_status'=>0);
		$result=apiCall(HomePublicApi::Task_His_SaveByID,array($id,$map));
		if($result['status']){
			$this->success('任务操作成功',U('Home/Usersm/sm_bbhd'));
		}else{
			$this->error('系统未知错误',U('Home/Usersm/sm_bbhd'));
		}
	}
	/*
	 * 确认任务
	 * */
	public function qrtask(){
		$id=I('id',0);
		$map=array('do_status'=>3);
		$result=apiCall(HomePublicApi::Task_His_SaveByID,array($id,$map));
		if($result['status']){
			$this->success('任务操作成功',U('Home/Usersm/sm_bbhd'));
		}else{
			$this->error('系统未知错误',U('Home/Usersm/sm_bbhd'));
		}
	}
	/**
	 * 领取任务
	 * */
	public function gettask(){
		$user=session('user');
		$mapa=array('task_do_type'=>1,'task_status'=>1,);
		$result=apiCall(HomePublicApi::Task_Query,array($mapa));
		if($result['info']==NULL){
			$this->error('暂无可接任务，请稍候再试',U('Home/Index/sm_manager'));
		}else{
			$entity=array(
				'get_task_time'=>time(),
				'do_status'=>1,
				'create_time'=>time(),
				'tb_orderid'=>'',
				'tb_address'=>'',
				'tb_price'=>'0.00',
				'task_id'=>$result['info'][0]['id'],
				'uid'=>$user['info']['id'],
			);
			$result3=apiCall(HomePublicApi::Task_His_Add,array($entity));
			if($result3['status']){
				$this->success('成功接收任务，正在跳转任务界面',U('Home/Usersm/sm_bbhd'));
			}
		}
		
	}
	public function rws(){
		$headtitle = "宝贝街-任务书";
		$this -> assign('head_title', $headtitle);
		$user = session('user');
		$uid=array('uid'=>$user['info']['id']);
		$address=apiCall(HomePublicApi::Address_Query, array($uid));
		$this->assign('address',$address['info']);
		$result_user=apiCall(HomePublicApi::Bbjmember_Query, array($uid));
		$this->assign('user',$result_user['info'][0]);
		$this -> assign('username', $user['info']['username']);
		$id=I('id',0);
		$tk=array('id'=>$id);
		$map=array('task_id'=>$id);
		$task=apiCall(HomePublicApi::Task_Query, array($tk));
		$this->assign('task',$task['info'][0]);
		$result=apiCall(HomePublicApi::TaskHasProduct_Query, array($map));
		$this->assign('jianshu',$result['info'][0]['num']);
		$mapp=array('id'=>$result['info'][0]['pid']);
		$mapa=array('pid'=>$result['info'][0]['pid']);
		$return=apiCall(HomePublicApi::Product_Query, array($mapp));
		$returns=apiCall(HomePublicApi::ProductSearchWay_Query, array($mapa));
		$taskhisid=I('taskhisid');
		$this->assign('hsid',$taskhisid);
		$this->assign('pd',$return['info'][0]);
		$this->assign('search',$returns['info'][0]);
//		dump($task);
		$this->display();
	}
	/*
	 * 保存任务订单号
	 * */
	public function savedd(){
		$id=I('hsid','');
		$entity=array(
			'tb_orderid'=>I('order_num',''),
			'tb_address'=>I('address',''),
			'tb_price'=>I('zhifu_price','0.00'),
		);
		
		$result=apiCall(HomePublicApi::Task_His_SaveByID,array($id,$entity));
//		dump($result);
		if($result['status']){
			$this->success('提交成功！！，请关注任务动态',U('Home/Usersm/sm_bbhd'));
		}else{
			$this->error($result['info']);
		}
	}
	
	

}
