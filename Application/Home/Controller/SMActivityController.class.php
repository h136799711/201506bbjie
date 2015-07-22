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
	 * 改变任务金
	 * */
	public function taskmoney(){
		$user = session('user');
		$id=$user['info']['id'];
		$entity=array('daily_task_money'=>I('tmoney','0'));
		$result = apiCall(HomePublicApi::Bbjmember_SaveByID,array($id,$entity));
		if($result['status']){
			$this->success('修改成功',U('Home/Usersm/manager_rw'));
		}
	}
	/*
	 * */
	public function settaobao(){
		$user = session('user');
		$id=$user['info']['id'];
		$entity=array('taobao_account'=>I('taobao','无'));
		$result = apiCall(HomePublicApi::Bbjmember_SaveByID,array($id,$entity));
		if($result['status']){
			$this->success('修改成功',U('Home/Usersm/manager_rw'));
		}
		
	}
	/*
	 * 兑换商品
	 * */
	public function exchange(){
		$user=session('user');
		$pid=I('pid');
		$entity=array('create_time'=>time(),'update_time'=>time(),'p_id'=>$pid,'uid'=>$user['info']['id'],'sku_notes'=>I('sinfo',''));
		$result=apiCall(HomePublicApi::ExchangeProduct_Add,array($entity));
		if($result['status']){
			$this->success('兑换成功！正在前往个人中心！',U('Home/Usersm/sm_dhsp'));
		}
		
	}
	/*
	 * 取消兑换
	 * */
	public function quxiaodh(){
		$id=I('id',0);
		$map=array('exchange_status'=>2);
		$result=apiCall(HomePublicApi::ExchangeProduct_SaveByID,array($id,$map));
		if($result['status']){
			$this->success('已取消兑换，请选择其他商品',U('Home/Usersm/sm_dhsp'));
		}
	}
	/*
	 * 等待确认订单
	 * */
	public function hd_waiting(){
		$headtitle = "宝贝街-等待审核";
		$this -> assign('cs_sf', 'sed');
		$this -> assign('head_title', $headtitle);
		$user = session('user');
		$order = " post_modified desc ";
		$result = apiCall(AdminPublicApi::Post_QueryNoPaging,array($ma,$order));
		$map=array('uid'=>$user['info']['id'],'do_status'=>3);
		$page = array('curpage' => I('get.p', 0), 'size' => 5);
		$result1=apiCall(HomePublicApi::Task_His_QueryAll,array($map,$page));
		
		for ($i=0; $i <count($result1['info']['list']) ; $i++) { 
			$mapp=array('id'=>$result1['info']['list'][$i]['task_id']);
			$result2[]=apiCall(HomePublicApi::Task_Query,array($mapp));
			$map3=array('task_id'=>$result2[$i]['info'][0]['id']);
			$result3[]=apiCall(HomePublicApi::TaskHasProduct_Query,array($map3));
		}
		$tp=$result1['info']['list'];
		$this->assign('zxgg',$result['info'][0]);
		$this -> assign('username', $user['info']['username']);
		$goods=apiCall(HomePublicApi::Product_Query,array($ddd));
		$this->assign('goods',$goods['info']);
		$this->assign('tspro',$result3);
		$this->assign('tp',$result1['info']['list']);
		$this->assign('show',$result1['info']['show']);
		$this->assign('task',$result2);
		$index=A('Index');
		$index->getcount();
//		dump($result2);
		$this -> display();
	}

	/*
	 * 等待确认返款
	 * */
	public function hd_remoney(){
		$headtitle = "宝贝街-等待返款";
		$this -> assign('cs_sf', 'sed');
		$this -> assign('head_title', $headtitle);
		$user = session('user');
		$order = " post_modified desc ";
		$result = apiCall(AdminPublicApi::Post_QueryNoPaging,array($ma,$order));
		$map=array('uid'=>$user['info']['id'],'do_status'=>4);
		$page = array('curpage' => I('get.p', 0), 'size' => 5);
		$result1=apiCall(HomePublicApi::Task_His_QueryAll,array($map,$page));
		
		for ($i=0; $i <count($result1['info']['list']) ; $i++) { 
			$mapp=array('id'=>$result1['info']['list'][$i]['task_id']);
			$result2[]=apiCall(HomePublicApi::Task_Query,array($mapp));
			$map3=array('task_id'=>$result2[$i]['info'][0]['id']);
			$result3[]=apiCall(HomePublicApi::TaskHasProduct_Query,array($map3));
			
		}
		$tp=$result1['info']['list'];
		$this->assign('zxgg',$result['info'][0]);
		$this -> assign('username', $user['info']['username']);
		$goods=apiCall(HomePublicApi::Product_Query,array($ddd));
		$this->assign('goods',$goods['info']);
		$this->assign('tspro',$result3);
		$this->assign('tp',$result1['info']['list']);
		$this->assign('show',$result1['info']['show']);
		$this->assign('task',$result2);
		$index=A('Index');
		$index->getcount();
		$this -> display();
	}

	/*
	 * 被驳回
	 * */
	public function hd_bh(){
		$headtitle = "宝贝街-被驳回";
		$this -> assign('cs_sf', 'sed');
		$this -> assign('head_title', $headtitle);
		$user = session('user');
		$order = " post_modified desc ";
		$result = apiCall(AdminPublicApi::Post_QueryNoPaging,array($ma,$order));
		$map=array('uid'=>$user['info']['id'],'do_status'=>8);
		$page = array('curpage' => I('get.p', 0), 'size' => 5);
		$result1=apiCall(HomePublicApi::Task_His_QueryAll,array($map,$page));
		
		$this->assign('zxgg',$result['info'][0]);
		for ($i=0; $i <count($result1['info']['list']) ; $i++) { 
			$mapp=array('id'=>$result1['info']['list'][$i]['task_id']);
			$result2[]=apiCall(HomePublicApi::Task_Query,array($mapp));
			$map3=array('task_id'=>$result2[$i]['info'][0]['id']);
			$result3[]=apiCall(HomePublicApi::TaskHasProduct_Query,array($map3));
			
		}
		$tp=$result1['info']['list'];
		$this->assign('zxgg',$result['info'][0]);
		$this -> assign('username', $user['info']['username']);
		$goods=apiCall(HomePublicApi::Product_Query,array($ddd));
		$this->assign('goods',$goods['info']);
		$this->assign('tspro',$result3);
		$this->assign('tp',$result1['info']['list']);
		$this->assign('show',$result1['info']['show']);
		$this->assign('task',$result2);
		$index=A('Index');
		$index->getcount();
		$this -> display();
	}

/*
	 * 取消放弃
	 * */
	public function hd_quxiao(){
		$headtitle = "宝贝街-取消放弃";
		$this -> assign('cs_sf', 'sed');
		$this -> assign('head_title', $headtitle);
		$user = session('user');
		$order = " post_modified desc ";
		$result = apiCall(AdminPublicApi::Post_QueryNoPaging,array($ma,$order));
		$map=array('uid'=>$user['info']['id'],'do_status'=>0);
		$page = array('curpage' => I('get.p', 0), 'size' => 5);
		$result1=apiCall(HomePublicApi::Task_His_QueryAll,array($map,$page));
		for ($i=0; $i <count($result1['info']['list']) ; $i++) { 
			$mapp=array('id'=>$result1['info']['list'][$i]['task_id']);
			$result2[]=apiCall(HomePublicApi::Task_Query,array($mapp));
			$map3=array('task_id'=>$result2[$i]['info'][0]['id']);
			$result3[]=apiCall(HomePublicApi::TaskHasProduct_Query,array($map3));
			
		}
		$tp=$result1['info']['list'];
		$this->assign('zxgg',$result['info'][0]);
		$this -> assign('username', $user['info']['username']);
		$goods=apiCall(HomePublicApi::Product_Query,array($ddd));
		$this->assign('goods',$goods['info']);
		$this->assign('tspro',$result3);
		$this->assign('tp',$result1['info']['list']);
		$this->assign('show',$result1['info']['show']);
		$this->assign('task',$result2);
		$index=A('Index');
		$index->getcount();
		$this -> display();
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
		$task_map=array('id'=>$id);
		$map=array('do_status'=>3);
		$task=apiCall(HomePublicApi::Task_His_Query,array($task_map));
		$result=apiCall(HomePublicApi::Task_His_SaveByID,array($id,$map));
		$ids=$task['info'][0]['task_id'];
		$map2=array('task_status'=>4);
		$result2=apiCall(HomePublicApi::Task_SaveByID,array($ids,$map2));
		if($result['status'] &&$result2['status'] ){
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
		$tsk=array('uid'=>$user['info']['id'],'do_status'=>1);
		$tsk_his=apiCall(HomePublicApi::Task_His_Query,array($tsk));
		if($tsk_his['info']==NULL){
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
				$id=$result['info'][0]['id'];
				$et=array('task_status'=>4);
//				$this->assign('dts',$result['info'][0]['list']);
				$tesk=apiCall(HomePublicApi::Task_SaveByID,array($id,$et));
				$result3=apiCall(HomePublicApi::Task_His_Add,array($entity));
				if($result3['status']){
					$this->success('成功接收任务，正在跳转任务界面',U('Home/Usersm/sm_bbhd'));
				}
			}
		}else{
			$this->error('领取任务失败，请先完成未完成的任务',U('Home/Usersm/sm_bbhd'));
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

		$map=array('uid'=>$user['info']['id'],'exchange_status'=>1);
		$re=apiCall(HomePublicApi::ExchangeProduct_Query,array($map));
		$this->assign('exchange',$re['info'][0]);
		$maps=array('uid'=>$user['info']['id']);
		$results=apiCall(AdminPublicApi::Wxproduct_QueryNoPaging,array($maps));
		$this->assign('product',$results['info']);
		$this->display();
	}
	/*
	 * 确定兑换
	 * */
	public function duihuanok(){
		$id=I('id',0);
		$map=array('exchange_status'=>1);
		$result=apiCall(HomePublicApi::ExchangeProduct_SaveByID,array($id,$map));
		if($result['status']){
			$this->success('兑换已确认，请等待分配任务。。',U('Home/Usersm/sm_dhsp'));
		}
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
	
	/*
	 * 系统确认超时
	 * */
	public function timeout(){
		$id=I('id',0);
		$status=array('do_status'=>0);
		$result=apiCall(HomePublicApi::Task_His_SaveByID,array($id,$status));
		$Usersm=A("Usersm");  
   		$Usersm->sm_bbhd();  
	}
	

}
