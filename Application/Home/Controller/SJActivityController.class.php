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
		$map1 = array('uid' => $user['info']['id'], 'task_status' => 1);
		$result = apiCall(HomePublicApi::Task_Query, array($map1));
		$map2 = array('uid' => $user['info']['id'], 'task_status' => 2);
		$resultzt = apiCall(HomePublicApi::Task_Query, array($map2));
		$map3 = array('uid' => $user['info']['id'], 'task_status' => 3);
		$resultjs = apiCall(HomePublicApi::Task_Query, array($map3));
		$headtitle = "宝贝街-活动";
		$this -> assign('head_title', $headtitle);
		$user = session('user');
		for ($i = 0; $i < count($result['info']); $i++) {
			$id = $result['info'][$i]['id'];
			$map4 = array('task_id' => $id);
			$results[] = apiCall(HomePublicApi::TaskHasProduct_Query, array($map4));
		}
		for ($j = 0; $j < count($results); $j++) {
			$pid = array('id' => $results[$j]['info'][0]['pid']);
			$producta[] = apiCall(HomePublicApi::Product_Query, array($pid));

		}
		$this -> assign('task', $result['info']);
		$this -> assign('taskzt', $resultzt['info']);
		$this -> assign('taskjs', $resultjs['info']);
		$this -> assign('geshu', session('shuliang'));
		$this -> assign('username', $user['info']['username']);
		$this -> assign('jihuas', $results);
		$this -> assign('pro', $producta);
		//		dump($results);
		$this -> display();
	}
	/**
	 * 任务计划
	 * */
	 public function task_play(){
	 	$mm = array('id' => I('id',0));
		$result_tast = apiCall(HomePublicApi::Task_Query, array($mm));
		$result_play = apiCall(HomePublicApi::TaskPlan_Query, array($mm));
		for ($i = 0; $i < count($result_tast['info']); $i++) {
			$id = $result_tast['info'][$i]['id'];
			$map4 = array('task_id' => $id);
			$results[] = apiCall(HomePublicApi::TaskHasProduct_Query, array($map4));
		}
		for ($j = 0; $j < count($results); $j++) {
			$pid = array('id' => $results[$j]['info'][0]['pid']);
			$producta[] = apiCall(HomePublicApi::Product_Query, array($pid));
		}
		$user = session('user');
		$uid = array('uid' => $user['info']['id']);
		$result = apiCall(HomePublicApi::Bbjmember_Seller_Query, array($uid));
		$page = array('curpage' => I('get.p', 0), 'size' => 6);
		$map=array('uid'=>$user['info']['id']);
		$resultAll = apiCall(HomePublicApi::TaskPlan_QueryAll, array($map,$page));
		$this->assign('tp',$resultAll['info']['list']);
		$this->assign('money',$result['info'][0]['coins']);
		$this->assign('task_play',$result_play['info'][0]);
		$this->assign('task',$result_tast['info'][0]);
		$this -> assign('jihuas', $results);
		$this -> assign('pro', $producta);
	 	$this->display();
	 }
	 /*
	  * 发放任务
	  * */
	public function create_tp(){
		$user=session('user');
		$entity=array(
			'uid'=>$user['info']['id'],
			'start_time'=>I('begin_time',0),
			'enter_way'=>I('sele_type',0),
			'task_cnt'=>I('count',0),
			'create_time'=>time(),
			'search_way_id'=>0,
			'task_id'=>I('tid',0),
		);
		$result = apiCall(HomePublicApi::TaskPlan_Add, array($entity));
		if($result['status']){
			$this->success('发放成功！',U('Home/SJActivity/sj_tbhd'));
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
		$headtitle = "宝贝街-活动";
		$this -> assign('head_title', $headtitle);
		$this -> assign('task', $result['info']);
		$this -> assign('username', $user['info']['username']);
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
//		dump($taskhis);
		$this -> display();
	}
	/*
	 * 订单确认
	 * */
	public function qrdd(){
		$id=I('id','');
		$entity=array('do_status'=>4);
		$result=apiCall(HomePublicApi::Task_His_SaveByID,array($id,$entity));
//		dump($id);dump($entity);
		if($result['status']){
			$this->success('任务操作成功',U('Home/SJActivity/sj_tbhd'));
		}else{
			$this->error('系统未知错误',U('Home/SJActivity/sj_tbhd'));
		}
	}
	/*
	 * 订单确认
	 * */
	public function qrhk(){
		$id=I('id','');
		$entity=array('do_status'=>2);
		$result=apiCall(HomePublicApi::Task_His_SaveByID,array($id,$entity));
		if($result['status']){
			$this->success('任务操作成功',U('Home/SJActivity/sj_tbhd'));
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
		$mapa = array('pid' => $result['info'][0]['pid']);
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
		//				dump($sj['info'][0]['aliwawa']);
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
		$al = array('img' => I('img', ''), 'link' => I('url', ''), 'title' => I('title', ''), 'num' => I('num', 1), 'price' => I('price'), 'position' => I('guige', ''), );
		session('al', $al);
		$count = 0;
		$summ = 0;
		$map = array('uid' => $user['info']['id']);
		$sj = apiCall(HomePublicApi::Bbjmember_Seller_Query, array($map));
		
		$entity = array('uid' => $user['info']['id'], 'aliwawa' => $sj['info'][0]['aliwawa'], 'delivery_mode' => '', 'create_time' => time(), 'task_gold' => '0.00', 'task_brokerage' => '0.00', 'task_postage' => '0.00', 'update_time' => time(), 'dtree_preferential' => '', 'task_name' => '', 'notes' => '', 'chat_argot' => '', 'task_do_type' => '', 'task_status' => 1, );
		$result1 = apiCall(HomePublicApi::Task_Add, array($entity));
		foreach ($al as $key => $value) {
			if ($count < count($al['title'])) {
				if ($al['title'][$count] != '' || $al['title'][$count] != null) {
					$pro = array('uid' => $user['info']['id'], 'link' => $al['link'][$count], 'price' => $al['price'][$count], 'has_model_num' => 1, 'position' => '', 'title' => $al['title'][$count], 'main_img' => $al['img'][$count], 'wangwang' => $aliwawa, 'create_time' => time(), 'update_time' => time(), 'status' => 1, 'model_num_cfg' => $al['position'][$count], 'is_on_sale' => 1, );
					$result = apiCall(HomePublicApi::Product_Add, array($pro));
					if ($result['status']) {
						session('pid', $result1['info']);
						$task_has = array('task_id' => $result1['info'], 'pid' => $result['info'], 'num' => $al['num'][$count], 'sku' => $al['position'][$count], 'pname' => $al['title'][$count], 'create_time' => time(), );
						$ret = apiCall(HomePublicApi::TaskHasProduct_Add, array($task_has));
						$summ = $summ + $al['price'][$count];
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
		$coin=0;
		if($bzj<100){
			$coin=2;
		}else if($bzj<200 && $bzj>=100){
			$coin=3;
		}else if($bzj<300 && $bzj>=200){
			$coin=4;
		}else if($bzj<400 && $bzj>=300){
			$coin=5;
		}else if($bzj>500){
			$coin=6;
		}
		session('money', $money);
		$entity = array('delivery_mode' => I('fhfs', ''), 'task_postage' => I('by', ''), 'task_gold' => $bzj, 'task_brokerage' => I('yj', ''), 'dtree_preferential' => I('yhfs', ''),'coin'=>$coin, );
		$map = array('id' => $id);
		$result = apiCall(HomePublicApi::Task_SaveByID, array($id, $entity));
		$tak = apiCall(HomePublicApi::Task_Query, array($map));
		if ($result['status']) {
			$this -> assign('xiadan', I('xiadan', ''));
			$this -> assign('tak', $tak['info']);
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
		$map = array('uid' => $uid);
		$rets = apiCall(HomePublicApi::Bbjmember_Seller_Query, array($map));
		if ($result['status']) {
			$ap = array('coins' => $rets['info'][0]['coins'] - $money,'frozen_money'=>$rets['info'][0]['coins']+$money );
			$return = apiCall(HomePublicApi::Bbjmember_Seller_SaveByID, array($uid, $ap));
			if ($return['status']) {
				$entity = array('uid' => $user['info']['id'], 'defray' => $money, 'income' => '0.000', 'create_time' => time(), 'notes' => '任务冻结金额', 'dtree_type' => 5, 'status' => 3, );
				$result1 = apiCall(HomePublicApi::FinAccountBalanceHis_Add, array($entity));
				if ($result1['status']) {

					$this -> success('任务创建完成', U('Home/SJActivity/sj_tbhd'));
				}

			}

		}
		//		dump($entity);
	}

	/*
	 * 暂停任务
	 * */
	public function zanting() {
		$id = I('id');
		$entity = array('task_status' => 2);
		$return = apiCall(HomePublicApi::Task_SaveByID, array($id, $entity));
		if ($return['status']) {
			$this -> success('操作成功', U('Home/SJActivity/sj_tbhd'));
		}
	}

	/*
	 * 开启
	 * */
	public function start() {

		$id = I('id');
		$entity = array('task_status' => 1);
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
		$sj = apiCall(HomePublicApi::Bbjmember_Seller_Query, array($map));
		$pro = apiCall(HomePublicApi::Product_QueryAll, array($map));
		$prduct = apiCall(HomePublicApi::Product_QueryAll, array($mwe));
		$this -> assign('id', 0);
		$this -> assign('downpro', $prduct['info']['list']);
		$this -> assign('showdown', $product['show']);
		$this -> assign('showall', $pro['show']);
		$this -> assign('products', $pro['info']['list']);
		$this -> assign('aliwawa', $sj['info'][0]['aliwawa']);
		$this -> assign('username', $user['info']['username']);
		$this -> display();
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
			$sj = apiCall(HomePublicApi::Bbjmember_Seller_Query, array($map));
			$pro = apiCall(HomePublicApi::Product_QueryAll, array($map));
			$prduct = apiCall(HomePublicApi::Product_QueryAll, array($mwe));
			$this -> assign('downpro', $prduct['info']['list']);
			$this -> assign('showdown', $product['show']);
			$this -> assign('showall', $pro['show']);
			$this -> assign('products', $pro['info']['list']);
			$this -> assign('aliwawa', $sj['info'][0]['aliwawa']);
			$this -> assign('username', $user['info']['username']);
			//				dump($pro['info'][0]);
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
		$page = array('curpage' => I('get.p', 0), 'size' => 6);
		//dump($user['info']['id']);
		$product = apiCall(HomePublicApi::Product_QueryAll, array($map,$page));
		$pro = apiCall(HomePublicApi::Product_QueryAll, array($mapp,$page));
		$pros = apiCall(HomePublicApi::ProductSearchWay_QueryAll, array());
		$prduct = apiCall(HomePublicApi::Product_QueryAll, array($mwe,$page));
		$this -> assign('prduct', $prduct['info']['list']);
		$this -> assign('prshow', $prduct['info']['show']);
		$this -> assign('product', $product['info']['list']);
		$this -> assign('prooshow', $product['info']['show']);
		$this -> assign('proshow', $pro['info']['show']);
		$this -> assign('pro', $pro['info']['list']);
		$this -> assign('pros', $pros['info']['list']);
		$this -> assign('username', $user['info']['username']);
//		dump($product);
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

	
	//创建搜索
	public function createsearch() {
		$headtitle = "宝贝街-创建搜索";
		$this -> assign('head_title', $headtitle);
		$user = session('user');
		$map = array('parentid' => 36, );
		$result = apiCall(AdminPublicApi::Datatree_QueryNoPaging, array($map));
		$this -> assign('username', $user['info']['username']);
		$this -> display();

	}

	/**
	 * 创建搜索中的下拉框信息
	 */
	public function select() {
		$user = session('user');
		$map = array('uid' => $user['info']['id'], 'status' => 1, 'title' => array('like', "%" . I('q', '', 'trim') . "%"),
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

	/**
	 * 保存搜索
	 */
	public function save() {
		$entity = array('dtree_type' => '关键字', 'status' => 1, 'create_time' => time(), 'update_time' => time(), 'pid' => I('pid', ''), 'search_url' => I('search_url', ''), 'search_q' => I('search_q', ''), 'search_order' => I('search_order', ''), 'search_condition' => I('search_xz', ''), );
		$result = apiCall(HomePublicApi::ProductSearchWay_Add, array($entity));
		if ($result['status']) {
			$this -> success('添加搜索成功', U('Home/SJActivity/createsearch'));
		}
		//
	}

}
