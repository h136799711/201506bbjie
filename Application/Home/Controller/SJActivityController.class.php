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
 * 资金提现
 */
class SJActivityController extends CheckLoginController {

	/*
	 * 淘宝活动
	 * */
	public function sj_tbhd() {
		$user = session('user');
		$page = array('curpage' => I('get.p', 0), 'size' => 4);
		$map1 = array('uid' => $user['info']['id'], 'task_status' => array('in',array(1,4)));
		$order=array('create_time'=>'desc');
		$result = apiCall(HomePublicApi::Task_QueryAll, array($map1,$page,$order));
		$map2 = array('uid' => $user['info']['id'], 'task_status' => 2);
		$resultzt = apiCall(HomePublicApi::Task_QueryAll, array($map2,$page,$order));
		$headtitle = "宝贝街-活动";
		$this -> assign('head_title', $headtitle);
		$user = session('user');
		for ($i = 0; $i < count($result['info']['list']); $i++) {
				$id = $result['info']['list'][$i]['id'];
				$map4 = array('task_id' => $id);
				$result['info']['list'][$i]['hasList']= apiCall(HomePublicApi::TaskHasProduct_Query, array($map4))['info'];
				for($j=0;$j<count($result['info']['list'][$i]['hasList']);$j++){
					$pid = array('id' => $result['info']['list'][$i]['hasList'][$j]['pid']);
					$result['info']['list'][$i]['hasList'][$j]['product'] = apiCall(HomePublicApi::Product_Query, array($pid))['info'][0];
				}
		}
		for ($i = 0; $i < count($resultzt['info']['list']); $i++) {
				$id = $result['info']['list'][$i]['id'];
				$map4 = array('task_id' => $id);
				$resultzt['info']['list'][$i]['hasList']= apiCall(HomePublicApi::TaskHasProduct_Query, array($map4))['info'];
				for($j=0;$j<count($result['info']['list'][$i]['hasList']);$j++){
					$pid = array('id' => $result['info']['list'][$i]['hasList'][$j]['pid']);
					$resultzt['info']['list'][$i]['hasList'][$j]['product'] = apiCall(HomePublicApi::Product_Query, array($pid))['info'][0];
				}
		}
//		dump($result);//dump($producta);dump($results);
		
		$this->assign('task',$result['info']['list']);
		$this->assign('show',$result['info']['show']);
		$this->assign('taskzt',$resultzt['info']['list']);
		$this->assign('showzt',$resultzt['info']['show']);
		$this -> assign('username', $user['info']['username']);
		
		$sj=A('usersj');
		$sj->is_auth();
		$sj->getcount();
		
		//		dump($results);
		$this -> display();
	}
	public function sj_tbzt() {
		$user = session('user');
		$page = array('curpage' => I('get.p', 0), 'size' => 4);
		$map1 = array('uid' => $user['info']['id'], 'task_status' => array('in',array(1,4)));
		$order=array('create_time'=>'desc');
		$result = apiCall(HomePublicApi::Task_QueryAll, array($map1,$page,$order));
		$map2 = array('uid' => $user['info']['id'], 'task_status' => 2);
		$resultzt = apiCall(HomePublicApi::Task_QueryAll, array($map2,$page,$order));
		$headtitle = "宝贝街-活动";
		$this -> assign('head_title', $headtitle);
		$user = session('user');
		for ($i = 0; $i < count($result['info']['list']); $i++) {
				$id = $result['info']['list'][$i]['id'];
				$map4 = array('task_id' => $id);
				$result['info']['list'][$i]['hasList']= apiCall(HomePublicApi::TaskHasProduct_Query, array($map4))['info'];
				for($j=0;$j<count($result['info']['list'][$i]['hasList']);$j++){
					$pid = array('id' => $result['info']['list'][$i]['hasList'][$j]['pid']);
					$result['info']['list'][$i]['hasList'][$j]['product'] = apiCall(HomePublicApi::Product_Query, array($pid))['info'][0];
				}
		}
		for ($i = 0; $i < count($resultzt['info']['list']); $i++) {
				$id = $result['info']['list'][$i]['id'];
				$map4 = array('task_id' => $id);
				$resultzt['info']['list'][$i]['hasList']= apiCall(HomePublicApi::TaskHasProduct_Query, array($map4))['info'];
				for($j=0;$j<count($result['info']['list'][$i]['hasList']);$j++){
					$pid = array('id' => $result['info']['list'][$i]['hasList'][$j]['pid']);
					$resultzt['info']['list'][$i]['hasList'][$j]['product'] = apiCall(HomePublicApi::Product_Query, array($pid))['info'][0];
				}
		}
//		dump($result);//dump($producta);dump($results);
		
		$this->assign('task',$result['info']['list']);
		$this->assign('show',$result['info']['show']);
		$this->assign('taskzt',$resultzt['info']['list']);
		$this->assign('showzt',$resultzt['info']['show']);
		$this -> assign('username', $user['info']['username']);
		
		$sj=A('usersj');
		$sj->is_auth();
		$sj->getcount();
		
		//		dump($results);
		$this -> display();
	}
	/*
	 * 退回订单
	 * */
	public function back(){
		$id=I('id',0);
		$entity=array('notes'=>I('text','无'),'do_status'=>8,'order_status'=>12);
		if($id!=0){
			$scount=apiCall(HomePublicApi::Task_His_SaveByID, array($id,$entity));
			if($scount['status']){
				$this->success('订单退回成功',U('Home/SJActivity/sj_waiting'));
			}else{
				$this->error('未知错误');
			}
		}else{
				$this->error('未知错误');
		}
	}
	/*
	 * 全部试民
	 * */
	public function alluser(){
		$user = session('user');
		$headtitle = "宝贝街-所有试民";
		$this -> assign('head_title', $headtitle);
		$this -> assign('username', $user['info']['username']);
		$id=array('task_id'=>I('id',0));
		$mm=array('id'=>I('id',0));
		$results = apiCall(HomePublicApi::TaskHasProduct_Query, array($map4));
		$result=apiCall(HomePublicApi::Task_His_Query, array($id));
		$result_user=apiCall(HomePublicApi::Bbjmember_Query, array());
		$result_tast = apiCall(HomePublicApi::Task_Query, array($mm));
		$producta = apiCall(HomePublicApi::Product_Query, array($pid));
		$this -> assign('jihuas', $results['info']);
		$this->assign('task',$result_tast['info'][0]);
		$this->assign('user',$result_user['info']);
		$this->assign('taskplan',$result['info']);
		$this -> assign('pro', $producta['info']);
//		dump($result_user);dump($result);
		$sj=A('usersj');
		$sj->is_auth();
		$this->display();
	}
	/**
	 * 任务计划
	 * */
	 public function task_play(){
	 	$mm = array('id' => I('id',0));
		$mms = array('task_id' => I('id',0));
		$map = array('task_id' => I('id',0));
		$result = apiCall(HomePublicApi::TaskHasProduct_Query, array($map));
		$mapa = array('pid' => $result['info'][0]['pid']);
		$returns = apiCall(HomePublicApi::ProductSearchWay_Query, array($mapa));
		if ($returns['info'] == NULL) {
			$this -> error('如果发放任务，请先创建搜索', U('Home/SJActivity/sj_tbhd'));
		}
		$result_tast = apiCall(HomePublicApi::Task_Query, array($mm));
		$result_play = apiCall(HomePublicApi::TaskPlan_Query, array($mms));
		$results = apiCall(HomePublicApi::TaskHasProduct_Query, array($map4));
		$producta = apiCall(HomePublicApi::Product_Query, array($pid));
		$user = session('user');
		$uid = array('uid' => $user['info']['id']);
		$result = apiCall(HomePublicApi::Bbjmember_Seller_Query, array($uid));
		$page = array('curpage' => I('get.p', 0), 'size' => 6);
		$map=array('uid'=>$user['info']['id']);
		$resultAll = apiCall(HomePublicApi::TaskPlan_QueryAll, array($map,$page));
		$fenpeimap=array('task_id'=>I('id',0),'do_status'=>array('neq',0));
		$wanchengmap=array('task_id'=>I('id',0),'do_status'=>2,'order_status'=>7);
		$fcount=apiCall(HomePublicApi::Task_His_Query, array($fenpeimap));
		$scount=apiCall(HomePublicApi::Task_His_Query, array($fenpeimap));
		$headtitle = "宝贝街-任务计划";
		$this -> assign('head_title', $headtitle);
		$this -> assign('username', $user['info']['username']);
		$this->assign('fcount',count($fcount['info']));
		$this->assign('scount',count($scount['info']));
		$this->assign('tp',$result_play['info']);
		$this->assign('money',$result['info'][0]['coins']);
		$this->assign('task_play',$result_play['info'][0]);
		$this->assign('task',$result_tast['info'][0]);
		$this -> assign('jihuas', $results['info']);
		$this -> assign('pro', $producta['info']);
		$sj=A('usersj');
		$sj->is_auth();
//		dump($resulta);
	 	$this->display();
	 }
	/*
	 * 平台发货
	 * */
	public function sj_pingtai(){
		
		$user = session('user');
		$map1 = array('uid' => $user['info']['id'], 'delivery_mode' => 1);
		$result = apiCall(HomePublicApi::Task_Query, array($map1));
		$taskhis = apiCall(HomePublicApi::Task_His_Query, array($whe));
		$this -> assign('tshis', $taskhis['info']);
		$headtitle = "宝贝街-活动";
		$this -> assign('head_title', $headtitle);
		$this -> assign('task', $result['info']);
		$this -> assign('username', $user['info']['username']);
		$exchange=apiCall(AdminPublicApi::OrderExpress_Query, array($whe));
		$this->assign('express',$exchange['info']);
		$sj=A('usersj');
		$sj->is_auth();
		$sj->getcount();
//		dump($exchange);
		$this -> display();
	}
	 /*
	  * 发放任务
	  * TODO 限制
	  * */
	public function create_tp(){
		$user=session('user');
		$zongjia=I('zong','0.00');
		$entity=array(
			'uid'=>$user['info']['id'],
			'start_time'=>strtotime(I('stime',0)),
			'end_time'=>strtotime(I('etime',0)),
			'enter_way'=>I('sele_type',0),
			'task_cnt'=>I('count',0),
			'create_time'=>time(),
			'search_way_id'=>0,
			'task_id'=>I('tid',0),
			'yuecount'=>I('count',0),
		);
		if(strtotime(I('etime',0))-strtotime(I('stime',0))<1 ||strtotime(I('stime',0))-time()<1){
			$this->error('发放时间错误！');
		}else{
			$result = apiCall(HomePublicApi::TaskPlan_Add, array($entity));
			$entitya = array('uid' => $user['info']['id'], 'defray' => $zongjia , 'income' => '0.000', 'create_time' => time(), 'notes' => '冻结任务佣金', 'dtree_type' => 5, 'status' => 3, );
			$resulta = apiCall(HomePublicApi::FinAccountBalanceHis_Add, array($entitya));
			if ($resulta['status']) {
				$return1=M('bbjmemberSeller')->where('uid='.$user['info']['id'])->setDec('coins',$zongjia);
				$return2=M('bbjmemberSeller')->where('uid='.$user['info']['id'])->setInc('frozen_money',$zongjia);
				if($return1 && $return2){
					$this->success('发放成功！');
				}
			}
		}
		
		
	}
	/*
	 * 
	 * */

	/*
	 * 任务计划统计
	 * */
	public function zdysele(){
		$user=session('user');
		$map=array('uid'=>$user['info']['id']);
		$result=apiCall(HomePublicApi::Bbjmember_Seller_Query, array($map));
		$vip=$result['info'][0]['vip_level'];
		if($vip==2){
			$this->display();
		}else{
			$this->error('只有超级VIP才可以创建自定义搜索哦');
		}
		
	}
	/*
	 * 改变任务状态
	 * */
	public function changestatus() {
		$id = I('id', 0);
		$status = I('status');
		$user = session('user');
		$uid = array('uid' => $user['info']['id']);
		if ($status == 1) {
			$ma = array('task_gettype' => 2);
			$mm = array('task_do_type' => 2);
			$result_tast = apiCall(HomePublicApi::Task_Save, array($uid, $mm));
			$result = apiCall(HomePublicApi::Bbjmember_Seller_SaveByID, array($id, $ma));
			if ($result['status'] && $result_tast['status']) {
				$this -> success('任务领取状态修改成功', U('Home/Usersj/index'));
			} else {
				$this -> error($result['info']);
			}
		} else if ($status == 2) {
			$ma = array('task_gettype' => 1);
			$mm = array('task_do_type' => 1);
			$result_tast = apiCall(HomePublicApi::Task_Save, array($uid, $mm));
			$result = apiCall(HomePublicApi::Bbjmember_Seller_SaveByID, array($id, $ma));
			//			dump($result_tast);
			if ($result['status'] && $result_tast['status']) {
				$this -> success('任务领取状态修改成功', U('Home/Usersj/index'));
			} else {
				$this -> error($result['info']);
			}
		} else {
			$this -> error('任务状态异常，请联系我们');
		}

	}

	/*
	 * 试民自主选择
	 * */
	public function zzxz() {
		$taskid = I('taskid', 0);
		$entity = array('start_time' => time(), 'enter_way' => '', 'task_cnt' => 1, 'create_time' => time(), 'search_way_id' => '', 'task_id' => $taskid, 'uid' => '', );
		$result = apiCall(HomePublicApi::TaskPlan_Add, array($entity));
		if ($result['status']) {
			$this -> success('创建任务计划成功', U('Home/SJActivity/sj_tbhd'));
		}
	}

	/*
	 * 淘宝活动
	 * */
	public function sj_waiting() {
		$user = session('user');
		$map1 = array('uid' => $user['info']['id'], 'task_status' => 4);
		$result = apiCall(HomePublicApi::Task_Query, array($map1));
		$whe = array('do_status' => 3);
		$taskhis = apiCall(HomePublicApi::Task_His_Query, array($whe));
		$this -> assign('tshis', $taskhis['info']);
		$sm=apiCall(HomePublicApi::Bbjmember_Query, array());
		$sms=apiCall(HomePublicApi::Member_Query, array());
		$headtitle = "宝贝街-活动";
		$this->assign('sm',$sm['info']);
		$this->assign('sms',$sms['info']);
		$this -> assign('head_title', $headtitle);
		$this -> assign('task', $result['info']);
		$this -> assign('username', $user['info']['username']);
		$sj=A('usersj');
		$sj->is_auth();
		$sj->getcount();
//		dump($taskhis);
		$this -> display();
	}
	/*
	 * 确认还款
	 * */
	public function sj_qrhk(){
		$user = session('user');
		$map1 = array('uid' => $user['info']['id'], 'task_status' => 4);
		$result = apiCall(HomePublicApi::Task_Query, array($map1));
		$whe = array('do_status' => 4);
		$taskhis = apiCall(HomePublicApi::Task_His_Query, array($whe));
		$this -> assign('tshis', $taskhis['info']);
		$headtitle = "宝贝街-活动";
		$this -> assign('head_title', $headtitle);
		$this -> assign('task', $result['info']);
		$this -> assign('username', $user['info']['username']);
		$sj=A('usersj');
		$sj->is_auth();
		$sj->getcount();
//		dump($taskhis);
		$this -> display();
	}
	/*
	 * 订单确认
	 * */
	public function qrdd(){
		$id=I('id','');
		$entity=array('order_status'=>2,'do_status'=>6);
		$result=apiCall(HomePublicApi::Task_His_SaveByID,array($id,$entity));
//		dump($id);dump($entity);
		if($result['status']){
			$this->success('任务操作成功',U('Home/SJActivity/sj_tbhd'));
		}else{
			$this->error('系统未知错误',U('Home/SJActivity/sj_tbhd'));
		}
	}
	/*
	 * 确认还款
	 * */
	public function qrhk(){
		$id=I('id',0);
		$umap=array('id'=>$id);
		$tid=I('tid',0);
		$smap=array('id'=>$tid);
		$entity=array('do_status'=>2);
		$result=apiCall(HomePublicApi::Task_His_SaveByID,array($id,$entity));
		if($result['status']){
			$us=apiCall(HomePublicApi::Task_His_Query,array($umap));
			$uid=$us['info'][0]['uid'];
			$map=array('uid' => $uid);
			$result=apiCall(HomePublicApi::Bbjmember_Query,array($map));
			$yqrid=$result['info'][0]['referrer_id'];
			$sel=apiCall(HomePublicApi::Task_Query,array($smap));
			$money=$sel['info'][0]['task_gold'];
			$fhmoney=$us['info'][0]['tb_price'];
			$sid=$sel['info'][0]['uid'];
			$fucoin=$sel['info'][0]['coin'];
			$orderid=$us['info'][0]['tb_orderid'];
			$return=M('bbjmember')->where('uid='.$uid)->setInc('coins',$fhmoney);
			$return=M('bbjmember')->where('uid='.$yqrid)->setInc('fucoin',1);
			$return=M('bbjmember')->where('uid='.$uid)->setInc('fucoin',$fucoin);
			$return1=M('bbjmemberSeller')->where('uid='.$sid)->setDec('frozen_money',$money);
			if($return!=0 &&$return1 !=0){
				$entity1 = array('uid' => $uid, 'defray' => '0.00', 'income' => $fhmoney, 'create_time' => time(), 'notes' => '商家退回任务金额', 'dtree_type' => 2, 'status' => 1, );
				$result1 = apiCall(HomePublicApi::FinAccountBalanceHis_Add, array($entity1));
				$entity2 = array('uid' => $uid, 'defray' => '0.00' ,'income' => $fucoin, 'create_time' => time(), 'notes' => "订单：".$orderid."任务完成奖励福币", 'dtree_type' => 2, 'status' => 1, );
				$result2 = apiCall(HomePublicApi::FinAccountBalanceHis_Add, array($entity2));
				$entity3 = array('uid' => $sid, 'defray' => $money, 'income' => '0.000', 'create_time' => time(), 'notes' => '任务结算返还试民', 'dtree_type' => 6, 'status' => 1, );
				$result3 = apiCall(HomePublicApi::FinAccountBalanceHis_Add, array($entity3));
				$this->success('任务操作成功',U('Home/SJActivity/sj_tbhd'));
			}
		}else{
			$this->error('系统未知错误',U('Home/SJActivity/sj_tbhd'));
		}
	}
	/*
	 * 驳回
	 * */
	public function rt(){
		$id=I('id','');
		$entity=array('do_status'=>8);
		$result=apiCall(HomePublicApi::Task_His_SaveByID,array($id,$entity));
		if($result['status']){
			$this->success('任务操作成功',U('Home/SJActivity/sj_tbhd'));
		}else{
			$this->error('系统未知错误',U('Home/SJActivity/sj_tbhd'));
		}
	}
	/*
	 * 任务书
	 * */
	public function rws() {
		
		$headtitle = "宝贝街-任务书";
		$this -> assign('head_title', $headtitle);
		$user = session('user');
		$this -> assign('username', $user['info']['username']);
		$id = I('id', 0);
		$map = array('task_id' => $id);
		$result = apiCall(HomePublicApi::TaskHasProduct_Query, array($map));
		$mapp = array('id' => $result['info'][0]['pid']);
		$mapa = array('pid' => $result['info'][0]['pid'],'status'=>1);
		//		dump($mapa);
		$return = apiCall(HomePublicApi::Product_Query, array($mapp));
		$returns = apiCall(HomePublicApi::ProductSearchWay_Query, array($mapa));
		$this -> assign('pd', $return['info'][0]);
		$this -> assign('search', $returns['info'][0]);
		if ($returns['info'] == NULL) {
			$this -> error('预览任务书，请先创建搜索', U('Home/SJActivity/sj_tbhd'));
		}
		$this -> display();
	}

	/*
	 * 创建任务第一步
	 * */
	public function activity_1() {
		$user = session('user');
		$headtitle = "宝贝街-创建任务";
		$this -> assign('head_title', $headtitle);
		$mapa = array('uid' => $user['info']['id']);
		$sj = apiCall(HomePublicApi::Bbjmember_Seller_Query, array($mapa));
		$this -> assign('vip_level', $sj['info'][0]['vip_level']);
		$this -> assign('aliwawa', $sj['info'][0]['aliwawa']);
		$this -> assign('username', $user['info']['username']);
		$map = array('uid' => $user['info']['id'], 'status' => 1);
		$pro = apiCall(HomePublicApi::Product_Query, array($map));
		$this -> assign('pros', $pro['info']);
		$this->assign('auth_status',$sj['info'][0]['auth_status']);
		$sj=A('usersj');
		$sj->is_auth();
		$this -> display();
	}

	public function yongjin() {
		/*
		 * 佣金表数组
		 * */
		$user = session('user');
		$map = array('uid' => $user['info']['id']);
		$sj = apiCall(HomePublicApi::Bbjmember_Seller_Query, array($map));
		$pros = session('al');

		$geshu = count($pros['title']);
		session('shuliang', $geshu);
		$vip = $sj['info'][0]['vip_level'];
		$count = 0;
		$sum = 0;
		foreach ($pros as $key => $value) {
			if ($count < $geshu) {
				$money = $pros['price'][$count];
				$zk = 1;
				$yongjin = 4;
				for ($a = 0; $a <= 2000; $a += 50) {
					if ($money < $a) {
						if ($geshu == 1 && $vip == 0) {
							$yongjin = $yongjin * $zk;
						} else if ($geshu == 1 && $vip == 1) {
							$zk = 0.05;
							$yongjin = $yongjin - ($yongjin * $zk);
						} else if ($geshu == 1 && $vip == 2) {
							$zk = 0.1;
							$yongjin = $yongjin - ($yongjin * $zk);
						} else if ($geshu == 2 && $vip == 1) {
							$zk = 0.35;
							$yongjin = $yongjin - ($yongjin * $zk);
						} else if ($geshu == 2 && $vip == 2) {
							$zk = 0.4;
							$yongjin = $yongjin - ($yongjin * $zk);
						} else if ($geshu == 3 && $vip == 1) {
							$zk = 0.5;
							$yongjin = $yongjin - ($yongjin * $zk);
						} else if ($geshu == 3 && $vip == 2) {
							$zk = 0.55;
							$yongjin = $yongjin - ($yongjin * $zk);
						}
						$sum = $sum + $yongjin;
						break;
					}
					$yongjin = $yongjin + 1;
				}
			}
			$count = $count + 1;
		}
		$this -> assign('yongjin', $sum);
		$this -> assign('pros', $pros);
		//	dump($sum);
		//		$this->display('activity_2');
	}

	/*
	 * 保存商品信息
	 * */
	public function a1() {
		$user = session('user');
		$aliwawa = I('aliwawa', '');
		$pid=I('pid',0);
//		dump($pid);
		$al = array('img' => I('img', ''), 'link' => I('url', ''), 'title' => I('title', ''), 'num' => I('num', 1), 'price' => I('price','0.00'), 'position' => I('guige', ''), );
		session('al', $al);
		$count = 0;
		$summ = 0;
		$map = array('uid' => $user['info']['id']);
		$sj = apiCall(HomePublicApi::Bbjmember_Seller_Query, array($map));
		
		$entity = array('uid' => $user['info']['id'], 'aliwawa' => $sj['info'][0]['aliwawa'], 'delivery_mode' => '', 'create_time' => time(), 'task_gold' => '0.00', 'task_brokerage' => '0.00', 'task_postage' => '0.00', 'update_time' => time(), 'dtree_preferential' => '', 'task_name' => '', 'notes' => '', 'chat_argot' => '', 'task_do_type' => 1, 'task_status' => 1, );
		$result1 = apiCall(HomePublicApi::Task_Add, array($entity));
		foreach ($al as $key => $value) {
			if ($count < count($al['title'])) {
				if ($al['title'][$count] != '' || $al['title'][$count] != null) {
					if($pid==0){
						$pro = array('uid' => $user['info']['id'], 'link' => $al['link'][$count], 'price' => $al['price'][$count], 'has_model_num' => 1, 'position' => '', 'title' => $al['title'][$count], 'main_img' => $al['img'][$count], 'wangwang' => $aliwawa, 'create_time' => time(), 'update_time' => time(), 'status' => 1, 'model_num_cfg' => $al['position'][$count], 'is_on_sale' => 1, );
						$result = apiCall(HomePublicApi::Product_Add, array($pro));
						if ($result['status']) {
							session('pid', $result1['info']);
							$task_has = array('task_id' => $result1['info'], 'pid' => $result['info'], 'num' => $al['num'][$count], 'sku' => $al['position'][$count], 'pname' => $al['title'][$count], 'create_time' => time(), );
							$ret = apiCall(HomePublicApi::TaskHasProduct_Add, array($task_has));
							$summ = $summ + $al['price'][$count];
						}
					}else{
//						dump($pid);
						$pro = array('price' => $al['price'][$count],  'update_time' => time(),  'model_num_cfg' => $al['position'][$count], );
//						dump($pid[$count]);
						$result = apiCall(HomePublicApi::Product_SaveByID, array($pid[$count],$pro));
						if ($result['status']) {
							session('pid', $result1['info']);
							$task_has = array('task_id' => $result1['info'], 'pid' => $pid[$count], 'num' => $al['num'][$count], 'sku' => $al['position'][$count], 'pname' => $al['title'][$count], 'create_time' => time(), );
							$ret = apiCall(HomePublicApi::TaskHasProduct_Add, array($task_has));
							$summ = $summ + $al['price'][$count];
						}
					}
					
				}
				$count = $count + 1;
			}
		}
//		dump($summ);
		$this -> yongjin();
		$this -> assign('summ', $summ);
		$headtitle = "宝贝街-创建任务";
		$this -> assign('head_title', $headtitle);
		$this -> assign('username', $user['info']['username']);
		$sj=A('usersj');
		$sj->is_auth();
		$this -> display('activity_2');
	}

	/*
	 * 创建任务第二步
	 * */
	public function activity_2() {
		$headtitle = "宝贝街-创建任务";
		$this -> assign('head_title', $headtitle);
		$user = session('user');
		$this -> assign('username', $user['info']['username']);
		$sj=A('usersj');
		$sj->is_auth();
		$this -> display();
	}

	/*
	 * 保存信息
	 * */
	public function a2() {
		$headtitle = "宝贝街-创建任务";
		$this -> assign('head_title', $headtitle);
		$user = session('user');
		$this -> assign('username', $user['info']['username']);
		$id = session('pid');
		$money = I('money');
		$bzj=I('bzj', '');
		$coin=round(I('yj', '')*0.7*4);
		session('money', $money);
		$entity = array('delivery_mode' => 1, 'task_postage' => I('by', ''), 'task_gold' => $bzj, 'task_brokerage' => I('yj', ''), 'dtree_preferential' => I('yhfs', ''),'coin'=>$coin, );
		$map = array('id' => $id);
		$result = apiCall(HomePublicApi::Task_SaveByID, array($id, $entity));
		$tak = apiCall(HomePublicApi::Task_Query, array($map));
		if ($result['status']) {
			$this -> assign('xiadan', I('xiadan', ''));
			$this -> assign('tak', $tak['info']);
			$sj=A('usersj');
			$sj->is_auth();
			$this -> display('activity_3');
		}
		//		$this->display('activity_3');
	}

	/*
	 * 创建任务第三步
	 * */
	public function activity_3() {
		$headtitle = "宝贝街-创建任务";
		$this -> assign('head_title', $headtitle);
		$sj=A('usersj');
		$sj->is_auth();
		$this -> assign('username', $user['info']['username']);
		$this -> display();
	}

	/*
	 * 任务完成创建
	 * */
	public function over() {
		$entity = array('task_name' => I('taskname', ''), 'chat_argot' => I('liaotiantext', ''), 'notes' => I('dingdantext', ''), );
		$id = session('pid');
		$result = apiCall(HomePublicApi::Task_SaveByID, array($id, $entity));
		//		dump($result);
		$money = session('money');
		$user = session('user');
		$uid = $user['info']['id'];
		
		if ($result['status']) {
					$this -> success('任务创建完成', U('Home/SJActivity/sj_tbhd'));
				
		}
		//		dump($entity);
	}

	/*
	 * 暂停任务
	 * */
	public function zanting() {
		$id = I('id');
		$mod=M('task_his');
		$whree['task_id']=$id;
		$count = $mod->where($whree)->count();
		if($count!=0){
			$this->error('还有任务，不能暂停。');
		}
		else{
			$entity = array('task_status' => 2);
			$return = apiCall(HomePublicApi::Task_SaveByID, array($id, $entity));
			if ($return['status']) {
				$this -> success('操作成功', U('Home/SJActivity/sj_tbhd'));
			}
		}

	}
	/*
	 * 暂停搜索
	 * */
	public function ztsele() {
		$id = I('id',0);
		$entity = array('status' => 0);
		$return = apiCall(HomePublicApi::ProductSearchWay_SaveByID, array($id, $entity));
		if ($return['status']) {
			$this -> success('操作成功', U('Home/SJActivity/productsele'));
		}
	}
	/*
	 * 开启搜索
	 * */
	public function startsele() {
		$id = I('id',0);
		$entity = array('status' => 1);
		$return = apiCall(HomePublicApi::ProductSearchWay_SaveByID, array($id, $entity));
		if ($return['status']) {
			$this -> success('操作成功', U('Home/SJActivity/productsele'));
		}
	}
	/*
	 * 开启
	 * */
	public function start() {
		$id = I('id');
		$map=array('task_id'=>$id);
		$result=apiCall(HomePublicApi::Task_His_Query, array($map));
		if($result['info'] == null){
			$entity = array('task_status' => 1);
		}else{
			$entity = array('task_status' => 4);
		}
		
		$return = apiCall(HomePublicApi::Task_SaveByID, array($id, $entity));
		if ($return['status']) {
			$this -> success('操作成功', U('Home/SJActivity/sj_tbhd'));
		}

	}

	/*
	 * 商品管理
	 * */
	public function productmanager() {
		$headtitle = "宝贝街-商品管理";
		$this -> assign('head_title', $headtitle);
		$user = session('user');
		$map = array('uid' => $user['info']['id'], 'is_on_sale' => 1);
		$mwe = array('uid' => $user['info']['id'], 'is_on_sale' => 0, );
		$page = array('curpage' => I('get.p', 0), 'size' => 6);
		$order=array('update_time'=>'desc');
		$sj = apiCall(HomePublicApi::Bbjmember_Seller_Query, array($map));
		$pro = apiCall(HomePublicApi::Product_QueryAll, array($map,$page,$order));
		$prduct = apiCall(HomePublicApi::Product_QueryAll, array($mwe,$page,$order));
		$this -> assign('id', 0);
		$this -> assign('downpro', $prduct['info']['list']);
		$this -> assign('downshow', $prduct['info']['show']);
		$this -> assign('showdown', $product['show']);
		$this -> assign('showall', $pro['info']['show']);
		$this -> assign('products', $pro['info']['list']);
		$this -> assign('aliwawa', $sj['info'][0]['aliwawa']);
		$this -> assign('username', $user['info']['username']);
		$sj=A('usersj');
		$sj->is_auth();
		$this -> display();
	}
		public function productmanager2() {
		$headtitle = "宝贝街-商品管理";
		$this -> assign('head_title', $headtitle);
		$user = session('user');
		$map = array('uid' => $user['info']['id'], 'is_on_sale' => 1);
		$mwe = array('uid' => $user['info']['id'], 'is_on_sale' => 0, );
		$page = array('curpage' => I('get.p', 0), 'size' => 6);
		$order=array('update_time'=>'desc');
		$sj = apiCall(HomePublicApi::Bbjmember_Seller_Query, array($map));
		$pro = apiCall(HomePublicApi::Product_QueryAll, array($map,$page,$order));
		$prduct = apiCall(HomePublicApi::Product_QueryAll, array($mwe,$page,$order));
		$this -> assign('id', 0);
		$this -> assign('downpro', $prduct['info']['list']);
		$this -> assign('downshow', $prduct['info']['show']);
		$this -> assign('showdown', $product['show']);
		$this -> assign('showall', $pro['info']['show']);
		$this -> assign('products', $pro['info']['list']);
		$this -> assign('aliwawa', $sj['info'][0]['aliwawa']);
		$this -> assign('username', $user['info']['username']);
		$sj=A('usersj');
		$sj->is_auth();
		$this -> display();
	}
	/*
	 * 搜索商品
	 * */
	public function querybyname(){
		$headtitle = "宝贝街-商品管理";
		$this -> assign('head_title', $headtitle);
		$name=I('name','');
		$url=I('url','');
		$user = session('user');
		$map= array('uid'=>$user['info']['id'],'title'=>array('like','%'. $name . '%'),'link'=>array('like','%'. $url . '%'));
		$order=array('update_time'=>'desc');
		$page = array('curpage' => I('get.p', 0), 'size' => 6);
		
		$pro = apiCall(HomePublicApi::Product_QueryAll, array($map,$page,$order));
		$this -> assign('qall', $pro['info']['show']);
		$this -> assign('products', $pro['info']['list']);
		$this -> assign('username', $user['info']['username']);
		$sj=A('usersj');
		$sj->is_auth();
		$this->display();
	}
	/*
	 * 商品下架
	 * */
	public function downpro() {
		$id = I('id', 0);
		$map = array('is_on_sale' => 0, );
		$result = apiCall(HomePublicApi::Product_SaveByID, array($id, $map));
		if ($result['status']) {
			$this -> success('更新成功', U('Home/SJActivity/productmanager'));
		} else {
			$this -> error($result['info']);
		}
	}

	/*
	 * 更新商品
	 * */
	public function productedit() {
		$user = session('user');
		$id = I('id', '');
		$mapa = array('id' => $id, );
		if (IS_GET) {
			$pro = apiCall(HomePublicApi::Product_Query, array($mapa));
			$this -> assign('id', $pro['info'][0]['id']);
			$this -> assign('product', $pro['info'][0]);
			$map = array('uid' => $user['info']['id'], 'is_on_sale' => 1);
			$mwe = array('uid' => $user['info']['id'], 'is_on_sale' => 0, );
			$order=array('update_time'=>'desc');
			$sj = apiCall(HomePublicApi::Bbjmember_Seller_Query, array($map,$order));
			$pro = apiCall(HomePublicApi::Product_QueryAll, array($map,$order));
			$prduct = apiCall(HomePublicApi::Product_QueryAll, array($mwe,$order));
			$this -> assign('downpro', $prduct['info']['list']);
			$this -> assign('showdown', $product['show']);
			$this -> assign('showall', $pro['show']);
			$this -> assign('products', $pro['info']['list']);
			$this -> assign('aliwawa', $sj['info'][0]['aliwawa']);
			$this -> assign('username', $user['info']['username']);
			$sj=A('usersj');
			$sj->is_auth();
			$this -> display('productmanager');
		} else {
			$entity = array('price' => I('price', ''), 'position' => trim(I('weizhi', '')), 'update_time' => time(), );

			//				dump($id);
			//dump($entity);
			$result = apiCall(HomePublicApi::Product_SaveByID, array($id, $entity));
			if ($result['status']) {
				$this -> success('更新成功', U('Home/SJActivity/productmanager'));
			} else {
				$this -> error($result['info']);
			}
		}

	}

	/*
	 * 商品搜索管理
	 * TODO:新增搜索
	 * */
	public function productsele() {
		$headtitle = "宝贝街-商品搜索管理";
		$this -> assign('head_title', $headtitle);
		$user = session('user');
		$map = array('uid' => $user['info']['id'], 'status' => 1);
		$mapp = array('uid' => $user['info']['id']);
		$mwe = array('uid' => $user['info']['id'], 'status' => 0);
		$page = array('curpage' => I('get.p', 0), 'size' => 10);
		//dump($user['info']['id']);
		$sj = apiCall(HomePublicApi::Bbjmember_Seller_Query, array($mapp));
		$product = apiCall(HomePublicApi::ProductSearchWay_QueryAll, array($map,$page));
		$pro = apiCall(HomePublicApi::ProductSearchWay_QueryAll, array($mapp,$page));
		$pros = apiCall(HomePublicApi::Product_Query, array($mapp));
		$prduct = apiCall(HomePublicApi::ProductSearchWay_QueryAll, array($mwe,$page));
		$this -> assign('aliwawa', $sj['info'][0]['aliwawa']);
		$this -> assign('prduct', $prduct['info']['list']);
		$this -> assign('prshow', $prduct['info']['show']);
		$this -> assign('product', $product['info']['list']);
		$this -> assign('prooshow', $product['info']['show']);
		$this -> assign('proshow', $pro['info']['show']);
		$this -> assign('pro', $pro['info']['list']);
		$this -> assign('pros', $pros['info']);
		$this -> assign('username', $user['info']['username']);
		$sj=A('usersj');
		$sj->is_auth();
//		dump($pros);
		$this -> display();
	}
	public function productonsele() {
		$headtitle = "宝贝街-商品搜索管理";
		$this -> assign('head_title', $headtitle);
		$user = session('user');
		$map = array('uid' => $user['info']['id'], 'status' => 1);
		$mapp = array('uid' => $user['info']['id']);
		$mwe = array('uid' => $user['info']['id'], 'status' => 0);
		$page = array('curpage' => I('get.p', 0), 'size' => 10);
		//dump($user['info']['id']);
		$sj = apiCall(HomePublicApi::Bbjmember_Seller_Query, array($mapp));
		$product = apiCall(HomePublicApi::ProductSearchWay_QueryAll, array($map,$page));
		$pro = apiCall(HomePublicApi::ProductSearchWay_QueryAll, array($mapp,$page));
		$pros = apiCall(HomePublicApi::Product_Query, array($mapp));
		$prduct = apiCall(HomePublicApi::ProductSearchWay_QueryAll, array($mwe,$page));
		$this -> assign('aliwawa', $sj['info'][0]['aliwawa']);
		$this -> assign('prduct', $prduct['info']['list']);
		$this -> assign('prshow', $prduct['info']['show']);
		$this -> assign('product', $product['info']['list']);
		$this -> assign('prooshow', $product['info']['show']);
		$this -> assign('proshow', $pro['info']['show']);
		$this -> assign('pro', $pro['info']['list']);
		$this -> assign('pros', $pros['info']);
		$this -> assign('username', $user['info']['username']);
		$sj=A('usersj');
		$sj->is_auth();
//		dump($pros);
		$this -> display();
	}
	public function productunsele() {
		$headtitle = "宝贝街-商品搜索管理";
		$this -> assign('head_title', $headtitle);
		$user = session('user');
		$map = array('uid' => $user['info']['id'], 'status' => 1);
		$mapp = array('uid' => $user['info']['id']);
		$mwe = array('uid' => $user['info']['id'], 'status' => 0);
		$page = array('curpage' => I('get.p', 0), 'size' => 10);
		//dump($user['info']['id']);
		$sj = apiCall(HomePublicApi::Bbjmember_Seller_Query, array($mapp));
		$product = apiCall(HomePublicApi::ProductSearchWay_QueryAll, array($map,$page));
		$pro = apiCall(HomePublicApi::ProductSearchWay_QueryAll, array($mapp,$page));
		$pros = apiCall(HomePublicApi::Product_Query, array($mapp));
		$prduct = apiCall(HomePublicApi::ProductSearchWay_QueryAll, array($mwe,$page));
		$this -> assign('aliwawa', $sj['info'][0]['aliwawa']);
		$this -> assign('prduct', $prduct['info']['list']);
		$this -> assign('prshow', $prduct['info']['show']);
		$this -> assign('product', $product['info']['list']);
		$this -> assign('prooshow', $product['info']['show']);
		$this -> assign('proshow', $pro['info']['show']);
		$this -> assign('pro', $pro['info']['list']);
		$this -> assign('pros', $pros['info']);
		$this -> assign('username', $user['info']['username']);
		$sj=A('usersj');
		$sj->is_auth();
//		dump($pros);
		$this -> display();
	}

	public function upsearch() {
		$headtitle = "宝贝街-更新搜索管理";
		$map=array('id'=>I('id',0));
		$this -> assign('head_title', $headtitle);
		$user = session('user');
		$mapp = array('uid' => $user['info']['id']);
		$sj = apiCall(HomePublicApi::Bbjmember_Seller_Query, array($mapp));
		$product = apiCall(HomePublicApi::ProductSearchWay_Query, array($map));
		$pros = apiCall(HomePublicApi::Product_Query, array($mapp));
		$this -> assign('pros',$pros['info']);
		$this->assign('search',$product['info'][0]);
		$this -> assign('aliwawa', $sj['info'][0]['aliwawa']);
		$this -> assign('username', $user['info']['username']);
		$sj=A('usersj');
		$sj->is_auth();
//		dump($pros);
		$this -> display();
	}
	/*
	 * 商品上架
	 * */
	public function uppro() {
		$id = I('id', 0);
		$map = array('is_on_sale' => 1, );
		$result = apiCall(HomePublicApi::Product_SaveByID, array($id, $map));
		if ($result['status']) {
			$this -> success('更新成功', U('Home/SJActivity/productmanager'));
		} else {
			$this -> error($result['info']);
		}
	}

	/*
	 * 商品删除
	 * */
	public function delpro() {
		$id = I('id', 0);
		$map = array('id' => $id, );
		$result = apiCall(HomePublicApi::Product_Del, array($map));
		if ($result['status']) {
			$this -> success('删除成功', U('Home/SJActivity/productmanager'));
		} else {
			$this -> error($result['info']);
		}
	}
	/*
	 * 商品删除
	 * */
	public function delsele() {
		$id = I('id', 0);
		$map = array('id' => $id, );
		$result = apiCall(HomePublicApi::ProductSearchWay_Del, array($map));
		if ($result['status']) {
			$this -> success('删除成功', U('Home/SJActivity/productsele'));
		} else {
			$this -> error($result['info']);
		}
	}

	/*
	 * 获取商品信息
	 * */
	public function addproduct() {
		$user = session('user');
		$gaoji = array('xinghao1' => I('xinghao1', ''), 'price1' => I('price1', ''), 'xinghao2' => I('xinghao2', ''), 'price2' => I('price2', ''), 'xinghao3' => I('xinghao3', ''), 'price3' => I('price3', ''), );
		$a = serialize($gaoji);
		if ($gaoji['xinghao1'] == '' && $gaoji['xinghao2'] == '' && $gaoji['xinghao3'] == '') {
			$entity = array('uid' => $user['info']['id'], 'link' => I('post.url', ''), 'price' => I('post.price', ''), 'has_model_num' => 0, 'position' => trim(I('post.weizhi', '')), 'title' => I('title', ''), 'main_img' => I('img', ''), 'wangwang' => I('alww', ''), 'create_time' => time(), 'update_time' => time(), 'status' => 1, 'model_num_cfg' => '', 'is_on_sale' => 1, );
			$result = apiCall(HomePublicApi::Product_Add, array($entity));
			if ($result['status']) {
				$this -> success('商品添加成功', U('Home/SJActivity/productmanager'));
			} else {
				$this -> error($result['info']);
			}
		} else {
			$entity = array('uid' => $user['info']['id'], 'link' => I('post.url', ''), 'price' => I('post.price', ''), 'has_model_num' => 1, 'position' => trim(I('post.weizhi', '')), 'title' => I('title', ''), 'main_img' => I('img', ''), 'wangwang' => I('alww', ''), 'create_time' => time(), 'update_time' => time(), 'status' => 1, 'model_num_cfg' => $a, 'is_on_sale' => 1, );
			$result = apiCall(HomePublicApi::Product_Add, array($entity));
			if ($result['status']) {
				$this -> success('商品添加成功', U('Home/SJActivity/productmanager'));
			} else {
				$this -> error($result['info']);
			}
		}

		//		dump($entity);
	}
	/*
	 * 已读
	 * */

	public function yidu(){
		$id=I('id',0);
		$map=array('msg_status'=>1);
		$result = apiCall(AdminPublicApi::Msgbox_SavebyId, array($id,$map));
		//$this->redirect("Home/Usersj/sj_znxx");
		//echo "1111111111111111";
		//dump($id);
	}
	//创建搜索
	public function createsearch() {
		$headtitle = "宝贝街-创建搜索";
		$this -> assign('head_title', $headtitle);
		$user = session('user');
		$map = array('parentid' => 36, );
		$result = apiCall(AdminPublicApi::Datatree_QueryNoPaging, array($map));
		$mapa = array('uid' => $user['info']['id']);
		$sj = apiCall(HomePublicApi::Bbjmember_Seller_Query, array($mapa));
		$this -> assign('aliwawa', $sj['info'][0]['aliwawa']);
		$this -> assign('username', $user['info']['username']);
		$sj=A('usersj');
		$sj->is_auth();
		$this -> display();

	}

	/**
	 * 创建搜索中的下拉框信息
	 */
	public function select() {
		$user = session('user');
		$map = array('uid' => $user['info']['id'], 'is_on_sale' => 1, 'title' => array('like', "%" . I('q', '', 'trim') . "%"),
		//'link'=>array('like', "%" . I('q', '', 'trim') . "%"),
		//'_logic' =>'OR',
		);
		$page = array('curpage' => I('get.p', 0), 'size' => 200);
		$result = apiCall(HomePublicApi::Product_QueryAll, array($map, $page));
		$this -> success($result['info']['list']);

		/*
		 $map['nickname'] = array('like', "%" . I('q', '', 'trim') . "%");
		 $map['id'] = I('q', -1);
		 $map['_logic'] = 'OR';
		 $page = array('curpage' => 0, 'size' => 20);
		 $order = " subscribe_time desc ";

		 $result = apiCall("Admin/Wxuser/query", array($map, $page, $order, false, 'id,nickname,avatar,openid'));
		 */
		/*if ($result['status']) {
		 $list = $result['info']['list'];
		 $this -> success($list);
		 } else {
		 LogRecord($result['info'], __LINE__);
		 }*/
	}

//	public function selectsch() {
//		$user = session('user');
//		$map = array('uid' => $user['info']['id'], 'is_on_sale' => 1, 'title' => array('like', "%" . I('q', '', 'trim') . "%"),
//		);
//		$results = apiCall(HomePublicApi::ProductSearchWay_Query);
//		for ($i=0; $i <count($results['info']) ; $i++) { 
//			$ids=$results['info'][$i]['pid'];
//		}
//		$page = array('curpage' => I('get.p', 0), 'size' => 200);
//		$result = apiCall(HomePublicApi::Product_QueryAll, array($map, $page));
//		for ($i=0; $i <count($result['info']['list']) ; $i++) { 
//			if(!is_array($result['info']['list'][$i]['id'],$ids)){
//				$ress[]=$result['info']['list'][$i];
//			}
//		}
//		$this -> success($ress);
//
//		
//	}

	/**
	 * 保存搜索 
	 */
	public function save() {
		$user=session('user');
		$iscz=I('iscz',0);
		$id=I('id',0);
		$type=I('type',0);
		if($id!=0){
			if($iscz!=0){
				$entity = array('update_time' => time(), 'pid' => I('pid', ''), 'search_url' => I('search_url', ''), 'search_q' => I('search_q', ''), 'search_order' => I('search_order', ''), 'search_condition' => I('search_xz', ''), );
				$result = apiCall(HomePublicApi::ProductSearchWay_SaveByID, array($id,$entity));
				if ($result['status']) {
					$this -> success('更新搜索成功', U('Home/SJActivity/createsearch'));
				}
			}else{
				$this->error('获取信息失败');
			}
		}else{
			if($iscz!=0){
				$entity = array('uid'=>$user['info']['id'] ,'dtree_type' => $type, 'status' => $iscz, 'create_time' => time(), 'update_time' => time(), 'pid' => I('pid', ''), 'search_url' => I('search_url', ''), 'search_q' => I('search_q', ''), 'search_order' => I('search_order', ''), 'search_condition' => I('search_xz', ''), );
				$result = apiCall(HomePublicApi::ProductSearchWay_Add, array($entity));
				if ($result['status']) {
					$this -> success('添加搜索成功', U('Home/SJActivity/createsearch'));
				}
			}else{
				$this->error('获取信息失败');
			}
		}
		
		
		//
	}
	/*
	 * 检查url
	 * */
	public function checkurl(){
		$map=array(
			'link'=>array('like','%'.I('purl','').'%'),
		);
		$result = apiCall(HomePublicApi::Product_Query, array($map));
		if($result['info'][0]['link'] !=null){
			$this->ajaxReturn(0,'json');
		}else{
			$this->ajaxReturn(1,'json');
		}
		
	}
	/*upcount修改任务份数
	 * */
	public function upcount(){
		$user=session('user');
	 	$id=I('sid',0);
		$mapp=array('id'=>$id);
		$money=I('dfmoney','');
		$result = apiCall(HomePublicApi::TaskPlan_Query, array($mapp));
		$fenshu=$result['info'][0]['task_cnt'];
		$yue=$result['info'][0]['yuecount'];
		$map=array('task_cnt'=>I('rwcount',1)+$fenshu,'yuecount'=>I('rwcount',1)+$yue);
		$zongjia=I('rwcount',1)*$money;
		if($zongjia<=0){
			$this->error('请填写正确的份数!');
		}else{
			$results = apiCall(HomePublicApi::TaskPlan_SaveByID, array($id,$map));
			$entitya = array('uid' => $user['info']['id'], 'defray' => $zongjia , 'income' => '0.000', 'create_time' => time(), 'notes' => '增加份数冻结任务佣金', 'dtree_type' => 5, 'status' => 3, );
			$resulta = apiCall(HomePublicApi::FinAccountBalanceHis_Add, array($entitya));
			if ($resulta['status']) {
				$return1=M('bbjmemberSeller')->where('uid='.$user['info']['id'])->setDec('coins',$zongjia);
				$return2=M('bbjmemberSeller')->where('uid='.$user['info']['id'])->setInc('frozen_money',$zongjia);
				if($return1 && $return2){
					$this->success('发放成功！',U('Home/SJActivity/sj_tbhd'));
				}
			}
		}
	 }
/**/
	public function phonesele(){
		$headtitle = "宝贝街-创建搜索";
		$this -> assign('head_title', $headtitle);
		$user = session('user');
		$map = array('parentid' => 36, );
		$result = apiCall(AdminPublicApi::Datatree_QueryNoPaging, array($map));
		$mapa = array('uid' => $user['info']['id']);
		$sj = apiCall(HomePublicApi::Bbjmember_Seller_Query, array($mapa));
		$this -> assign('aliwawa', $sj['info'][0]['aliwawa']);
		$this -> assign('username', $user['info']['username']);
		$sj=A('usersj');
		$sj->is_auth();
		$this -> display();
	}
	/*
	 * 修改任务时间
	 * */
	public function uptime(){
		$id=I('timeid',0);
		$stime=I('stime',0);
		$etime=I('etime',0);
		$map=array('create_time'=>strtotime($stime),'start_time'=>strtotime($etime));
		$results = apiCall(HomePublicApi::TaskPlan_SaveByID, array($id,$map));
		$this->success('操作成功');
	}
	public function zdysave() {
		$user=session('user');
		$entity = array('uid'=>$user['info']['id'] ,'dtree_type' => 1, 'status' => 1, 'create_time' => time(), 'update_time' => time(), 'pid' => I('pid', ''), 'search_url' => '', 'search_q' => I('text', ''), 'search_order' => I('search_order', ''), 'search_condition' => I('weizhi', ''), );
//		dump($entity);
		$result = apiCall(HomePublicApi::ProductSearchWay_Add, array($entity));
		if ($result['status']) {
			$this -> success('添加搜索成功', U('Home/SJActivity/createsearch'));
		}else{
			$this->error('创建自定义搜索失败!');
		}
		
	}

}
