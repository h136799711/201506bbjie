<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 青 <99701759@qq.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Home\Controller;
use Admin\Api\AdminPublicApi;
use Admin\Api\DatatreeApi;
use Admin\Model\DatatreeModel;
use Common\Model\ProductSearchWayModel;
use Home\Api\BbjmemberApi;
use Home\Api\BbjmemberSellerApi;
use Home\Api\FinAccountBalanceHisApi;
use Home\Api\HomePublicApi;
use Home\Api\ProductSearchWayApi;
use Home\Api\TaskApi;
use Home\Api\TaskHasProductApi;
use Home\Api\TaskHisApi;
use Home\Api\TaskPlanApi;
use Home\Api\TaskProductApi;
use Home\Api\VBbjmemberInfoApi;
use Home\Api\VTaskHisInfoApi;
use Home\Api\VTaskProductInfoApi;
use Home\Api\VTaskProductSearchWayApi;
use Home\Logic\TaskHelperLogic;
use Home\Model\BbjmemberSellerModel;
use Home\Model\FinAccountBalanceHisModel;
use Home\Model\FinFucoinHisModel;
use Home\Model\TaskHisModel;
use Home\Model\TaskLogModel;
use Home\Model\TaskModel;
use Money\Logic\TaskLogic;
use Think\Controller;

/*
 * 资金提现
 */
class SJActivityController extends SjController {

    protected function _initialize(){
        parent::_initialize();
        $this->checkLogin();
    }

    /**
     * 发放任务
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     */
    public function give_task_to(){

        $uid = I("get.uid",0);
        $task_id = I('get.task_id',0);
        $tp_id = I('get.tp_id',0);

        $logic = new TaskLogic();
        $result = $logic->sellerGiveTaskTo($uid,$task_id,$tp_id);

        if($result['status']){
            $this->success('成功分配任务');
        }else{
            $this->error($result['info']);
        }


//        $seller_uid = $this->uid;
//        $helper = new TaskHelperLogic();
//        $result = $helper->giveTaskFromTo($seller_uid,$uid,$task_id);
//
//        if($result['status']){
//            $this->success($result['info']);
//        }else{
//            $this->error($result['info']);
//        }

    }

    /**
     * 审批用户
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     */
    public function sh_user(){
        $id  = I('get.id',0);
        $tp_id  = I('get.tp_id',0);//任务计划id

        $page = array('curpage'=>I('get.p',1),'size'=>10);
        $params = array();
        $result = apiCall(VBbjmemberInfoApi::QUERY_SH_USER,array($page,$params));

        if($result['status']){
            $list = $result['info']['list'];

            foreach($list as &$vo){
                $order = 'get_task_time desc';
                $map = array(
                    'seller_uid'=>$this->uid,
                    'uid'=>$vo['uid'],
                );

                $result = apiCall(VTaskHisInfoApi::GET_INFO,array($map,$order));


                if($result['status']){
                    $vo['_last_get_task'] = $result['info'];
                }
            }

            $this->assign("tp_id",$tp_id);
            $this->assign("task_id",$id);
            $this->assign("list",$list);
            $this->assign("show",$result['info']['show']);
        }

        $this->boye_display();
    }

	/*
	 * 淘宝活动
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function sj_tbhd() {

        $this -> assign('head_title', "宝贝街-活动");
        $task_status = I('post.task_status','open');
        $this->assign("task_status",$task_status);
        switch($task_status){
            case "open":
                $task_status = TaskModel::STATUS_TYPE_OPEN;
                break;
            case "pause":
                $task_status = TaskModel::STATUS_TYPE_PAUSE;
                break;
            case "over":
                $task_status = TaskModel::STATUS_TYPE_OVER;
                break;
            default:
                $task_status = TaskModel::STATUS_TYPE_OPEN;
                break;
        }

		$user = $this->userinfo;
        $uid = $user['uid'];

		$page = array('curpage' => I('get.p', 0), 'size' => 5);
		$map  = array(
            'uid' => $uid,
            'task_status' => $task_status
        );
		$order = "create_time desc";

        $result = apiCall(TaskApi::QUERY,array($map,$page,$order));

        $task = $result['info']['list'];
        $show = $result['info']['show'];

        $api = new VTaskProductInfoApi();
        $task_his_api = new TaskHisApi();
        foreach($task as &$vo){
            $result = $api->queryNoPaging(array('task_id'=>$vo['id']));
            if($result['status']){
                $vo['_products'] = $result['info'];
            }

            $map = array('task_id'=>$vo['id']);
            $result = apiCall(TaskPlanApi::SUM,array($map,'yuecount'));

            $all_plan_total = $result['info'];

            $vo['_all_task'] = $all_plan_total;
            $vo['_doing_task'] = 0;
            $vo['_done_task'] = 0;
            $vo['_suspend_task'] = 0;

            $result = $task_his_api->count(array('do_status'=>array('neq',TaskHisModel::DO_STATUS_CANCEL),'task_id'=>$vo['id']));
            if($result['status']){
                $vo['_all_task'] += $result['info'];
            }

            $result = $task_his_api->count(array('do_status'=>TaskHisModel::DO_STATUS_RETURNED_MONEY,'task_id'=>$vo['id']));
            if($result['status']){
                $vo['_done_task'] = $result['info'];
            }

            $result = $task_his_api->count(array('do_status'=>TaskHisModel::DO_STATUS_SUSPEND,'task_id'=>$vo['id']));
            if($result['status']){
                $vo['_suspend_task'] = $result['info'];
            }

            $vo['_doing_task'] = $vo['_all_task'] - $vo['_done_task'] - $all_plan_total;

        }

		$this->assign('task',$task);
		$this->assign('show',$show);

        $helper = new TaskHelperLogic();
        $result = $helper->countStatusCnt($this->uid);
        $this->assign('count',$result);

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
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function alluser(){
        $code = I('post.code','');
		$this -> assign('head_title', "宝贝街-所有试民");
        $task_id = I('get.id',0);

        $result = apiCall(TaskApi::GET_INFO,array(array('id'=>$task_id)));

        if($result['status']){
            $task = $result['info'];
            $this->assign("task",$task);
        }

        $map = array('task_id' => $task_id);

        //任务包含的商品
        $result = apiCall(VTaskProductInfoApi::QUERY_NO_PAGING,array($map));
        $products = $result['info'];

        $this -> assign('products', $products);
        $map = array(
//            'seller_uid'=>$task['uid'],
            'task_id'=>$task_id,
            'do_status'=>array('not in',array(TaskHisModel::DO_STATUS_SUSPEND,TaskHisModel::DO_STATUS_RETURNED_MONEY,TaskHisModel::DO_STATUS_CANCEL))
        );
        if(!empty($code)){
            $map['_string'] = '(id = '.$code.' or tb_orderid = '.$code.' or tb_account = '.$code.' )';
            $this->assign("code",$code);
        }

        $result  = apiCall(VTaskHisInfoApi::QUERY,array($map));

        $this -> assign('list', $result['info']['list']);
        $this -> assign('show', $result['info']['show']);


        $this -> assign('received_goods', TaskHisModel::DO_STATUS_RECEIVED_GOODS);
        $this -> assign('reject_order', TaskHisModel::DO_STATUS_REJECT);
        $this -> assign('submit_order', TaskHisModel::DO_STATUS_SUBMIT_ORDER);

        $this->display();

	}


	/**
	 * 任务计划
     * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	 public function task_play(){
         $this->reloadUserInfo();
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
         $cnt_map['do_status'] = array('not in',array(TaskHisModel::DO_STATUS_RETURNED_MONEY,TaskHisModel::DO_STATUS_CANCEL));

         $result = apiCall(TaskHisApi::COUNT,array($cnt_map));
         $doing_cnt = $result['info'];

         $cnt_map['do_status'] = TaskHisModel::DO_STATUS_RETURNED_MONEY ;
         $result = apiCall(TaskHisApi::COUNT,array($cnt_map));
         $done_cnt = $result['info'];

//         $result = apiCall(TaskHisApi::COUNT,array($cnt_map));
//         $total_cnt = $result['info'];

         $result = apiCall(TaskPlanApi::QUERY_NO_PAGING,array(array('task_id'=>$task_id)));
         $plan_list = $result['info'];
         if(empty($all_plan_total)){
             $all_plan_total = 0;
         }

         $this -> assign('all_plan_total',$all_plan_total);
         $this -> assign('doing_cnt',$doing_cnt);
         $this -> assign('done_cnt',$done_cnt);
         $this -> assign('other_cnt',$all_plan_total-$doing_cnt-$done_cnt);
         $this -> assign('products', $products);
         $this -> assign('task',$task);
         $this -> assign('plan_list',$plan_list);
         $this->display();

	 }

    /**
     * 返回搜索方式
     */
    public function searchWay(){

        $q = I('get.q','');

        $map = array();
        if(!empty($q)){
            $map['search_q'] = array('like','%'.$q.'%');
        }
        $map['uid'] = $this->uid;
        $expire_time = time() - 7*24*3600;
        $map['update_time'] = array('gt',$expire_time);
        $map['status'] = "1";
        $page = array('curpage'=>I('get.p',0),'size'=>10);
        $result = apiCall(VTaskProductSearchWayApi::QUERY,array($map,$page));
        if($result['status']){
            $this->success($result['info']['list']);
        }else{
            $this->error($result['info']);
        }

    }


	 /*
	  * 发放任务
	  * @author 老胖子-何必都 <hebiduhebi@126.com>
	  * */
	public function create_tp(){

        $this->reloadUserInfo();
        $search_way_id = I('post.search_way_id',0);
        if(empty($search_way_id)){
            $this->error('请选择搜索方式!');
        }
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

        $result = apiCall(TaskApi::GET_INFO,array(array('id'=>$task_id)));

        $defray = $count * $result['info']['task_gold'];
        if($this->userinfo['coins'] < $defray ){
            $this->error("余额不足,请充值!");
        }

		$entity=array(
			'uid'=>$this->uid,
			'start_time'=>$start_time ,
			'end_time'=>$end_time ,
			'enter_way'=>I('sele_type',0),
			'task_cnt'=>$count,
			'create_time'=>time(),
			'search_way_id'=>$search_way_id,
			'task_id'=>$task_id,
			'yuecount'=>$count,
            'frozen_money'=>$defray,
		);


        $result = apiCall(TaskPlanApi::ADD, array($entity));
        $entity = array(
            'uid' => $this->uid,
            'defray' => $defray ,
            'income' => '0.000',
            'create_time' => time(),
            'notes' => '商家创建了任务计划',
            'dtree_type' => FinAccountBalanceHisModel::TYPE_FREEZE_CREATE_TASK_PLAN,
            'left_money'=>$this->userinfo['coins'] - $defray,
            'frozen_money'=>$this->userinfo['frozen_money'] + $defray,
            'status' => 1,
            'extra'=>'',
            );


        $result = apiCall(FinAccountBalanceHisApi::ADD, array($entity));
        if ($result['status']) {

            //账户余额
            apiCall(BbjmemberSellerApi::SET_DESC,array(array('uid'=>$this->uid),"coins",$defray));
            apiCall(BbjmemberSellerApi::SET_INC,array(array('uid'=>$this->uid),"frozen_money",$defray));
            apiCall(TaskApi::SET_INC,array(array('id'=>$task_id),"frozen_money",$defray));

            $this->success('发放成功！');

        }else{
            $this->error($result['info']);
        }

		
		
	}

	/*
	 * 改变任务状态
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function changestatus() {

		$status = I('get.status','');

        if($status != BbjmemberSellerModel::SELF_GET && $status != BbjmemberSellerModel::SELLER_SELECT){
            $this->error("任务状态非法!");
        }

        $entity = array('task_gettype' => $status);

        $result = apiCall(BbjmemberSellerApi::SAVE_BY_ID, array($this->uid, $entity));

        if ($result['status']) {
            $this->updateTaskGetType($status);
            $this -> success('任务领取状态修改成功', U('Home/Usersj/index'));
        } else {
            $this -> error($result['info']);
        }

	}

    /**
     * 发货信息
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     */
    public function delivery_order(){
        $id = $this->_param('id','');
        $express_name = $this->_param('express_name','');
        $express_code = $this->_param('express_code','');
        $express_no = $this->_param('express_no','');
        $express_price = $this->_param('express_price','');

        $result = apiCall(TaskHisApi::GET_INFO,array(array('id'=>$id)));
        if($result['status'] && is_array($result['info'])){
            $his = $result['info'];
        }


        $entity = array(
            'express_name'=>$express_name,
            'express_code'=>$express_code,
            'express_no'=>$express_no,
            'express_price'=>$express_price,
            'do_status'=>TaskHisModel::DO_STATUS_DELIVERY_GOODS,
        );

        $result = apiCall(TaskHisApi::SAVE_BY_ID,array($id,$entity));

        if($result['status']){

            $notes = "商家已发出快递，".$express_name." , ".$express_no;
            task_log($id,$his['tpid'],$his['uid'],$his['task_id'],TaskLogModel::TYPE_SELLER_DELIVERY,$notes);
            $this->success("操作成功!");
        }else{
            $this->error("操作失败!");
        }


    }

    /**
     * 驳回了该订单
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     */
    public function reject_order(){

        $reason = I('post.reason','');
        $id = $this->_param('id','');
        $result = apiCall(TaskHisApi::GET_INFO,array(array('id'=>$id)));
        if($result['status'] && is_array($result['info'])){
            $his = $result['info'];
        }

        $entity = array(
            'notes'=>$reason,
            'do_status'=>TaskHisModel::DO_STATUS_REJECT
        );

        if($his['do_status'] == TaskHisModel::DO_STATUS_REJECT){
            $this->error("驳回失败(CODE＝－1)");
        }

        $result=apiCall(TaskHisApi::SAVE_BY_ID,array($id,$entity));



        if($result['status']){

            $notes = "商家驳回了您的订单，原因:".$reason;
            task_log($id,$his['tpid'],$his['uid'],$his['task_id'],TaskLogModel::TYPE_REJECT_ORDER,$notes);

            $this->success('操作成功',U('Home/SJActivity/alluser',array('id'=>$his['task_id'])));
        }else{
            $this->error('系统未知错误',U('Home/SJActivity/alluser',array('id'=>$his['task_id'])));
        }
    }


	/*
	 * 订单确认
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function confirm_order(){
        $id = $this->_param('id','');

        $result = apiCall(TaskHisApi::GET_INFO,array(array('id'=>$id)));
        if($result['status'] && is_array($result['info'])){
            $his = $result['info'];
        }

		$entity = array('do_status'=>TaskHisModel::DO_STATUS_PASS);
        if($his['do_status'] == TaskHisModel::DO_STATUS_PASS){
            $this->error("确认失败(CODE＝－1)");
        }

		$result=apiCall(TaskHisApi::SAVE_BY_ID,array($id,$entity));

		if($result['status']){

            $notes = "商家已确认了您的订单";
            task_log($id,$his['tpid'],$his['uid'],$his['task_id'],TaskLogModel::TYPE_CONFIRM_ORDER,$notes);

			$this->success('任务操作成功',U('Home/SJActivity/sj_tbhd'));
		}else{
			$this->error('系统未知错误',U('Home/SJActivity/sj_tbhd'));
		}
	}

    /**
     * 确认还款
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     */
    public function return_money(){
        $id = $this->_param('id','');

        $result = apiCall(TaskHisApi::GET_INFO,array(array('id'=>$id)));
        if($result['status'] && is_array($result['info'])){
            $his = $result['info'];
        }

        $entity = array('do_status'=>TaskHisModel::DO_STATUS_RETURNED_MONEY);
        if($his['do_status'] == TaskHisModel::DO_STATUS_RETURNED_MONEY){
            $this->error("还款失败(CODE＝－1)");
        }
        $result = array('status'=>true);
        $result=apiCall(TaskHisApi::SAVE_BY_ID,array($id,$entity));

        if($result['status']){

            $notes = "商家已确认还款，请查看资金明细!";
            task_log($id,$his['tpid'],$his['uid'],$his['task_id'],TaskLogModel::TYPE_TASK_OVER,$notes);

            $this->task_over($his);

            $this->success('任务操作成功',U('Home/SJActivity/sj_tbhd'));
        }else{
            $this->error('系统未知错误',U('Home/SJActivity/sj_tbhd'));
        }
    }

    /**
     * 任务结束归还
     * 1. 增加用户虚拟币
     * 2. 增加用户余额
     */
    private function task_over($his){

        $tb_price = $his['tb_price'];
        if($his['tb_pay_type'] != TaskHisModel::PAY_TYPE_LEGAL){
            //信用卡，花呗支付扣除手续费 1%
            $tb_price = number_format($tb_price * 0.99,2,".","");
        }
        $result = apiCall(TaskHisApi::SAVE_BY_ID,array($his['id'],array('return_money'=>$tb_price)));
        if(!$result['status']){
            $this->error($result['info']);
        }
        $map = array('uid'=>$his['uid']);

        $result = apiCall(BbjmemberApi::GET_INFO,array($map));
        if($result['status']){
            $bbj_member = $result['info'];
        }

        ifFailedLogRecord($result,__FILE__.__LINE__);

        $result = apiCall(TaskApi::GET_INFO,array(array('id'=>$his['task_id'])));
        ifFailedLogRecord($result,__FILE__.__LINE__);

        $task = false;
        if($result['status']){
            $task = $result['info'];
        }

        if(empty($task)){
            LogRecord("完成任务归还时，获取任务信息失败!",__FILE__.__LINE__);
            return;
        }

        $uid = $his['uid'];
        $notes = "商家确认还款，退还资金!";
        //1. 增加用户余额
        $result = apiCall(BbjmemberApi::ADD_MONEY,array($uid,$tb_price,$notes));

        if(!$result['status']){
            $this->error("操作失败!");
        }

        //2. 增加用户虚拟币
        $left_coin = $bbj_member['fucoin']+$task['coin'];
        $notes = "您完成了任务#".$his['id']."#,获得了 ".$task['coin'].VIRTUAL_CURRENCY;
        $result = apiCall(BbjmemberApi::ADD_FU_COINS,array($uid,$task['coin'],$notes,$left_coin,FinFucoinHisModel::PLUS_COMPLETE_TASK));

        LogRecord(json_encode($result),__FILE__.__LINE__);

        //3. 扣除商家冻结资金
//        $notes = "用户完成了任务#".$his['id']."#,还款给用户#".$uid."#,".$tb_price;
//        $result = apiCall(BbjmemberSellerApi::MINUS_FROZEN_MONEY,array($task['uid'],$tb_price,$notes));
//        LogRecord(json_encode($result),__FILE__.__LINE__);


        //4. 增加用户经验值
        $exp = $task['task_gold']*0.1;
//        +100经验，最低+20经验。
        if($exp < 20){
            $exp = 20;
        }
        if($exp > 100){
            $exp = 100;
        }
        $result = apiCall(BbjmemberApi::SET_INC,array(array('uid'=>$uid),'exp',$exp ));

        LogRecord(json_encode($result),__FILE__.__LINE__);

        //返还给推荐人 1个福币

        $result = apiCall(BbjmemberApi::GET_INFO,array(array('uid'=>$uid)));
        if($result['status'] && is_array($result['info'])){
            $reffer_uid = $result['info']['referrer_id'];
            $reffer_uid = $result['info']['referrer_id'];
            $result = apiCall(BbjmemberApi::GET_INFO,array(array('uid'=>$reffer_uid)));

            if($reffer_uid > 0 && is_array($result['info'])){
                $reffer_coin = 1;
                $left_coin = $result['info']['fucoin'];
                $notes = "您推荐的用户#".$uid."#完成了任务#".$his['id']."#,您因此获得了 ".$reffer_coin.VIRTUAL_CURRENCY;
                $result = apiCall(BbjmemberApi::ADD_FU_COINS,array($uid,$reffer_coin,$notes,$left_coin));
                if($result['status']){
                    $this->error($result['info']);
                }
            }
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
        unset($map['pid']);
        $result = apiCall(VTaskProductInfoApi::QUERY_NO_PAGING, array($map));
        $this -> assign('products', $result['info']);
        $cal_price = 0;
        if(is_array($result['info'])){

            foreach($result['info'] as $vo){
                $cal_price += ($vo['price']*$vo['num']);
            }
        }
        $this->assign('cal_price',$cal_price);


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

        session('sj_task_info', null);
		$this -> assign('head_title', "宝贝街-创建任务");
		$map = array('uid' => $this->uid,'status' => 1);
        if($this->userinfo['auth_status'] != 1){
            $this->error('您的账号未通过认证，通过认证才能发布任务');
        }

		$result = apiCall(TaskProductApi::QUERY_NO_PAGING, array($map));
		$this -> assign('pros', $result['info']);
        if($this->hasMulLink()){
            $this->assign("links",3);
        }else{
            $this->assign("links",1);
        }

		$this -> display();
	}

    /**
     * 计算佣金
     * @param $total_price
     * @param $level
     * @param $link_cnt
     * @return int|string
     */
	public function yongjin($total_price,$level,$link_cnt) {
        $inc = $total_price / 50 ;
        $base = 5;
        $percent = 1;
        if($link_cnt == 1){
            if($level == BbjmemberSellerModel::VIP_TYPE_NORMAL){
                $percent = 0.95;
            }else if($level == BbjmemberSellerModel::VIP_TYPE_SUPER){
                $percent = 0.9;
            }
        }else if($link_cnt == 3){
            if($level == BbjmemberSellerModel::VIP_TYPE_NORMAL){
                $percent = 0.5;
            }else if($level == BbjmemberSellerModel::VIP_TYPE_SUPER){
                $percent = 0.45;
            }
        }

        $ret = ($base + floor($inc))*$percent;
        $ret = number_format($ret,2,".","");

        return $ret;
	}

	/*
	 * 保存商品信息
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function save_1() {
        $this->checkLogin();
        $task = session('sj_task_info');
        if(empty($products)){
            $task = array();
            $task['_products'] = array(
                'img' => I('post.img', ''),
                'title' => I('post.title', ''),
                'position' => I('post.guige', ''),
                'price' => I('post.price',''),
                'pid' => I('post.pid', ''),
                'link' => I('post.url', ''),
                'num' => I('post.num', 1),
            );
            session('sj_task_info', $task);
        }

        $price_list = $task['_products']['price'];
        $total_price = 0;
        foreach($price_list as $key=>$vo){
            $num = $task['_products']['num'];
            $total_price += ($vo * $num[$key]);
        }

        $task['total_price'] = $total_price;
        $rebate = $this->yongjin($total_price,$this->userinfo['level'],$this->getProductNumber());
        $seller_deliver = $this->hasSellerDeliver();
        $task['rebate'] = $rebate;
        $task['seller_deliver'] = $seller_deliver;
        session('sj_task_info', $task);


        $this->assign("seller_deliver",$seller_deliver);
        $this->assign("rebate",$rebate);
        $this->assign("total_price",$total_price);
		$this -> assign('head_title', "宝贝街-创建任务");
        $this->assignYhfs();
        $this -> assign("products",$task['_products']);
		$this -> display('activity_2');
	}

    /**
     * 分配优惠信息
     */
    private function assignYhfs(){
        $map = array(
            'parentid'=>DatatreeModel::YHFS
        );

        $result = apiCall(DatatreeApi::QUERY_NO_PAGING,array($map));
        $this->assign("yhfs",$result['info']);
    }

	/*
	 * 创建任务第二步
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function activity_2() {

		if(IS_GET){

            $this->assignYhfs();
            $this -> assign('head_title', "宝贝街-创建任务");

            $task = session("sj_task_info");

            $this->assign("seller_deliver",$task['seller_deliver']);
            $this->assign("rebate",$task['rebate']);
            $this->assign("total_price",$task['total_price']);
            $this -> assign("products",$task['_products']);
		    $this -> display();
        }else{

            $task = session('sj_task_info');
            $delivery_mode = I('post.fhfs','1');
            $bzj = I('post.bzj',0);//保证金
            $task_brokerage = I('post.task_brokerage',0);//佣金
            $price = I('post.pronum',0);//用户下单单份商品金额
            $yhfs = I('post.yhfs','');//优惠方式
            $task_postage = I('post.task_postage',0);//包邮


            $rate = VIRTUAL_COIN_PERCENTAGE;
            $task['coin'] = $task_brokerage * $rate * VIRTUAL_RATE; //可获得福币
            $task['dtree_preferential'] = $yhfs;

            $task['task_brokerage'] = $task_brokerage;
            if(intval($fhfs) == 1){
                $task['task_postage'] = 10;
            }else{
                $task['task_postage'] = $task_postage;
            }

            $task['task_gold'] = $bzj;
            $task['delivery_mode'] = $delivery_mode;

            session("sj_task_info",$task);

            $this->display("activity_3");
        }
	}


	/*
	 * 创建任务第三步
	 * */
	public function activity_3() {
        if(IS_GET) {

            $this->assign('head_title', "宝贝街-创建任务");
            $this->display();
        }else{

            $task = session("sj_task_info");
            $task_name = I('post.task_name','');
            $notes = I('post.notes','');
            $chat_argot = I('post.chat_argot','');
            $entity = array(
                'uid'=>$this->uid,
                'create_time'=>time(),
                'aliwawa'=>$this->userinfo['aliwawa'],
                'delivery_mode'=>$task['delivery_mode'],
                'task_gold'=>$task['task_gold'],
                'task_brokerage'=>$task['task_brokerage'],
                'task_postage'=>$task['task_postage'],
                'update_time'=>time(),
                'dtree_preferential'=>$task['dtree_preferential'],
                'coin'=>$task['coin'],
                'task_name'=>$task_name,
                'notes'=>$notes,
                'chat_argot'=>$chat_argot,
                'task_do_type'=>1,
                'task_status'=>'1',
                'frozen_money'=>0,
            );
            $result = apiCall(TaskApi::ADD,array($entity));
            if(!$result['status']){
                $this->error($result['info']);
            }
            $task_id = $result['info'];

            $products = $task['_products'];

            $img_arr = $products['img'];

            foreach($img_arr as $key=>$vo){
                $has_product = array(
                    'task_id'=>$task_id,
                    'pid'=>$products['pid'][$key],
                    'num'=>$products['num'][$key],
                    'sku'=>$products['position'][$key],
                    'pname'=>$products['title'][$key],
                    'create_time'=>time(),
                );
                $result = apiCall(TaskHasProductApi::ADD,array($has_product));
                if(!$result['status']){
                    $this->error($result['info']);
                }
            }


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
        $map['do_status'] = array('not in',array(TaskHisModel::DO_STATUS_CANCEL,TaskHisModel::DO_STATUS_RETURNED_MONEY));

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

    /**
     * 开启任务
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     * @date 20160509
     */
    public function start_task(){

        $id = I('get.id',0);
        $entity = array('task_status' => TaskModel::STATUS_TYPE_OPEN);
        $return = apiCall(HomePublicApi::Task_SaveByID, array($id, $entity));
        if ($return['status']) {
            $this -> success('操作成功', U('Home/SJActivity/sj_tbhd'));
        }else{
            $this->error($return['info']);
        }
    }


	/*
	 * 暂停搜索
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function ztsele() {

		$id = I('get.id',0);
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
		$id = I('get.id',0);
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
        }else if($type == 'all'){
            $map['status'] = array('neq','0');
        }

		$page = array('curpage' => I('get.p', 0), 'size' => 10);
        $order = "update_time desc";
        $result = apiCall(VTaskProductSearchWayApi::QUERY,array($map,$page,$order));

        $this->assign("type",$type);
        if($result['status']){
            $this->assign("expire_time",7*24*3600);
            $this->assign("show",$result['info']['show']);
            $this->assign("list",$result['info']['list']);
        }

		$this -> display();
	}


    /**
     * 更新搜索
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     */
	public function edit_search() {

		$map = array('uid' => $this->uid,'id'=>$this->_param('id',0 ));

		$product = apiCall(VTaskProductSearchWayApi::GET_INFO, array($map));

        $link = $product['info']['link'];
        if(strpos($link,'taobao.com') >= 0){
            $this->assign("url","www.taobao.com");
        }elseif(strpos($link,'tmall.com') >= 0){
            $this->assign("url","www.tmall.com");
        }else{
            $this->assign("url","未知");
        }

        $search_info = $product['info'];
        $condition = json_decode($search_info['search_condition'],JSON_OBJECT_AS_ARRAY);

        if(is_array($condition)){
            $attrs = $condition['attr'];
            if(strlen($attrs) > 0){
                $tmp_arr = explode(",",$attrs);
                $condition['attr_list'] = $tmp_arr;
            }

            $search_info = array_merge($search_info,$condition);
        }

        $this->assign('search',$search_info);
        $this->assign('aliwawa',$this->userinfo['aliwawa']);
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
        $code = $this->_param('code',''); //商品货号

        $entity = array(
            'wangwang'=>$this->userinfo['aliwawa'],
            'p_code'=>$code,
            'price' => I('price', ''),
            'position' => trim(I('weizhi', '')),
            'update_time' => time(), );

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
        $img = $this->_param('main_img','');
        $title = $this->_param('title','');
        $aliwawa = $this->userinfo['aliwawa'];
        $code = $this->_param('code',''); //商品货号
        $url = $this->_param('url','');


        $entity = array(
            'uid' => $user['id'],
            'link' =>trim($url),
            'price' => I('post.price', ''),
            'has_model_num' => 0,
            'position' => trim(I('post.weizhi', '')),
            'title' => $title,
            'main_img' => $img,
            'wangwang' => $aliwawa,
            'create_time' => time(),
            'update_time' => time(),
            'status' => 1,
            'model_num_cfg' => '',
            'p_code'=>$code,
            'is_on_sale' => 1,
            );

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

        $founded = $this->_param('iscz',2);
		$id   = I('id',0);
        $search_xz = I("post.search_xz","");
        $search_filter = I("post.search_filter","");
        $search_attr = I("post.search_attr","");


        $price_min = I("post.price_min",0);
        $price_max = I("post.price_max",0);

        $price_min = floatval($price_min);
        $price_max = floatval($price_max);

        $condition = array(
            'tab'=>$search_xz,
            'filter'=>$search_filter,
            'attr'=>$search_attr,
        );

        $entity = array(
            'update_time' => time(),
            'price_min'=>$price_min,
            'price_max'=>$price_max,
            'pid' => I('pid', ''),
            'search_url' => I('search_url', ''),
            'search_q' => I('search_q', ''),
            'search_order' => I('search_order', ''),
            'search_condition' => json_encode($condition),
            'status'=>2,
            'dtree_type'=>ProductSearchWayModel::SEARCH_TYPE_KEYWORD,
        );

        if($founded == 1){
            $entity['status'] = $founded;
        }

		if($id > 0){
            $result = apiCall(ProductSearchWayApi::SAVE, array(array('id'=>$id),$entity));
		}else{
            $entity['uid'] = $this->uid;
            $entity['dtree_type'] = ProductSearchWayModel::SEARCH_TYPE_KEYWORD;
            $entity['create_time'] = time();
            $result = apiCall(ProductSearchWayApi::ADD, array($entity));
		}

        if ($result['status']) {
            $this -> success('操作成功', U('Home/SJActivity/productsele'));
        }

	}
	/*
	 * 检查url
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function checkurl(){
        $url = $this->_param('url','');

        if(empty($url)){
            $this->ajaxReturn(0,'json');
        }

		$map = array(
			'link'=>$url,
		);

		$result = apiCall(TaskProductApi::GET_INFO, array($map));

		if($result['status'] && is_array($result['info'])){
			$this->ajaxReturn(0,'json');
		}else{
			$this->ajaxReturn(1,'json');
		}
		
	}
//	/*upcount修改任务份数
//	 * */
//	public function upcount(){
//		$user=session('user');
//	 	$id=I('sid',0);
//		$mapp=array('id'=>$id);
//		$money=I('dfmonfey','');
//		$result = apiCall(HomePublicApi::TaskPlan_Query, array($mapp));
//		$fenshu=$result['info'][0]['task_cnt'];
//		$yue=$result['info'][0]['yuecount'];
//		$map=array('task_cnt'=>I('rwcount',1)+$fenshu,'yuecount'=>I('rwcount',1)+$yue);
//		$zongjia=I('rwcount',1)*$money;
//		if($zongjia<=0){
//			$this->error('请填写正确的份数!');
//		}else{
//			$results = apiCall(HomePublicApi::TaskPlan_SaveByID, array($id,$map));
//			$entitya = array('uid' => $user['info']['id'], 'defray' => $zongjia , 'income' => '0.000', 'create_time' => time(), 'notes' => '增加份数冻结任务佣金', 'dtree_type' => 5, 'status' => 3, );
//			$resulta = apiCall(HomePublicApi::FinAccountBalanceHis_Add, array($entitya));
//			if ($resulta['status']) {
//				$return1=M('bbjmemberSeller')->where('uid='.$user['info']['id'])->setDec('coins',$zongjia);
//				$return2=M('bbjmemberSeller')->where('uid='.$user['info']['id'])->setInc('frozen_money',$zongjia);
//				if($return1 && $return2){
//					$this->success('发放成功！');
//				}
//			}
//		}
//	 }
/**/
//	public function phonesele(){
//		$headtitle = "宝贝街-创建搜索";
//		$this -> assign('head_title', $headtitle);
//		$user = session('user');
//		$map = array('parentid' => 36, );
//		$result = apiCall(AdminPublicApi::Datatree_QueryNoPaging, array($map));
//		$mapa = array('uid' => $user['info']['id']);
//		$sj = apiCall(HomePublicApi::Bbjmember_Seller_Query, array($mapa));
//		$this -> assign('aliwawa', $sj['info'][0]['aliwawa']);
//		$this -> assign('username', $user['info']['username']);
//		$this -> display();
//	}
	/*
	 * 修改任务时间
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
//	public function uptime()
//    {
//        $id = I('post.task_id', 0);
//
//        $rwcount = I('post.rwcount', 0);
//        $stime = I('post.stime', 0);
//        $etime = I('post.etime', 0);
//        $stime = strtotime($stime);
//        $etime = strtotime($etime);
//        if ($stime > $etime) {
//            $tmp = $stime;
//            $stime = $etime;
//            $etime = $tmp;
//        }
//
//        $rwcount = intval($rwcount);
//        $entity = array(
//            'start_time' => $stime,
//            'end_time' => $etime
//        );
//        if ($rwcount > 0) {
//            $result = apiCall(TaskPlanApi::GET_INFO, array(array('id'=>$id)));
//
//            if($result['status']){
//
//                $elapse = $rwcount - intval($result['info']['task_cnt']);
//                if($elapse < 0){
//                    $this->error("修改的任务分数必须大于之前的");
//                }
//                //需要增加冻结资金
//                $elapse_gold = $elapse * $result['info']['task_gold'];
//                dump($result);
//                exit;
//                $entity = array(
//                    'uid' => $this->uid,
//                    'defray' => $elapse_gold ,
//                    'income' => '0.000',
//                    'create_time' => time(),
//                    'notes' => '商家增加了任务份数',
//                    'dtree_type' => FinAccountBalanceHisModel::TYPE_FREEZE_CREATE_TASK_PLAN,
//                    'left_money'=>$this->userinfo['coins'] - $elapse_gold,
//                    'frozen_money'=>$this->userinfo['frozen_money'] + $elapse_gold,
//                    'status' => 1,
//                    'extra'=>'',
//                    );
//
//                $result = apiCall(FinAccountBalanceHisApi::ADD, array($entity));
//            }
//
//
//            $entity['task_cnt'] = $rwcount;
//        }
//
//        $result = apiCall(TaskPlanApi::SAVE_BY_ID, array($id, $entity));
//        if ($result['status']) {
//            $this->success('操作成功');
//        }else{
//            $this->error($result['info']);
//        }
//    }
//	public function zdysave() {
//		$user=session('user');
//		$entity = array('uid'=>$user['info']['id'] ,'dtree_type' => 1, 'status' => 1, 'create_time' => time(), 'update_time' => time(), 'pid' => I('pid', ''), 'search_url' => '', 'search_q' => I('text', ''), 'search_order' => I('search_order', ''), 'search_condition' => I('weizhi', ''), );
////		dump($entity);
//		$result = apiCall(HomePublicApi::ProductSearchWay_Add, array($entity));
//		if ($result['status']) {
//			$this -> success('添加搜索成功', U('Home/SJActivity/createsearch'));
//		}else{
//			$this->error('创建自定义搜索失败!');
//		}
//
//	}

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

    /**
     * 结算
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     */
    public function task_clear(){

        $task_info = array();
        $task_id = $this->_param('id',0);

        $result = apiCall(TaskApi::GET_INFO,array(array('id'=>$task_id)));
        if($result['status']){
            $task_info = $result['info'];
            $this->assign("task_info",$task_info);
        }
        if(is_null($task_info)){
            $this->error("任务信息获取失败!");
        }

        $map = array(
            'seller_uid'=>$this->uid,
            'task_id'=>$task_id,
            'do_status'=>TaskHisModel::DO_STATUS_RETURNED_MONEY
        );

        $page = array('curpage'=>I('get.p',0),'size'=>10);
        $order = " update_time desc ";
        $result = apiCall(VTaskHisInfoApi::QUERY,array($map,$page,$order));

        if($result['status']){
            $this->assign("list",$result['info']['list']);
            $this->assign("show",$result['info']['show']);
        }

        $all_tb_price = 0;
        $all_task_brokerage = 0;
        $all_tb_express = 0;
        $this->assign("all_tb_price",$all_tb_price);
        $this->assign("all_task_brokerage",$all_task_brokerage);
        $this->assign("all_tb_express",$all_tb_express);

        $result = apiCall(TaskHisApi::SUM,array($map,'task_brokerage'));

        if($result['status'] && intval($result['info']) > 0){
            $all_task_brokerage = $result['info'];
            $this->assign("all_task_brokerage",$all_task_brokerage);
        }

        $result = apiCall(VTaskHisInfoApi::SUM,array($map,'return_money'));

        if($result['status'] && intval($result['info']) > 0){
            $all_tb_price = $result['info'];
            $this->assign("all_tb_price",$all_tb_price);
        }

        $result = apiCall(TaskHisApi::SUM,array($map,'express_price'));

        if($result['status'] && intval($result['info']) > 0){

            $all_tb_express = $result['info'];
            $this->assign("all_tb_express",$all_tb_express);
        }



        //1. 查找是否存在 正进行中的任务
        $map = array(
            'seller_uid'=>$this->uid,
            'task_id'=>$task_id,
        );

        $map['do_status'] = array('not in',array(TaskHisModel::DO_STATUS_RETURNED_MONEY,TaskHisModel::DO_STATUS_CANCEL));

        $result = apiCall(TaskHisApi::GET_INFO,array($map));
        $this->assign("can_clear",1);

        if($result['status'] && is_array($result['info'])){
            $this->assign("can_clear",0);
        }

        //end


        $expect_money = $task_info['frozen_money'] - number_format($all_tb_price+$all_task_brokerage+$all_tb_express  ,2,".","");

        $expect_money = $expect_money < 0 ? 0.00 : $expect_money;
        $this->assign("expect_money",$expect_money);
        $this->display();
    }


    /**
     * 任务结算，对任务进行结算
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     */
    public function all_task_over(){

        //1. 对剩余的冻结资金 进行解冻,返还
        $this->reloadUserInfo();
        $task_id = $this->_param('id',0);
        $result = apiCall(TaskApi::GET_INFO,array(array('id'=>$task_id)));
        if($result['status']){
            $task_info = $result['info'];
        }

        if(is_null($result['info'])){
            $this->error("任务信息获取失败!");
        }
        //不等于取消、完成、已还款的任务时
        $map = array(
            'seller_uid'=>$this->uid,
            'task_id'=>$task_id,
        );
        $map['do_status'] = array('not in',array(TaskHisModel::DO_STATUS_RETURNED_MONEY,TaskHisModel::DO_STATUS_CANCEL));
        $result = apiCall(TaskHisApi::GET_INFO,array($map));
        if($result['status'] && is_array($result['info'])){
            $this->error("当前仍有任务正在进行中...");
        }

        $map = array(
            'seller_uid'=>$this->uid,
            'task_id'=>$task_id,
            'do_status'=>TaskHisModel::DO_STATUS_RETURNED_MONEY
        );

        $result = apiCall(TaskHisApi::SUM,array($map,'task_brokerage'));
        $all_task_brokerage = 0;
        $all_tb_price = 0;
        $all_tb_express = 0;

        if($result['status']){
            $all_task_brokerage = $result['info'];
        }

        $result = apiCall(VTaskHisInfoApi::SUM,array($map,'return_money'));

        if($result['status']){
            $all_tb_price = $result['info'];
        }

        $result = apiCall(TaskHisApi::SUM,array($map,'express_price'));

        if($result['status']){
            $all_tb_express = $result['info'];
        }

        $all_use_price = $all_task_brokerage + $all_tb_express + $all_tb_price;

        $left_price = $task_info['frozen_money'] - $all_use_price;

        if($left_price < 0){
            $left_price = 0;
        }


        //记录帐号资金变动日志
        $notes = "任务#".$task_id."#已结算,花费了".$all_use_price."元";
        $entity = array(
            'uid'=>$this->uid,
            'defray'=>$all_use_price,
            'income'=>$left_price,
            'create_time'=>time(),
            'notes'=>$notes,
            'dtree_type'=>FinAccountBalanceHisModel::TYPE_TASK_OVER_CLEAR,
            'imgurl'=>'',
            'status'=>1,
            'left_money'=>$this->userinfo['coins'] + $left_price,
            'frozen_money'=>$this->userinfo['frozen_money'] - $task_info['frozen_money'],
            'extra'=>'',
        );

        $result = apiCall(FinAccountBalanceHisApi::ADD,array($entity));


        $result = apiCall(BbjmemberSellerApi::SAVE_BY_ID,array($this->uid,array('frozen_money'=>$entity['frozen_money'],'coins'=>$entity['left_money'])));

        if($result['status']){
            $result  = apiCall(TaskApi::SAVE_BY_ID,array($task_id,array('task_cost_money'=>$all_use_price,'task_status'=>TaskModel::STATUS_TYPE_OVER)));
            $this->success("结算成功!",U('Home/SJActivity/sj_tbhd'));
        }else{
            $this->error("操作失败！");
        }

    }



}
