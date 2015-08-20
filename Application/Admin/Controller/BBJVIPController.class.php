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
		$resultsm=apiCall(HomePublicApi::Bbjmember_Query, array());
		$resultsj=apiCall(HomePublicApi::Bbjmember_Seller_Query, array());
		$i=0;
		foreach($resultsm['info'] as $a){
			
			$new[$i]['id']=$a['uid'];
			$new[$i]['coins']=$a['coins'];
			$i++;
		}
		foreach($resultsj['info'] as $b){
			$new[$i]['id']=$b['uid'];
			$new[$i]['coins']=$b['coins'];
			$i++;
		}
		$this->assign('new',$new);
		$result1=apiCall(HomePublicApi::UcenterUser_Query, array($maps));
		$result=apiCall(HomePublicApi::FinAccountBalanceHis_QueryAll, array($map,$page));
		$this->assign('user',$result1['info']);
		$this->assign('jilu',$result['info']['list']);
		$this->assign('show',$result['info']['show']);
		$this->display();
	}	
	/*
	 * 商品搜索管理审核
	 * */
	public function checkproduct(){
		$map=array('status'=>2);
		$page = array('curpage' => I('get.p', 0), 'size' => 10 );
		$pro = apiCall(HomePublicApi::ProductSearchWay_QueryAll, array($map,$page));
		$pros = apiCall(HomePublicApi::Product_Query, array($mapp));
		$result=apiCall(HomePublicApi::UcenterUser_Query, array($maps));
		$this->assign('searchway',$pro['info']['list']);
		$this->assign('searchshow',$pro['info']['show']);
		$this->assign('user',$result['info']);
		$this->assign('pros',$pros['info']);
//		dump($pro);
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
			$return2=M('bbjmember')->where('uid='.$uid)->setDec('frozen_money',$money);
			if($return2==0){
				$return1=M('bbjmemberSeller')->where('uid='.$uid)->setDec('frozen_money',$money);
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

	/*
	 * 资金审核失败
	 * */
	public function moneychecksb(){
		$id=I('id','');
		$map=array('id'=>I('id',''));
		$result=apiCall(HomePublicApi::FinAccountBalanceHis_Query, array($map));
		$uid=$result['info'][0]['uid'];
		if($result['info'][0]['dtree_type'] ==1){
			$jl=array('status'=>4,'notes'=>'充值审核失败');
			$results=apiCall(HomePublicApi::FinAccountBalanceHis_SaveByID, array($id,$jl));
			$this->success('操作成功',U('Admin/BBJVIP/index'));
		}else if($result['info'][0]['dtree_type'] ==2){
			$jl=array('status'=>4,'notes'=>'审核失败');
			$results=apiCall(HomePublicApi::FinAccountBalanceHis_SaveByID, array($id,$jl));
			$this->success('操作成功',U('Admin/BBJVIP/index'));
			
		}else if($result['info'][0]['dtree_type'] ==3){
			$money=$result['info'][0]['defray'];
			$return2=M('bbjmember')->where('uid='.$uid)->setDec('frozen_money',$money);
			$return3=M('bbjmember')->where('uid='.$uid)->setInc('coins',$money);
			if($return3==0){
				$return1=M('bbjmemberSeller')->where('uid='.$uid)->setDec('frozen_money',$money);
				$return1=M('bbjmemberSeller')->where('uid='.$uid)->setDec('coins',$money);
				$jl=array('status'=>4,'notes'=>'提现审核失败');
				$results=apiCall(HomePublicApi::FinAccountBalanceHis_SaveByID, array($id,$jl));
				$this->success('操作成功',U('Admin/BBJVIP/index'));
			}else{
				$jl=array('status'=>4,'notes'=>'提现审核失败');
				$results=apiCall(HomePublicApi::FinAccountBalanceHis_SaveByID, array($id,$jl));
				$this->success('操作成功',U('Admin/BBJVIP/index'));
			}
		}
	}
	/**
	 * 会员管理
	 * */
	public function vipmanager(){
		$page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
		$result=apiCall(HomePublicApi::Bbjmember_Seller_QueryAll, array($maps,$page));
		$result1=apiCall(HomePublicApi::UcenterUser_Query, array());
		$map=array('dtree_type'=>4);
		//$order=" create_time desc ";
		$result2=apiCall(HomePublicApi::FinAccountBalanceHis_Query, array($map,$order));
		for($i=0;$i<count($result['info']['list']);$i++){
			for($j=0;$j<count($result2['info']);$j++){
				if($result['info']['list'][$i]['uid']==$result2['info'][$j]['uid']){
					$result['info']['list'][$i]['time']=$result2['info'][$j]['create_time'];
					$result['info']['list'][$i]['text']=$result2['info'][$j]['notes'];
					continue;
				}
			}
			
		}
		//dump($result);
		
		$this->assign('seller',$result['info']['list']);
		$this->assign('sellershow',$result['info']['show']);
		$this->assign('user',$result1['info']);
		$this->assign('jilu',$result2['info']);
		$this->display();
	}
	/*
	 * 撤销vip
	 * */
	public function quxiaovip(){
		$id=I('id',0);
		if($id!=0){
			$entity=array('vip_level'=>0);
			$result=apiCall(HomePublicApi::Bbjmember_Seller_SaveByID, array($id,$entity));
			if($result['status']){
				$this->success('撤销成功',U('Admin/BBJVIP/vipmanager'));
			}else{
				$this->error('撤销失败',U('Admin/BBJVIP/vipmanager'));
			}
		}
		
	}
	public function checksm(){
		$username=I('username','');
		if (!empty($username)) {
			$map['username'] = array('like','%'. $username . '%');
		}
		$page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
		$result=apiCall(HomePublicApi::Bbjmember_QueryAll, array($maps,$page));
		$result1=apiCall(HomePublicApi::UcenterUser_Query, array($map));
		$this->assign('jilu',$result['info']['list']);
		$this->assign('show',$result['info']['show']);
		$this->assign('user',$result1['info']);
		$this->display();
	}
	public function managersm(){
		$username=I('username','');
		if (!empty($username)) {
			$map['username'] = array('like','%'. $username . '%');
		}
		$page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
		$result=apiCall(HomePublicApi::Bbjmember_QueryAll, array($maps,$page));
		$result1=apiCall(HomePublicApi::UcenterUser_Query, array($map));
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
		$username=I('username','');
		if (!empty($username)) {
			$map['username'] = array('like','%'. $username . '%');
		}
		
		$page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
		$result=apiCall(HomePublicApi::Bbjmember_Seller_QueryAll, array($maps,$page));
		$result1=apiCall(HomePublicApi::UcenterUser_Query, array($map));
		$this->assign('jilu',$result['info']['list']);
		$this->assign('show',$result['info']['show']);
		$this->assign('user',$result1['info']);
		$this->display();
	}
	public function managersj(){
		$username=I('username','');
		if (!empty($username)) {
			$map['username'] = array('like','%'. $username . '%');
		}
		
		$page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
		$result=apiCall(HomePublicApi::Bbjmember_Seller_QueryAll, array($maps,$page));
		$result1=apiCall(HomePublicApi::UcenterUser_Query, array($map));
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
			$this->success('审核成功',U('Admin/BBJVIP/checksj'));
		}
	}
	public function checksb(){

		$id=I('id');
		$entity=array('auth_status'=>3);
		$result=apiCall(HomePublicApi::Bbjmember_Seller_SaveByID, array($id,$entity));
//		dump($result);
		if($result['status']){
			$this->success('操作成功',U('Admin/BBJVIP/checksj'));
		}
	}
	
	public function view(){
		$map=array('uid'=>I('id'));
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
	 * 查看搜索
	 * */
	public function searchview(){
		$map=array('id'=>I('id',0));
		$result1 = apiCall(HomePublicApi::ProductSearchWay_Query, array($map));
		$pros = apiCall(HomePublicApi::Product_Query, array($mapp));
		$this->assign('sh',$result1['info'][0]);
		$this->assign('pros',$pros['info']);
		$this->display();
	}
	/*
	 * 审核通过
	 * */
	public function searchsucc(){
		$id=I('id',0);
		if($id!=0){
			$map=array('status'=>1);
			$result = apiCall(HomePublicApi::ProductSearchWay_SaveByID, array($id,$map));
			if($result['status']){
				$this->success('操作成功',U('Admin/BBJVIP/checkproduct'));
			}
		}else{
			$this->error('参数错误');
		}
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
