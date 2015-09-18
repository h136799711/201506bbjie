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
	 * 确认收货
	 * */
	public function sure(){
		$id=I('id',0);
		$uid=I('uid',0);
		if($id!=0){
			$map=array('order_status'=>5,'do_status'=>4);
			$result=apiCall(HomePublicApi::Task_His_SaveByID,array($id,$map));
			if($result['status'] ){
				$ida=array('uid'=>$uid);
				$results = apiCall(HomePublicApi::Task_His_Query, array($ida));
				$entity=array(
					'dtree_type'=>1,
					'content'=>"您的订单".$results['info'][0]['tb_orderid']."已确认收货请尽快返款",
					'title'=>'系统提示',
					'create_time'=>time(),
					'send_time'=>0,
					'from_id'=>0,
					'summary'=>'系统提示，您的订单...',
					'status'=>1,
				);
				
				$return = apiCall(AdminPublicApi::Message_Add, array($entity));
				$msg=array('to_id'=>$uid,'msg_status'=>0,'msg_id'=>$return['info']);
				$returns = apiCall(AdminPublicApi::Msgbox_Add, array($msg));
				$this->success('确认成功，请等待商家返款',U('Home/SMActivity/hd_sened'));
			}else{
				$this->error('未知错误');
			}
		}else{
			$this->error('未知错误');
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
	 * 设置淘宝
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
	 * 驳回后修改订单信息
	 * */
	public function editorder(){
		$id=I('hsid','');
		$user=session('user');
		$spid=I('pid',0);
		$addid=array('id'=>I('address',0));
		$result_address=apiCall(HomePublicApi::Address_Query, array($addid));
		if($result_address['info']==null){
			$this->error('无法获取地址信息，请重试或确认你的地址信息');
		}else{
			$address=$result_address['info'][0];
			
				$entity=array(
					'tb_orderid'=>I('order_num',0),
					'tb_address'=>$address['contact_name'].$address['mobile'].$address['province'].$address['city'].$address['detail'].$address['area'].$address['detail'],
					'tb_price'=>I('zhifu_price','0.00'),
					'notes'=>'无',
					'do_status'=>3,
					'order_status'=>2,
				);
				$result=apiCall(HomePublicApi::Task_His_SaveByID,array($id,$entity));
	//			dump($result);
				if($result['status']){
					$this->success('提交成功！！，请关注任务动态',U('Home/Usersm/sm_bbhd'));
				}else{
					$this->error($result['info']);
				}
			
			
		}
	}
	/*
	 * 确认收货
	 * */
	public function hd_sened(){
		$user = session('user');
		$map = array('uid' => $user['info']['id'], 'do_status' => 7);
		$page = array('curpage' => I('get.p', 0), 'size' => 5);
		$result=apiCall(HomePublicApi::Task_His_QueryAll,array($map,$page));
		for ($i = 0; $i < count($result['info']['list']); $i++) {
			$id = $result['info']['list'][$i]['task_id'];
			$map4 = array('task_id' => $id);
			$result['info']['list'][$i]['hasList']= apiCall(HomePublicApi::TaskHasProduct_Query, array($map4))['info'];
			for($j=0;$j<count($result['info']['list'][$i]['hasList']);$j++){
				$pid = array('id' => $result['info']['list'][$i]['hasList'][$j]['pid']);
				$result['info']['list'][$i]['hasList'][$j]['product'] = apiCall(HomePublicApi::Product_Query, array($pid))['info'][0];
			}
		}
		$tp=$result1['info']['list'];
		$this -> assign('username', $user['info']['username']);
		$result2=apiCall(HomePublicApi::Task_Query,array($mapp));
		$express=apiCall(AdminPublicApi::OrderExpress_Query,array());
		$this->assign('express',$express['info']);
		$this->assign('task',$result2['info']);
		$this->assign('pross',$result['info']['list']);
		$this->assign('show',$result['info']['show']);
		$index=A('Index');
		$index->getcount();
		$index->posts();
//		dump($goods);
		$this -> display();
	}
	/*
	 * 等待确认订单
	 * */
	public function hd_waiting(){
		$headtitle = "宝贝街-等待审核";
		$this -> assign('cs_sf', 'sed');
		$this -> assign('head_title', $headtitle);
		$user = session('user');
		
		$map=array('uid'=>$user['info']['id'],'do_status'=>3);
		$page = array('curpage' => I('get.p', 0), 'size' => 5);
		$result=apiCall(HomePublicApi::Task_His_QueryAll,array($map,$page));
		for ($i = 0; $i < count($result['info']['list']); $i++) {
			$id = $result['info']['list'][$i]['task_id'];
			$map4 = array('task_id' => $id);
			$result['info']['list'][$i]['hasList']= apiCall(HomePublicApi::TaskHasProduct_Query, array($map4))['info'];
			for($j=0;$j<count($result['info']['list'][$i]['hasList']);$j++){
				$pid = array('id' => $result['info']['list'][$i]['hasList'][$j]['pid']);
				$result['info']['list'][$i]['hasList'][$j]['product'] = apiCall(HomePublicApi::Product_Query, array($pid))['info'][0];
			}
		}
		$tp=$result1['info']['list'];
		$this -> assign('username', $user['info']['username']);
		$result2=apiCall(HomePublicApi::Task_Query,array($mapp));
		$this->assign('task',$result2['info']);
		$this->assign('pross',$result['info']['list']);
		$this->assign('show',$result['info']['show']);
		$index=A('Index');
		$index->getcount();
		$index->posts();
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
		
		$map=array('uid'=>$user['info']['id'],'do_status'=>4);
		$page = array('curpage' => I('get.p', 0), 'size' => 5);
		$result=apiCall(HomePublicApi::Task_His_QueryAll,array($map,$page));
		for ($i = 0; $i < count($result['info']['list']); $i++) {
			$id = $result['info']['list'][$i]['task_id'];
			$map4 = array('task_id' => $id);
			$result['info']['list'][$i]['hasList']= apiCall(HomePublicApi::TaskHasProduct_Query, array($map4))['info'];
			for($j=0;$j<count($result['info']['list'][$i]['hasList']);$j++){
				$pid = array('id' => $result['info']['list'][$i]['hasList'][$j]['pid']);
				$result['info']['list'][$i]['hasList'][$j]['product'] = apiCall(HomePublicApi::Product_Query, array($pid))['info'][0];
			}
		}
		$tp=$result1['info']['list'];
		$this -> assign('username', $user['info']['username']);
		$result2=apiCall(HomePublicApi::Task_Query,array($mapp));
		$this->assign('task',$result2['info']);
		$this->assign('pross',$result['info']['list']);
		$this->assign('show',$result['info']['show']);
		$index=A('Index');
		$index->getcount();
		$index->posts();
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
	
		$map=array('uid'=>$user['info']['id'],'do_status'=>8);
		$page = array('curpage' => I('get.p', 0), 'size' => 5);
		$result=apiCall(HomePublicApi::Task_His_QueryAll,array($map,$page));
		for ($i = 0; $i < count($result['info']['list']); $i++) {
			$id = $result['info']['list'][$i]['task_id'];
			$map4 = array('task_id' => $id);
			$result['info']['list'][$i]['hasList']= apiCall(HomePublicApi::TaskHasProduct_Query, array($map4))['info'];
			for($j=0;$j<count($result['info']['list'][$i]['hasList']);$j++){
				$pid = array('id' => $result['info']['list'][$i]['hasList'][$j]['pid']);
				$result['info']['list'][$i]['hasList'][$j]['product'] = apiCall(HomePublicApi::Product_Query, array($pid))['info'][0];
			}
		}
		$tp=$result1['info']['list'];
		$this -> assign('username', $user['info']['username']);
		$result2=apiCall(HomePublicApi::Task_Query,array($mapp));
		$this->assign('task',$result2['info']);
		$this->assign('pross',$result['info']['list']);
		$this->assign('show',$result['info']['show']);
		$index=A('Index');
		$index->getcount();
		$index->posts();
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
		$map=array('uid'=>$user['info']['id'],'do_status'=>0);
		$page = array('curpage' => I('get.p', 0), 'size' => 5);
		$result=apiCall(HomePublicApi::Task_His_QueryAll,array($map,$page));
		for ($i = 0; $i < count($result['info']['list']); $i++) {
			$id = $result['info']['list'][$i]['task_id'];
			$map4 = array('task_id' => $id);
			$result['info']['list'][$i]['hasList']= apiCall(HomePublicApi::TaskHasProduct_Query, array($map4))['info'];
			for($j=0;$j<count($result['info']['list'][$i]['hasList']);$j++){
				$pid = array('id' => $result['info']['list'][$i]['hasList'][$j]['pid']);
				$result['info']['list'][$i]['hasList'][$j]['product'] = apiCall(HomePublicApi::Product_Query, array($pid))['info'][0];
			}
		}
		$tp=$result1['info']['list'];
		$this -> assign('username', $user['info']['username']);
		$result2=apiCall(HomePublicApi::Task_Query,array($mapp));
		$this->assign('task',$result2['info']);
		$this->assign('pross',$result['info']['list']);
		$this->assign('show',$result['info']['show']);
		$index=A('Index');
		$index->posts();
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
		$tpid=I('tpid',0);
		if($tpid!=0){
			$results=M('taskPlan')->where('id='.$tpid)->setInc('yuecount',1);
		}else{
			$this->error('系统未知错误',U('Home/Usersm/sm_bbhd'));
		}
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
		$time=time();
		$tsk=array('uid'=>$user['info']['id'],'do_status'=>1);
		$tsk_his=apiCall(HomePublicApi::Task_His_Query,array($tsk));
		$uid=array('uid'=>$user['info']['id']);
		$usersm=apiCall(HomePublicApi::Bbjmember_Query, array($uid));
		if($usersm['info'][0]['auth_status']!=0){
			if($tsk_his['info']==NULL){
				$map=array('uid'=>$user['info']['id'],'do_status'=>array('neq',2));
//				
				$task_id=apiCall(HomePublicApi::Task_His_Query, array($map));
//				
				$mapa=array('yuecount'=>array('neq',0),'start_time'=>array('lt',time()),'end_time'=>array('gt',time()));
				$result=apiCall(HomePublicApi::TaskPlan_Query,array($mapa));
//				dump($result);
//				dump($mapa);
				if($result['info']==NULL){
					$this->error('暂无可接任务，请稍候再试',U('Home/Index/sm_manager'));
				}else{
					$tid=array('id'=>$result['info'][0]['task_id']);
					$resultq=apiCall(HomePublicApi::Task_Query,array($tid));
					$entity=array(
						'get_task_time'=>time(),
						'do_status'=>1,
						'order_status'=>1,
						'create_time'=>time(),
						'tb_orderid'=>'',
						'tb_address'=>'',
						'tb_price'=>0,
						'task_id'=>$result['info'][0]['task_id'],
						'uid'=>$user['info']['id'],
						'tpid'=>$result['info'][0]['id'],
					);
	//				dump($entity);
					$id=$result['info'][0]['task_id'];
					$et=array('task_status'=>4);
	//				$this->assign('dts',$result['info'][0]['list']);
					$tesk=apiCall(HomePublicApi::Task_SaveByID,array($id,$et));
					$result3=apiCall(HomePublicApi::Task_His_Add,array($entity));
					if($result3['status']){
	//					dump($result['info'][0]['id']);
//						$return1=M('bbjmemberSeller')->where('uid='.$user['info']['id'])->setDec('coins',$zongjia);
						$result=M('taskPlan')->where('id='.$result['info'][0]['id'])->setDec('yuecount',1);
						if($result==1){
							
							$this->success('成功接收任务，正在跳转任务界面',U('Home/Usersm/sm_bbhd',array('tpid'=>$result['info'][0]['id'])));
						}else{
							$this->error('领取任务失败');
						}
						
					}
				}
			}else{
				$this->error('领取任务失败，请先完成未完成的任务',U('Home/Usersm/sm_bbhd'));
			}	
		}else{
			$this->error('用户信息认证完成才可以接任务哦');
		}
		
		
	}
	/*
	 * 查找任务
	 * */
	public function chazhao(){
		$user=session('user');
		$map=array('uid'=>$user['info']['id'],'tb_orderid'=>array('like','%'.I('txt','').'%'));
		$page = array('curpage' => I('get.p', 0), 'size' => 8);
		$result=apiCall(HomePublicApi::Task_His_QueryAll,array($map,$page));
		for ($i = 0; $i < count($result['info']['list']); $i++) {
			$id = $result['info']['list'][$i]['task_id'];
			$map4 = array('task_id' => $id);
			$result['info']['list'][$i]['hasList']= apiCall(HomePublicApi::TaskHasProduct_Query, array($map4))['info'];
			for($j=0;$j<count($result['info']['list'][$i]['hasList']);$j++){
				$pid = array('id' => $result['info']['list'][$i]['hasList'][$j]['pid']);
				$result['info']['list'][$i]['hasList'][$j]['product'] = apiCall(HomePublicApi::Product_Query, array($pid))['info'][0];
			}
		}
		//dump($result);
		$result2=apiCall(HomePublicApi::Task_Query,array($mapp));
		$this->assign('task',$result2['info']);
		$this->assign('pross',$result['info']['list']);
		$this->assign('show',$result['info']['show']);
		$this -> assign('username', $user['info']['username']);
		$this -> assign('cs_sf', 'sed');
		$this -> assign('head_title', $headtitle);	
		$index=A('Index');
		$index->getcount();
		$index->posts();
		$this->display('Usersm/sm_bbhd');
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
		
		$taskhisid=I('taskhisid');
		for ($i=0; $i <count($result['info']) ; $i++) { 
			$mapp=array('id'=>$result['info'][$i]['pid']);
			$mapa=array('pid'=>$result['info'][$i]['pid']);
			$return[]=apiCall(HomePublicApi::Product_Query, array($mapp));
			$returns=apiCall(HomePublicApi::ProductSearchWay_Query, array($mapa));
		}
		$tsmap=array('id'=>$taskhisid);
		$taskhis=apiCall(HomePublicApi::Task_His_Query, array($tsmap));
//		dump($return);
		$this->assign('pd',$return);
		$this->assign('pds',$return[0]['info'][0]);
		$this->assign('searchm ',$returns['info'][0]);
		$map=array('uid'=>$user['info']['id'],'exchange_status'=>1,'orderid'=>0);
		$re=apiCall(HomePublicApi::ExchangeProduct_Query,array($map));
		$this->assign('exchange',$re['info'][0]);
		$maps=array('uid'=>$user['info']['id']);
		$results=apiCall(AdminPublicApi::Wxproduct_QueryNoPaging,array($maps));
		$this->assign('product',$results['info']);
		$this->assign('hsid',$taskhisid);
		$this->assign('do_status',$taskhis['info'][0]['do_status']);
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
		$user=session('user');
		$spid=I('pid',0);
		$orderid=I('order_num',0);
		$addid=array('id'=>I('address',0));
		$result_address=apiCall(HomePublicApi::Address_Query, array($addid));
		if($result_address['info']==null){
			$this->error('无法获取地址信息，请重试或确认你的地址信息');
		}else{
			$address=$result_address['info'][0];
			if($spid==0){
				$entity=array(
					'tb_orderid'=>$orderid,
					'tb_address'=>$address['contact_name'].",".$address['mobile'].",".$address['province'].",".$address['city'].",".$address['detail'].",".$address['area'].",".$address['detail'],
					'tb_price'=>I('zhifu_price','0.00'),
				);
				$result=apiCall(HomePublicApi::Task_His_SaveByID,array($id,$entity));
				if($result['status']){
					$this->success('提交成功！！，请关注任务动态',U('Home/Usersm/sm_bbhd'));
				}else{
					$this->error($result['info']);
				}
			}else{
				$entity=array(
					'tb_orderid'=>I('order_num',0),
					'tb_address'=>$address['contact_name'].$address['mobile'].$address['province'].$address['city'].$address['detail'].$address['area'].$address['detail'],
					'tb_price'=>I('zhifu_price','0.00'),
				);
				$result=apiCall(HomePublicApi::Task_His_SaveByID,array($id,$entity));
				$usersmap=array('id'=>$id);
				$users=apiCall(HomePublicApi::Task_His_Query,array($usersmap));
				$uid=$users['info'][0]['uid'];
				$map=array('uid'=>$uid);
				$exchange=apiCall(HomePublicApi::ExchangeProduct_Query,array($map));
				$ord=array('orderid'=>I('order_num',0));
				$exid=$exchange['info'][0]['id'];
				$exchanges=apiCall(HomePublicApi::ExchangeProduct_SaveByID,array($exid,$ord));
//				
				if($exchanges['status'] ){
					$this->success('提交成功！！，已提交后台审核',U('Home/Usersm/sm_bbhd'));
				}
			}
			
		}
		
		
	}
	
	/*
	 * 系统确认超时
	 * */
	public function timeout(){
		$id=I('id',0);
		$status=array('do_status'=>0);
		$result=apiCall(HomePublicApi::Task_His_SaveByID,array($id,$status));
		$tpid=I('tpid',0);
		if($tpid!=0){
			$results=M('taskPlan')->where('id='.$tpid)->setInc('yuecount',1);
			$Usersm=A("Usersm");  
   			$Usersm->sm_bbhd();  
		}else{
		}
		
	}
	
	 /**
     * 查询订单
     */
    function  searchExpress(){

         $url =C('JUHE_API.EXPRESS_SENDURL');#请求的数据接口URL
        $com=I("com",0);
        $no=I("no",0);
        $params='com='.$com.'&no='.$no.'&dtype=json&key='.C('JUHE_API.EXPRESS_APPKEY');

        $content = $this->juhecurl($url,$params,0);
        if($content){
            $result = json_decode($content,true);
            $result_code = $result['resultcode'];
            if($result_code == 200){
                $this->ajaxReturn($result['result']);
            }else{
                $this->ajaxReturn("订单查询失败,错误ID号：".$result_code);
            }
        }else{
            $this->ajaxReturn("订单查询失败");
        }
    }
	
	
	/**
     * 查询订单
     */
    function  searchExpressHtml(){

        /*$url=C('JUHE_API.EXPRESS_SENDURL'); #请求的数据接口URL
        //dump($url);
        $com=I("com",0);
        $no=I("no",0);
        $params='com='.$com.'&no='.$no.'&dtype=json&key='.C('JUHE_API.EXPRESS_APPKEY');
        $content = $this->juhecurl($url,$params,0);
        if($content){
            $result = json_decode($content,true);
            //rsort($result['result']['list']);
            $this->assign('result',$result);
        }else{

        }*/


        /******** 测试数据*************/
       $result['error_code']=0;
        $list[]= array(
                "datetime"=>"2013-06-25  10:44:05",
                "remark"=>"已收件",
                "zone"=>"台州市"
        );
        $list[]= array(
                "datetime"=> "2013-06-25  11:05:21",
                "remark"=>"快件在 台州  ,准备送往下一站 台州集散中心  ",
                "zone"=> "台州市"

        );
        $list[]= array(
            "datetime"=>"2013-06-25  20:36:02",
            "remark"=>"快件在 台州集散中心  ,准备送往下一站 台州集散中心 ",
            "zone"=>"台州市"
        );
        $list[]= array(
            "datetime"=>"2013-06-25  21:17:36",
            "remark"=>"快件在 台州集散中心 ,准备送往下一站 杭州集散中心",
            "zone"=>"台州市"
        );
        $list[]= array(
            "datetime"=>"2013-06-26  12:20:00",
            "remark"=>"快件在 杭州集散中心  ,准备送往下一站 西安集散中心 ",
            "zone"=>"杭州市"
        );
        $list[]= array(
            "datetime"=>"2013-06-27  05:48:42",
            "remark"=>"快件在 西安集散中心 ,准备送往下一站 西安  ",
            "zone"=>"西安市/咸阳市"
        );

        $list[]= array(
            "datetime"=>"2013-06-27  08:03:03",
            "remark"=>"正在派件.. ",
            "zone"=>"西安市/咸阳市"
        );

        $list[]= array(
            "datetime"=>"2013-06-27  08:51:33",
            "remark"=>"派件已签收",
            "zone"=>"西安市/咸阳市"
        );

        $list[]= array(
            "datetime"=>"2013-06-27 08:51",
            "remark"=>"派件已签收",
            "zone"=>"西安市/咸阳市"
        );

        $list[]= array(
            "datetime"=>"2013-06-27  08:51:33",
            "remark"=>"签收人是：已签收",
            "zone"=>"西安市/咸阳市"
        );


        rsort($list); //数组倒序
        $result['result']=array(
           "company"=>"顺丰",
           "com"=>"sf",
           "no"=>"575677355677",
           "status"=>1,
            "list"=>$list
        );
        $this->assign("result",$result);
        /******** 测试数据*************/
        $this ->display();
    }
	
	
	

/*
      ***请求接口，返回JSON数据
      ***@url:接口地址
      ***@params:传递的参数
      ***@ispost:是否以POST提交，默认GET
  */
    function juhecurl($url,$params=false,$ispost=0){
        $httpInfo = array();
        $ch = curl_init();

        curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_0 );
        curl_setopt( $ch, CURLOPT_USERAGENT , 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22' );
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 30 );
        curl_setopt( $ch, CURLOPT_TIMEOUT , 30);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
        if( $ispost )
        {
            curl_setopt( $ch , CURLOPT_POST , true );
            curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
            curl_setopt( $ch , CURLOPT_URL , $url );
        }
        else
        {
            if($params){
                curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
            }else{
                curl_setopt( $ch , CURLOPT_URL , $url);
            }
        }
        $response = curl_exec( $ch );
        if ($response === FALSE) {
            #echo "cURL Error: " . curl_error($ch);
            return false;
        }
        $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
        $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
        curl_close( $ch );
        return $response;
    }
	

}
