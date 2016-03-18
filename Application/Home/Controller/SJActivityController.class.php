<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 青 <99701759@qq.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Home\Controller;
use Common\Model\ProductSearchWayModel;
use Home\Api\BbjmemberSellerApi;
use Home\Api\FinAccountBalanceHisApi;
use Home\Api\ProductSearchWayApi;
use Home\Api\TaskApi;
use Home\Api\TaskHasProductApi;
use Home\Api\TaskHisApi;
use Home\Api\TaskPlanApi;
use Home\Api\TaskProductApi;
use Home\Api\VTaskHisInfoApi;
use Home\Api\VTaskProductInfoApi;
use Home\Api\VTaskProductSearchWayApi;
use Home\Model\BbjmemberSellerModel;
use Home\Model\FinAccountBalanceHisModel;
use Home\Model\TaskHisModel;
use Home\Model\TaskLogModel;
use Think\Controller;
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
            $result = $task_his_api->count(array('do_status'=>array('neq',TaskHisModel::DO_STATUS_CANCEL),'task_id'=>$vo['id']));
            if($result['status']){
                $vo['_all_task'] += $result['info'];
            }

            $result = $task_his_api->count(array('do_status'=>TaskHisModel::DO_STATUS_DONE,'task_id'=>$vo['id']));
            if($result['status']){
                $vo['_done_task'] = $result['info'];
            }

            $vo['_doing_task'] = $vo['_all_task'] - $vo['_done_task'] - $all_plan_total;

        }


		$this->assign('task',$task);
		$this->assign('show',$show);

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
            $this->assign("task",$result['info']);
        }

        $map = array('task_id' => $task_id);

        //任务包含的商品
        $result = apiCall(VTaskProductInfoApi::QUERY_NO_PAGING,array($map));
        $products = $result['info'];

        $this -> assign('products', $products);
        $map = array('do_status'=>array('not in',array(TaskHisModel::DO_STATUS_DONE,TaskHisModel::DO_STATUS_CANCEL)));
        if(!empty($code)){
            $map['_string'] = '(id = '.$code.' or tb_orderid = '.$code.' or tb_account = '.$code.' )';
            $this->assign("code",$code);
        }

        $result  = apiCall(VTaskHisInfoApi::QUERY,array($map));

        $this -> assign('list', $result['info']['list']);
        $this -> assign('show', $result['info']['show']);


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
         $cnt_map['do_status'] = array('not in',array(TaskHisModel::DO_STATUS_DONE,TaskHisModel::DO_STATUS_CANCEL));

         $result = apiCall(TaskHisApi::COUNT,array($cnt_map));
         $doing_cnt = $result['info'];

         $cnt_map['do_status'] = TaskHisModel::DO_STATUS_DONE ;
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



	 /*
	  * 发放任务
	  * @author 老胖子-何必都 <hebiduhebi@126.com>
	  * */
	public function create_tp(){

        $this->reloadUserInfo();
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
			'search_way_id'=>0,
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
            'status' => 1,
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
        $this -> assign("products",$task['_products']);
		$this -> display('activity_2');
	}

	/*
	 * 创建任务第二步
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function activity_2() {
		if(IS_GET){

            $this -> assign('head_title', "宝贝街-创建任务");

            $task = session("sj_task_info");
            $this->assign("seller_deliver",$task['seller_deliver']);
            $this->assign("rebate",$task['rebate']);
            $this->assign("total_price",$task['total_price']);
            $this -> assign("products",$task['_products']);
		    $this -> display();
        }else{

            $delivery_type = I('post.fhtype','1');
            $bzj = I('post.bzj',0);//保证金
            $yj = I('post.yj',0);//佣金
            $price = I('post.xiadan',0);//用户下单单份商品金额
            $yhfs = I('post.yhfs','');//优惠方式
            $task = session('sj_task_info');
            $by = I('post.by',0);

            $task['coin'] = $yj * 0.7 * VIRTUAL_RATE; //可获得福币
            $task['dtree_preferential'] = $yhfs;
            $task['task_brokerage'] = $yj;
            $task['task_postage'] = $by;
            $task['task_gold'] = $bzj;
            $task['delivery_type'] = $delivery_type;
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
                'delivery_mode'=>$task['delivery_type'],
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

		$map = array('id'=>$this->_param('id',0));

		$map = array('uid' => $this->uid ) ;

		$product = apiCall(VTaskProductSearchWayApi::GET_INFO, array($map));
        $link = $product['info']['link'];
        if(strpos($link,'taobao.com') >= 0){
            $this->assign("url","www.taobao.com");
        }elseif(strpos($link,'tmall.com') >= 0){
            $this->assign("url","www.tmall.com");
        }else{
            $this->assign("url","未知");
        }
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
		$user = $this->userinfo;
        $founded = $this->_param('iscz',0);
		$id   = I('id',0);

        $entity = array(
            'update_time' => time(),
            'pid' => I('pid', ''),
            'search_url' => I('search_url', ''),
            'search_q' => I('search_q', ''),
            'search_order' => I('search_order', ''),
            'search_condition' => I('search_xz', ''),
            'status'=>2,
            'dtree_type'=>ProductSearchWayModel::SEARCH_TYPE_KEYWORD,
        );

		if($id > 0){
            $result = apiCall(ProductSearchWayApi::SAVE_BY_ID, array($id,$entity));
		}else{
            $entity['uid'] = $user['id'];
            $entity['dtree_type'] = ProductSearchWayModel::SEARCH_TYPE_KEYWORD;
            $entity['status'] = $founded;
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
