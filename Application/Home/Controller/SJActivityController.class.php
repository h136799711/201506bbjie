<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 青 <99701759@qq.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Home\Controller;
use Home\Api\BbjmemberSellerApi;
use Home\Api\FinAccountBalanceHisApi;
use Home\Api\ProductSearchWayApi;
use Home\Api\TaskApi;
use Home\Api\TaskHasProductApi;
use Home\Api\TaskHisApi;
use Home\Api\TaskPlanApi;
use Home\Api\TaskProductApi;
use Home\Api\VTaskProductInfoApi;
use Home\Api\VTaskProductSearchWayApi;
use Home\Model\BbjmemberSellerModel;
use Home\Model\TaskHasProductModel;
use Home\Model\TaskHisModel;
use Home\Model\TaskPlanModel;
use Think\Controller;
use Think\Storage;
use Home\Api\HomePublicApi;
use Admin\Api\AdminPublicApi;
/*
 * 资金提现
 */
class SJActivityController extends SjController {

    protected function _initialize(){
        parent::_initialize();
        $this->checkLogin();
    }


	/*
	 * 淘宝活动
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function sj_tbhd() {

        $this -> assign('head_title', "宝贝街-活动");
		$user = $this->userinfo;
        $uid = $user['uid'];

		$page = array('curpage' => I('get.p', 0), 'size' => 5);
		$map  = array('uid' => $uid, 'task_status' => array('in',array(1,4)));
		$order = "create_time desc";

        $result = apiCall(TaskApi::QUERY,array($map,$page,$order));

        $task = $result['info']['list'];
        $api = new VTaskProductInfoApi();
        foreach($task as &$vo){
            $result = $api->queryNoPaging(array('task_id'=>$vo['id']));
            if($result['status']){
                $vo['_products'] = $result['info'];
            }
        }


		$this->assign('task',$task);
		$this->assign('show',$result['info']['show']);

		$this -> display();
	}

    /**
     * 暂停下的活动
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     */
	public function sj_tbzt() {

        $this -> assign('head_title', "宝贝街-活动");
        $user = $this->userinfo;
        $uid = $user['uid'];

        $page = array('curpage' => I('get.p', 0), 'size' => 5);
        $map  = array('uid' => $uid, 'task_status' => 2);
        $order = "create_time desc";

        $result = apiCall(TaskApi::QUERY,array($map,$page,$order));

        $task = $result['info']['list'];
        $api = new VTaskProductInfoApi();
        foreach($task as &$vo){
            $result = $api->queryNoPaging(array('task_id'=>$vo['id']));
            if($result['status']){
                $vo['_products'] = $result['info'];
            }
        }


        $this->assign('task',$task);
        $this->assign('show',$result['info']['show']);

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

	}
	/**
	 * 任务计划
     * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	 public function task_play(){
         $task_id = I('id',0);
         $this -> assign('head_title', "宝贝街-任务计划");
         $map = array('task_id' => $task_id);

         $result = apiCall(TaskApi::GET_INFO,array(array('id'=>$task_id)));
         $task = $result['info'];

         $result = apiCall(TaskPlanApi::SUM,array($map,'task_cnt'));

         $all_plan_total = $result['info'];

         //任务包含的商品
         $result = apiCall(VTaskProductInfoApi::QUERY_NO_PAGING,array($map));
         $products = $result['info'];
         //2. 统计
         $cnt_map = array('task_id'=>$task_id);
         $cnt_map['do_status'] = TaskHisModel::DO_STATUS_DOING;

         $result = apiCall(TaskHisApi::COUNT,array($cnt_map));
         $doing_cnt = $result['info'];

         $cnt_map['do_status'] = TaskHisModel::DO_STATUS_DONE ;
         $result = apiCall(TaskHisApi::COUNT,array($cnt_map));
         $done_cnt = $result['info'];

         $result = apiCall(TaskHisApi::COUNT,array($cnt_map));
         $total_cnt = $result['info'];

         $result = apiCall(TaskPlanApi::QUERY_NO_PAGING,array(array('task_id'=>$task_id)));
         $plan_list = $result['info'];

         $this -> assign('all_plan_total',$all_plan_total);
         $this -> assign('doing_cnt',$doing_cnt);
         $this -> assign('done_cnt',$done_cnt);
         $this -> assign('other_cnt',$total_cnt-$doing_cnt-$done_cnt);
         $this -> assign('products', $products);
         $this -> assign('task',$task);
         $this -> assign('plan_list',$plan_list);
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

//		dump($exchange);
		$this -> display();
	}
	 /*
	  * 发放任务
	  * @author 老胖子-何必都 <hebiduhebi@126.com>
	  * */
	public function create_tp(){
        $count = I('count',0);
        $count = intval($count);

        if($count <= 0){
            $this->error('发放数量不能小于0');
        }

        $task_id = I('post.tid',0);
        $start_time = strtotime(I('stime',0));
        $end_time = strtotime(I('etime',0));
        if($start_time > $end_time){
            $tmp = $end_time;
            $end_time = $start_time;
            $start_time = $tmp;
        }

        if($start_time < time()){
            $start_time = time();
        }
        if($end_time < time()){
            $end_time  = time() + 7*24*3600;
        }

        $defray = I('post.defray',0);

		$entity=array(
			'uid'=>$this->uid,
			'start_time'=>$start_time ,
			'end_time'=>$end_time ,
			'enter_way'=>I('sele_type',0),
			'task_cnt'=>$count,
			'create_time'=>time(),
			'search_way_id'=>0,
			'task_id'=>$task_id,
			'yuecount'=>$count,
		);


        $result = apiCall(TaskPlanApi::ADD, array($entity));
        $entity = array(
            'uid' => $this->uid,
            'defray' => $defray ,
            'income' => '0.000',
            'create_time' => time(),
            'notes' => '冻结任务佣金',
            'dtree_type' => 5,
            'status' => 3,
            );

        $result = apiCall(FinAccountBalanceHisApi::ADD, array($entity));
        if ($result['status']) {

            apiCall(BbjmemberSellerApi::SET_DESC,array(array('uid'=>$this->uid),"coins",$defray));
            apiCall(BbjmemberSellerApi::SET_INC,array(array('uid'=>$this->uid),"frozen_money",$defray));

            $this->success('发放成功！');

        }else{
            $this->error($result['info']);
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

		$status = I('get.status','');

        if($status != BbjmemberSellerModel::SELF_GET && $status != BbjmemberSellerModel::SELLER_SELECT){
            $this->error("任务状态非法!");
        }

        $entity = array('task_gettype' => $status);

        $result = apiCall(BbjmemberSellerApi::SAVE_BY_ID, array(UID, $entity));

        if ($result['status']) {
            $this->updateTaskGetType($status);
            $this -> success('任务领取状态修改成功', U('Home/Usersj/index'));
        } else {
            $this -> error($result['info']);
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

		$this -> assign('head_title', "宝贝街-活动");
		$this -> assign('task', $result['info']);

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
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function rws() {

		$this -> assign('head_title', "宝贝街-任务书");

		$id = I('id', 0);
        $pid = $this->_param('pid',0);
		$map = array('task_id' => $id);
        $map['pid'] = $pid;
		$result = apiCall(VTaskProductInfoApi::GET_INFO, array($map));
        $this -> assign('pd', $result['info']);

        $result = apiCall(ProductSearchWayApi::GET_INFO, array(array('pid'=>$pid)));

        $this -> assign('search', $result['info']);

		if ($result['info'] == NULL) {
			$this -> error('预览任务书，请先创建搜索', U('Home/SJActivity/sj_tbhd'));
		}

		$this -> display();
	}

	/*
	 * 创建任务第一步
	 * */
	public function activity_1() {

		$this -> assign('head_title', "宝贝街-创建任务");
		$map = array('uid' => $this->uid,'status' => 1);

		$result = apiCall(TaskProductApi::QUERY_NO_PAGING, array($map));
		$this -> assign('pros', $result['info']);
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

		$aliwawa = I('aliwawa', '');
		$pid=I('pid',0);
//		dump($pid);
		$al = array('img' => I('img', ''),
            'link' => I('url', ''),
            'title' => I('title', ''),
            'num' => I('num', 1),
            'price' => I('price','0.00'),
            'position' => I('guige', ''), );

		session('al', $al);
		$count = 0;
		$summ = 0;
		$map = array('uid' => $this->uid);
		
		$entity = array('uid' => $this->uid, 'aliwawa' => $this->userinfo['aliwawa'],
            'delivery_mode' => '', 'create_time' => time(), 'task_gold' => '0.00',
            'task_brokerage' => '0.00', 'task_postage' => '0.00',
            'update_time' => time(), 'dtree_preferential' => '',
            'task_name' => '', 'notes' => '', 'chat_argot' => '',
            'task_do_type' => 1, 'task_status' => 1, );

		$result1 = apiCall(TaskApi::ADD, array($entity));

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

						$pro = array('price' => $al['price'][$count],  'update_time' => time(),  'model_num_cfg' => $al['position'][$count], );

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

		$this -> yongjin();
		$this -> assign('summ', $summ);
		$this -> assign('head_title', "宝贝街-创建任务");
		$this -> display('activity_2');
	}

	/*
	 * 创建任务第二步
	 * */
	public function activity_2() {
		$this -> assign('head_title', "宝贝街-创建任务");
		$this -> display();
	}

	/*
	 * 保存信息
	 * */
	public function a2() {

		$this -> assign('head_title', "宝贝街-创建任务");
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
			$this -> display('activity_3');
		}
	}

	/*
	 * 创建任务第三步
	 * */
	public function activity_3() {
		$this -> assign('head_title', "宝贝街-创建任务");
		$this -> display();
	}

	/*
	 * 任务完成创建
	 * */
	public function over() {
		$entity = array('task_name' => I('taskname', ''), 'chat_argot' => I('liaotiantext', ''), 'notes' => I('dingdantext', ''), );
		$id = session('pid');
		$result = apiCall(HomePublicApi::Task_SaveByID, array($id, $entity));
		
		if ($result['status']) {
            $this -> success('任务创建完成', U('Home/SJActivity/sj_tbhd'));
		}
	}

	/*
	 * 暂停单个任务
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function zanting() {
		$id = I('get.id',0);

		$mod = new TaskHisApi();

		$map['task_id']=$id;

		$result = $mod->count($map);

        if(!$result['status']){
            $this->error($result['info']);
        }

        $count = $result['info'];

		if($count > 0){
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
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
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
	 * 开启任务
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function start() {
		$id = I('get.id');
        $entity = array('task_status' => 1);

		$return = apiCall(TaskApi::SAVE_BY_ID, array($id, $entity));
		if ($return['status']) {
			$this -> success('操作成功', U('Home/SJActivity/sj_tbhd'));
		}else{
            $this->error($return['info']);
        }

	}

	/*
	 * 商品管理
	 * */
	public function productmanager() {

        $name = $this->_param('name','');
        $is_on_sale = $this->_param('sale','1');
        $id = $this->_param('id',0);
        $this -> assign('head_title', "宝贝街-商品管理");
        $uid = $this->userinfo['uid'];

		$map = array('uid' => $uid, 'is_on_sale' => $is_on_sale);
        if(!empty($name)){
            $map['title'] = array('like','%'.$name.'%');
            $param['name'] = $name;
            $param['sale'] = $is_on_sale;
        }

		$page = array('curpage' => I('get.p', 0), 'size' => 6);
		$order=array('update_time'=>'desc');

		$pro = apiCall(TaskProductApi::QUERY, array($map,$page,$order));

        if(!empty($id)){
            $result = apiCall(TaskProductApi::GET_INFO,array(array('id'=>$id)));
            if($result['status']){
                $this->assign('product',$result['info']);
            }
        }

        $this -> assign('sale', $is_on_sale);
        $this -> assign('name', $name);
        $this -> assign('show', $pro['info']['show']);
        $this -> assign('products', $pro['info']['list']);


		$this -> display();
	}


	/*
	 * 商品上下架
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function on_sale() {
		$id = I('id', 0);
        $is_on_sale = $this->_param('sale','0');
		$map = array('is_on_sale' => $is_on_sale);
		$result = apiCall(TaskProductApi::SAVE_BY_ID, array($id, $map));
		if ($result['status']) {
			$this -> success('更新成功', U('Home/SJActivity/productmanager'));
		} else {
			$this -> error($result['info']);
		}
	}



	/*
	 * 商品搜索管理
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function productsele() {

		$this -> assign('head_title', "宝贝街-商品搜索管理");
        $type = $this->_param('type','all');

		$user = $this->userinfo;
		$map = array('uid' => $user['id']);
        if($type == 'on'){
            $map['status'] = 1;
        }else if($type == 'stop'){
            $map['status'] = 0;
        }

		$page = array('curpage' => I('get.p', 0), 'size' => 10);
        $order = "update_time desc";
        $result = apiCall(VTaskProductSearchWayApi::QUERY,array($map,$page,$order));

        $this->assign("type",$type);
        if($result['status']){
            $this->assign("show",$result['info']['show']);
            $this->assign("list",$result['info']['list']);
        }

		$this -> display();
	}


    /**
     *
     */
	public function edit_search() {

		$map=array('id'=>I('id',0));
		$map = array('uid' => $this->userinfo['id'] ) ;

		$product = apiCall(VTaskProductSearchWayApi::GET_INFO, array($map));

		$this->assign('search',$product['info']);
		$this -> display();
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
	 * 商品搜索方式删除
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
	 * 更新商品信息
     * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
    public function productedit() {

        $id = I('id', '');
        $entity = array('price' => I('price', ''), 'position' => trim(I('weizhi', '')), 'update_time' => time(), );

        $result = apiCall(TaskProductApi::SAVE_BY_ID, array($id, $entity));
        if ($result['status']) {
            $this -> success('更新成功', U('Home/SJActivity/productmanager'));
        } else {
            $this -> error($result['info']);
        }

    }

	/*
	 * 新增商品信息
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function addproduct() {
		$user = $this->userinfo;
		$gaoji = array('xinghao1' => I('xinghao1', ''),
            'price1' => I('price1', ''),
            'xinghao2' => I('xinghao2', ''),
            'price2' => I('price2', ''),
            'xinghao3' => I('xinghao3', ''),
            'price3' => I('price3', ''),
            );
		$model = serialize($gaoji);
        $entity = array(
            'uid' => $user['id'],
            'link' => I('post.url', ''),
            'price' => I('post.price', ''),
            'has_model_num' => 0,
            'position' => trim(I('post.weizhi', '')),
            'title' => I('title', ''),
            'main_img' => I('img', ''),
            'wangwang' => I('alww', ''),
            'create_time' => time(),
            'update_time' => time(),
            'status' => 1,
            'model_num_cfg' => '',
            'is_on_sale' => 1, );

		if ($gaoji['xinghao1'] == '' && $gaoji['xinghao2'] == '' && $gaoji['xinghao3'] == '') {
		} else {
			$entity['model_num_cfg'] = $model;
		}

        $result = apiCall(TaskProductApi::ADD, array($entity));

        if ($result['status']) {
            $this -> success('商品添加成功', U('Home/SJActivity/productmanager'));
        } else {
            $this -> error($result['info']);
        }

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

    /**
     * 创建搜索
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     */
	public function createsearch() {
		$this -> assign('head_title', "宝贝街-创建搜索");
		$this -> display();
	}

	/**
	 * 创建搜索中的下拉框信息
     * @author 老胖子-何必都 <hebiduhebi@126.com>
	 */
	public function select() {

		$map = array(
            'uid' => UID ,
            'is_on_sale' => 1,
            'title' => array('like', "%" . I('q', '', 'trim') . "%"),
		);
		$page = array('curpage' => I('get.p', 0), 'size' => 200);
		$result = apiCall(HomePublicApi::Product_QueryAll, array($map, $page));
		$this -> success($result['info']['list']);
	}

	/**
	 * 保存搜索
     * @author 老胖子-何必都 <hebiduhebi@126.com>
	 */
	public function save() {
		$user = $this->userinfo;
		$iscz = I('iscz',0);
		$id   = I('id',0);
		$type = I('type',0);

        if($iscz == 0) {
            $this->error('获取信息失败');
        }

        $entity = array('update_time' => time(),
            'pid' => I('pid', ''),
            'search_url' => I('search_url', ''),
            'search_q' => I('search_q', ''),
            'search_order' => I('search_order', ''),
            'search_condition' => I('search_xz', ''),
            'status'=>2,
        );

		if($id > 0){
            $result = apiCall(ProductSearchWayApi::SAVE_BY_ID, array($id,$entity));
		}else{
            $entity['uid'] = $user['id'];
            $entity['dtree_type'] = $type;
            $entity['status'] = $iscz;
            $entity['create_time'] = time();
            $result = apiCall(HomePublicApi::ProductSearchWay_Add, array($entity));
		}

        if ($result['status']) {
            $this -> success('操作成功', U('Home/SJActivity/productsele'));
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
					$this->success('发放成功！');
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
		$this -> display();
	}
	/*
	 * 修改任务时间
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function uptime()
    {
        $id = I('post.task_id', 0);

        $rwcount = I('post.rwcount', 0);
        $stime = I('post.stime', 0);
        $etime = I('post.etime', 0);
        $stime = strtotime($stime);
        $etime = strtotime($etime);
        if ($stime > $etime) {
            $tmp = $stime;
            $stime = $etime;
            $etime = $tmp;
        }

        $rwcount = intval($rwcount);
        $entity = array(
            'start_time' => $stime,
            'end_time' => $etime
        );
        if ($rwcount > 0) {
            $result = apiCall(TaskPlanApi::GET_INFO, array(array('id'=>$id)));

            if($result['status']){

                $elapse = $rwcount - intval($result['info']['task_cnt']);
                $yuecount = $result['info']['yuecount'] + $elapse;

                if($yuecount < 0){
                    $this->error("剩余份数不能小于0!");
                }else{
                    $entity['yuecount'] = $yuecount;
                }

            }


            $entity['task_cnt'] = $rwcount;
        }

        $result = apiCall(TaskPlanApi::SAVE_BY_ID, array($id, $entity));
        if ($result['status']) {
            $this->success('操作成功');
        }else{
            $this->error($result['info']);
        }
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

    /**
     * 删除任务
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     */
    public function delete_task(){
        $id = I('get.id',0);

        $result = apiCall(TaskHisApi::GET_INFO,array(array('task_id'=>$id)));

        if($result['status'] && is_array($result['info'])){
            $this->error("已经有人接了任务，不能删除该任务!");
        }

        $result = apiCall(TaskHasProductApi::DELETE,array(array('task_id'=>$id)));

        if($result['status']) {
            $result = apiCall(TaskApi::DELETE, array(array('id' => $id)));
            if($result['status']) {
                $this->success('操作成功!');
            }
        }
    }

}
