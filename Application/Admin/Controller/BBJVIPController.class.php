<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 青<99701759@qq.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Admin\Controller;
use Home\Api\HomePublicApi;

class BBJVIPController extends AdminController{
	
	public function index(){
		$page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
		$map=array('status'=>2);
		$result1=apiCall(HomePublicApi::UcenterUser_Query, array($maps));
		$result=apiCall(HomePublicApi::FinAccountBalanceHis_QueryAll, array($map,$page));
		$this->assign('user',$result1['info']);
		$this->assign('jilu',$result['info']['list']);
		$this->assign('show',$result['info']['show']);
		$this->display();
	}
	public function check(){
		$id=I('id','');
		$map=array('id'=>I('id',''));
		$result=apiCall(HomePublicApi::FinAccountBalanceHis_Query, array($map));
		$uid=$result['info'][0]['uid'];
		if($result['info'][0]['dtree_type'] ==1){
			$money=$result['info'][0]['income'];
			$return=M('bbjmember')->where('uid='.$uid)->setInc('coins',$money);
			if($return==0){
				$return=M('bbjmemberSeller')->where('uid='.$uid)->setInc('coins',$money);
				$jl=array('status'=>1);
				$results=apiCall(HomePublicApi::FinAccountBalanceHis_SaveByID, array($id,$jl));
				$this->success('审核成功',U('Admin/BBJVIP/index'));
			}else{
				$jl=array('status'=>1);
				$results=apiCall(HomePublicApi::FinAccountBalanceHis_SaveByID, array($id,$jl));
				$this->success('审核成功',U('Admin/BBJVIP/index'));
			}
			
		}else if($result['info'][0]['dtree_type'] ==2){
			$money=$result['info'][0]['income'];
			$return=M('bbjmember')->where('uid='.$uid)->setInc('coins',$money);
			if($return==0){
				$return=M('bbjmemberSeller')->where('uid='.$uid)->setInc('coins',$money);
				$jl=array('status'=>1);
				$results=apiCall(HomePublicApi::FinAccountBalanceHis_SaveByID, array($id,$jl));
				$this->success('审核成功',U('Admin/BBJVIP/index'));
			}else{
				$jl=array('status'=>1);
				$results=apiCall(HomePublicApi::FinAccountBalanceHis_SaveByID, array($id,$jl));
				$this->success('审核成功',U('Admin/BBJVIP/index'));
			}
		}else if($result['info'][0]['dtree_type'] ==3){
			$money=$result['info'][0]['defray'];
			$return2=M('bbjmember')->where('uid='.$uid)->setDec('coins',$money);
			if($return2==0){
				$return1=M('bbjmemberSeller')->where('uid='.$uid)->setDec('coins',$money);
				$jl=array('status'=>1);
				$results=apiCall(HomePublicApi::FinAccountBalanceHis_SaveByID, array($id,$jl));
				$this->success('审核成功',U('Admin/BBJVIP/index'));
			}else{
				$jl=array('status'=>1);
				$results=apiCall(HomePublicApi::FinAccountBalanceHis_SaveByID, array($id,$jl));
				$this->success('审核成功',U('Admin/BBJVIP/index'));
			}
		}
	}
	public function checksm(){
		$map=array('auth_status'=>0);
		$page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
		$result=apiCall(HomePublicApi::Bbjmember_QueryAll, array($map,$page));
		$result1=apiCall(HomePublicApi::UcenterUser_Query, array($maps));
		$this->assign('jilu',$result['info']['list']);
		$this->assign('show',$result['info']['show']);
		$this->assign('user',$result1['info']);
		$this->display();
	}
	public function managersm(){
		
		$page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
		$result=apiCall(HomePublicApi::Bbjmember_QueryAll, array($page));
		$result1=apiCall(HomePublicApi::UcenterUser_Query, array($maps));
		$this->assign('jilu',$result['info']['list']);
		$this->assign('show',$result['info']['show']);
		$this->assign('user',$result1['info']);
		$this->display();
	}
	public function delete_sm(){
		$uid=array('uid'=>I('id'));
		$id=array('id'=>I('id'));
		$result=apiCall(HomePublicApi::Bbjmember_Del, array($uid));
		if($result['status']){
			$result1=apiCall(HomePublicApi::UcenterUser_Del, array($id));
			if($result1['status']){
				$result2=apiCall(HomePublicApi::Member_Del, array($uid));
				if($result2['status']){
					$this->success('删除成功',U('Admin/BBJVIP/managersm'));
				}
			}
		
		}
	}
	
	public function checksj(){
		$map=array('auth_status'=>0);
		$page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
		$result=apiCall(HomePublicApi::Bbjmember_Seller_QueryAll, array($map,$page));
		$result1=apiCall(HomePublicApi::UcenterUser_Query, array($maps));
		$this->assign('jilu',$result['info']['list']);
		$this->assign('show',$result['info']['show']);
		$this->assign('user',$result1['info']);
		$this->display();
	}
	public function managersj(){
		$page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
		$result=apiCall(HomePublicApi::Bbjmember_Seller_QueryAll, array($page));
		$result1=apiCall(HomePublicApi::UcenterUser_Query, array($maps));
		$this->assign('jilu',$result['info']['list']);
		$this->assign('show',$result['info']['show']);
		$this->assign('user',$result1['info']);
		$this->display();
	}
	
	public function delete_sj(){
		$uid=array('uid'=>I('id'));
		$id=array('id'=>I('id'));
		$result=apiCall(HomePublicApi::Bbjmember_Seller_Del, array($uid));
		if($result['status']){
			$result1=apiCall(HomePublicApi::UcenterUser_Del, array($id));
			if($result1['status']){
				$result2=apiCall(HomePublicApi::Member_Del, array($uid));
				if($result2['status']){
					$this->success('删除成功',U('Admin/BBJVIP/managersj'));
				}
			}
		}
	}
	public function checksuccess(){

		$id=I('id');
		$entity=array('auth_status'=>1);
		$result=apiCall(HomePublicApi::Bbjmember_SaveByID, array($id,$entity));
//		dump($result);
		if($result['status']){
			$this->success('审核成功',U('Admin/BBJVIP/checksm'));
		}
	}
	public function checksuccesssj(){

		$id=I('id');
		$entity=array('auth_status'=>1);
		$result=apiCall(HomePublicApi::Bbjmember_Seller_SaveByID, array($id,$entity));
//		dump($result);
		if($result['status']){
			$this->success('审核成功',U('Admin/BBJVIP/checksm'));
		}
	}
	public function checkf(){
		$this->error('认证失败');
	}
	
	public function view(){
		$map=array('id'=>I('id'));
		$result=apiCall(HomePublicApi::Bbjmember_Query, array($map));
		$result1=apiCall(HomePublicApi::UcenterUser_Query, array($maps));
		if($result['info']=='' ||$result['info']==null ){
			$result=apiCall(HomePublicApi::Bbjmember_Seller_Query, array($map));
			$result1=apiCall(HomePublicApi::UcenterUser_Query, array($maps));
			if($result['status']){
				$this->assign('user',$result1['info']);
				$this->assign('entity',$result['info'][0]);
				$this->display();
			}
		}else{
			$this->assign('user',$result1['info']);
			$this->assign('entity',$result['info'][0]);
			$this->display();
		}
	}
	/*
	 * 所有任务
	 * */
	public function alltask(){
		$taskname=I('taskname','');
		if (!empty($taskname)) {
			$map['task_name'] = array('like','%'. $taskname . '%');
		}
		$page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
//		$order = " create_time desc ";
		$result = apiCall(HomePublicApi::Task_QueryAll, array($map, $page, $order));
//		dump($result);
		$this->assign('list',$result['info']['list']);
		$this->assign('show',$result['info']['show']);
		$this->display();
	}
	/*
	 * 任务领取管理
	 * */
	public function taskgetview(){
		$map=array('task_id'=>I('id',0));
		$page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
//		$order = " create_time desc ";
		$result = apiCall(HomePublicApi::Task_His_QueryAll, array($map, $page, $order));
		$result1 = apiCall(HomePublicApi::Task_Query, array());
//		dump($result);dump($result1);
		$this->assign('list',$result['info']['list']);
		$this->assign('task',$result1['info']);
		$this->assign('show',$result['info']['show']);
		$this->display();
	}
	/*
	 * 删除任务
	 * */
	public function delete(){
		$id=I('id',0);
		$map=array('order_status'=>0);
		$result = apiCall(HomePublicApi::Task_SaveByID, array($id,$map));
		dump($result);
	}
}
