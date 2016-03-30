<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 青 <99701759@qq.com> 
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Home\Controller;
use Admin\Model\MsgboxModel;
use Cms\Api\VPostInfoApi;
use Home\Api\AddressApi;
use Home\Api\BbjmemberApi;
use Home\Api\ProductExchangeApi;
use Home\Api\TaskApi;
use Home\Api\TaskHasProductApi;
use Home\Api\TaskHisApi;
use Home\Api\TaskLogApi;
use Home\Api\TaskPlanApi;
use Home\Api\VBbjmemberSellerInfoApi;
use Home\Api\VCanDoTaskApi;
use Home\Api\VMsgInfoApi;
use Home\Api\VProductExchangeInfoApi;
use Home\Api\VTaskHisInfoApi;
use Home\Api\VTaskProductInfoApi;
use Home\Api\VTaskProductSearchWayApi;
use Home\Model\ProductExchangeModel;
use Home\Model\TaskHisModel;
use Home\Model\TaskLogModel;
use Home\Model\TaskModel;
use Think\Controller;
use Home\Api\HomePublicApi;
use Admin\Api\AdminPublicApi;


class SMActivityController extends HomeController {

    public function _initialize(){
        parent::_initialize();
        $this->checkLogin();
        $upload_url = C('SITE_URL').'/index.php/Home/Avatar/upload';
        $this->getLastestNotice();
        $this->not_read_msg_cnt();
        $this->get_doing_task_cnt();

        $this->assign("upload_url",$upload_url);
    }

	/*
	 * 改变状态
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function changestatus(){
        $type = I('get.type','');
        if($type == 'on'){
            $status = 1;
        }else{
            $status = 0;
        }

        $entity = array('task_status' => $status);

        $result=apiCall(BbjmemberApi::SAVE_BY_ID,array($this->uid,$entity));

        if($result['status']){
            $this->reloadUserInfo();
            $this->success('状态设置成功',U('Home/Usersm/manager_rw'));
        }else{
            $this->error($result['info']);
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
					'summary'=>"您的订单".$results['info'][0]['tb_orderid']."已确认收货请尽快返款",
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
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function taskmoney(){


		$entity=array('daily_task_money'=>I('post.tmoney','0'));
		$result = apiCall(BbjmemberApi::SAVE_BY_ID,array($this->uid,$entity));
		if($result['status']){
            $this->reloadUserInfo();
			$this->success('修改成功',U('Home/Usersm/manager_rw'));
		}else{
            $this->error('未知错误');
        }
	}
	/*
	 * 设置淘宝
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function settaobao(){
        $taobao = I('post.taobao','');
        if(empty($taobao)){
            $this->error("请填写淘宝帐号");
        }

        $result = apiCall(BbjmemberApi::GET_INFO,array(array('taobao_account'=>$taobao)));

        if($result['status'] && is_array($result['info'])){
            $uid = $result['info']['uid'];
            if($uid != $this->uid){
                $this->error($taobao."已被其它帐号绑定(".$uid.")");
            }
        }

		$entity=array(
            'taobao_account'=>$taobao,
            'auth_status'=>1,
        );
        $result = apiCall(BbjmemberApi::SAVE_BY_ID,array($this->uid,$entity));
		if($result['status']){

            $this->reloadUserInfo();
			$this->success('修改成功',U('Home/Usersm/manager_rw'));
		}else{
            $this->error("未知错误");
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
	 * 任务管理
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function task_manager(){

        $this -> assign('head_title', "宝贝街-任务管理");
        $do_status = I('get.do_status','');

        $map    = array('uid'=>$this->uid);
        switch($do_status){
            case "doing":
                $map['do_status'] = array('not in',array(TaskHisModel::DO_STATUS_CANCEL,TaskHisModel::DO_STATUS_RETURNED_MONEY));
                break;
            case 'cancel':
                $map['do_status'] = TaskHisModel::DO_STATUS_CANCEL;
                break;
            case 'reject':
                $map['do_status'] = TaskHisModel::DO_STATUS_REJECT;
                break;
            case 'submit':
                $map['do_status'] = TaskHisModel::DO_STATUS_SUBMIT_ORDER;
                break;
            case 'pass':
                $map['do_status'] = TaskHisModel::DO_STATUS_PASS;
                break;
            case 'wait_return':
                $map['do_status'] = TaskHisModel::DO_STATUS_WAIT_RETURN;
//                $map['order_status'] =
                break;
            default:

                break;
        }

        $page   = array('curpage' => I('get.p', 0), 'size' => 5);
        $result = apiCall(VTaskHisInfoApi::QUERY,array($map,$page,'get_task_time desc'));

        $his_list = $result['info']['list'];
        for ($i = 0; $i < count($his_list); $i++) {

            //获取发布任务的商家信息
            $seller_uid = $his_list[$i]['seller_uid'];
            $map = array('uid' => $seller_uid);
            $result = apiCall(VBbjmemberSellerInfoApi::GET_INFO, array($map));
            $his_list[$i]['_seller']= $result['info'];

            //获取任务产品信息
            $plan_id = $his_list[$i]['tpid'];
            $map = array('plan_id'=>$plan_id);
            $result = apiCall(VTaskProductInfoApi::QUERY_NO_PAGING, array($map));
            $his_list[$i]['_products']= $result['info'];
        }

        $this->assign('status_delivery_order',TaskHisModel::DO_STATUS_DELIVERY_GOODS);
        $this->assign('status_received_goods',TaskHisModel::DO_STATUS_RECEIVED_GOODS);
        $this->assign('status_wait_return',TaskHisModel::DO_STATUS_WAIT_RETURN);
        $this->assign('status_pass_order',TaskHisModel::DO_STATUS_PASS);
        $this->assign('status_reject_order',TaskHisModel::DO_STATUS_REJECT);
        $this->assign('status_not_start',TaskHisModel::DO_STATUS_NOT_START);
        $this->assign('status',$do_status);
        $this->assign('his_list',$his_list);
        $this->assign('show',$result['info']['show']);

        $this -> boye_display();
	}


	/*
	 * 取消任务
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function qxtask(){

        $id = I('get.id',0);
        $task_id = I('get.task_id',0);
		$map=array('do_status'=>TaskHisModel::DO_STATUS_CANCEL);
		$result=apiCall(TaskHisApi::SAVE_BY_ID,array($id,$map));
		$plan_id = $this->_param('tpid','','缺少任务计划ID');

		if($plan_id != 0){
            $map = array('id'=>$plan_id);
            $result = apiCall(TaskPlanApi::SET_INC,array($map,"yuecount",1));
		}else{
			$this->error('系统未知错误',U('Home/SMActivity/task_manager',array('do_status'=>'doing')));
		}

        $notes = "用户在 ".date("Y-m-d H:i:s",time())." 取消了任务";
        task_log($id , $plan_id,$this->uid,$task_id,TaskLogModel::TYPE_CANCEL_TASK,$notes);

		if($result['status']){
            $map['uid'] = $this->uid;
            $result = apiCall(BbjmemberApi::SET_INC,array($map,'cancel_task_cnt',1));

			$this->success('任务操作成功',U('Home/SMActivity/task_manager',array('do_status'=>'doing')));
		}else{
			$this->error('系统未知错误',U('Home/SMActivity/task_manager',array('do_status'=>'doing')));
		}
	}


	/**
	 * 领取任务
     * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function gettask(){

		$now_time = time();
        $this->reloadUserInfo();

        $map = array('uid'=>$this->uid);
        $map['do_status'] = array('notin',array(TaskHisModel::DO_STATUS_RETURNED_MONEY , TaskHisModel::DO_STATUS_DONE,TaskHisModel::DO_STATUS_CANCEL ));

		$result = apiCall(TaskHisApi::GET_INFO,array($map));

        if($this->userinfo['auth_status'] == 0){
            $this->error("用户信息认证完成才可以接任务哦");
        }

		if(is_array($result['info'])) {
            $this->error("请先完成或取消之前接的任务！");
        }


        $map = array('yuecount'=>array('gt',0),'start_time'=>array('lt',time()),'end_time'=>array('gt',time()));

        $result = apiCall(TaskPlanApi::COUNT,array($map));
        $total = 0;
        if($result['status']){
            $total = $result['info'];
        }else{
            $this->error($result['info']);
        }

        $rand_id = rand(0,$total-1);
        $map['id'] = array('egt',$rand_id);
        $result = apiCall(VCanDoTaskApi::GET_INFO,array($map,'id desc'));

        if( !is_array($result['info']) ){
            $this->error('暂无可接任务，请稍候再试',U('Home/Index/sm_manager'));
        }else{
            $task_plan = $result['info'];
            $result = apiCall(TaskApi::GET_INFO,array(array('id'=>$task_plan['task_id'])));
            if(!$result['status'] || is_null(($result['info']))){
                $this->error("发生错误，请联系管理员!");
            }

            $task_brokerage  = $result['info']['task_brokerage'];

            //新增到接收任务表
            $entity = array(
                'tpid'=>$task_plan['id'],
                'uid'=>$this->uid,
                'order_status'=>2,
                'create_time'=>$now_time,
                'get_task_time'=>$now_time,
                'do_status'=>TaskHisModel::DO_STATUS_NOT_START,
                'task_id'=>$task_plan['task_id'],
                'task_brokerage'=>$task_brokerage,
                'notes'=>'',
                'tb_orderid'=>'',
                'tb_address'=>'',
                'tb_price'=>0,
                'tb_pay_type'=>TaskHisModel::PAY_TYPE_LEGAL,

                'tb_account'=>$this->userinfo['taobao_account'],
            );

            $result = apiCall(TaskHisApi::ADD,array($entity));


            if($result['status']){
                $notes = "用户 (".$this->userinfo['username'].") 领取了任务";
                task_log($result['info'] , $task_plan['id'],$this->uid,$task_plan['task_id'],TaskLogModel::TYPE_GET_TASK,$notes);

                $map = array('id'=>$task_plan['id']);
                $result = apiCall(TaskPlanApi::SET_DEC,array($map,'yuecount',1));

                if($result['status']){

                    $this->success('成功接收任务，正在跳转任务界面',U('Home/SMActivity/task_manager',array('do_status'=>'doing')));
                }else{
                    $this->error('领取任务失败');
                }

            }
        }
		
	}


    /**
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     */
	public function rws(){

		$this -> assign('head_title', "宝贝街-任务书");

        $id = I('get.id',0);

		$task=apiCall(VTaskHisInfoApi::GET_INFO, array(array('id'=>$id)));
        $do_status = $task['info']['do_status'];
		$this->assign('task',$task['info']);

        $seller_uid = $task['info']['seller_uid'];
        $result = apiCall(VBbjmemberSellerInfoApi::GET_INFO,array(array('uid'=>$seller_uid)));

        if($result['status']){
            $this->assign("seller",$result['info']);
        }

        $result = apiCall(VTaskProductInfoApi::QUERY_NO_PAGING,array(array('task_id'=>$task['info']['task_id'])));

        if($result['status']){
            $product = $result['info'];
            $this->assign("products",$result['info']);
        }

        $result = apiCall(VTaskProductSearchWayApi::GET_INFO,array(array('pid'=>$product[0]['pid'])));

        if($result['status']){

            $search = $result['info'];

            if(strpos($search['link'],'taobao.com') >= 0){
                $search['url'] = "www.taobao.com";
            }elseif(strpos($search['link'],'tmall.com') >= 0){
                $search['url'] = "www.tmall.com";
            }else{
                $search['url'] = "未知";
            }

            $this->assign("search",$search);
        }
        //对不同的任务状态 显示不同的页面
        if($do_status == TaskHisModel::DO_STATUS_NOT_START){
            $this->getAddressList();
            $this->getGoodsForDelivery($task);
            $this->display('rws');
        }elseif($do_status == TaskHisModel::DO_STATUS_SUBMIT_ORDER){
            $this->getLogList($id);
            $this->display('rws_wait_check');
        }elseif($do_status == TaskHisModel::DO_STATUS_CANCEL){
            $this->getLogList($id);
            $this->display('rws_cancel');
        }elseif($do_status == TaskHisModel::DO_STATUS_REJECT){
            $this->getAddressList();
            $this->getGoodsForDelivery($task);
            $this->display('rws_reject');
        }else{
            $this->getLogList($id);
            $this->display('rws_wait_check');
        }



	}

    private function getAddressList(){
        $result = apiCall(AddressApi::QUERY,array(array('uid'=>$this->uid),array('curpage'=>1,'size'=>10)));
        if($result['status']){
            $this->assign("address_list",$result['info']['list']);
        }
    }

    /**
     * 提交订单信息
     */
    public function submit_order(){
        $id = I('post.id',0);
        $order_num = I('post.order_num','');
        $zhifu_price = I('post.zhifu_price',0,'floatval');
        $notes = I('post.notes','');
        $pay_type = I('post.pay_type','');
        $tb_address = $this->_param('tb_address','');

        if($zhifu_price <= 0){
            $this->error("支付金额是不是填错了？");
        }
        $his = array();
        $result = apiCall(TaskHisApi::GET_INFO,array(array('id'=>$id)));
        if($result['status'] && is_array($result['info'])){
            $his = $result['info'];
        }

        $entity = array(
            'tb_orderid'=>$order_num,
            'to_seller_notes'=>$notes,
            'tb_price'=>$zhifu_price,
            'tb_pay_type'=>$pay_type,
            'tb_address'=>$tb_address,
            'do_status'=>TaskHisModel::DO_STATUS_SUBMIT_ORDER,
        );

        $result = apiCall(TaskHisApi::SAVE_BY_ID,array($id,$entity));

        if($result['status']){
            $notes = "用户(".$this->userinfo['username'].") 提交了订单";
            task_log($id,$his['tpid'],$this->uid,$his['task_id'],TaskLogModel::TYPE_SUBMIT_TB_ORDER,$notes);

            $this->success("操作成功!");
        }

        $this->error("操作失败!");


    }


    /**
     * 确认收货
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     */
    public function receive_goods(){

        $id = I('get.id',0);
        if(empty($id)){
            $this->error("缺少id");
        }

        $result = apiCall(TaskHisApi::GET_INFO,array(array('id'=>$id)));

        if($result['status'] && is_array($result['info'])){
            $his = $result['info'];
        }

        $entity = array('do_status'=>TaskHisModel::DO_STATUS_RECEIVED_GOODS);

        $result = apiCall(TaskHisApi::SAVE_BY_ID,array($id,$entity));

        if($result['status']){

            $notes = "用户(".$this->userinfo['username'].") 已经确认收货";
            task_log($id,$his['tpid'],$this->uid,$his['task_id'],TaskLogModel::TYPE_SUBMIT_TB_ORDER,$notes);

            $this->success("操作成功!");
        }

        $this->error("操作失败!");

    }

    /**
     * 获取发货的物品信息
     */
    private function getGoodsForDelivery($task){
        $mode = intval($task['info']['delivery_mode']);
        if($mode == TaskModel::DELIVERY_MODE_PLATFORM) {
            //平台发货
            $map = array(
                'uid'=>$this->userinfo['id'],
                'exchange_status'=>ProductExchangeModel::CHECK_SUCCESS,
            );
            $result  = apiCall(VProductExchangeInfoApi::GET_INFO,array($map,'update_time desc'));

            $this->assign("exchange",$result['info']);


        }elseif($mode == TaskModel::DELIVERY_MODE_SELLER){
            //商家发货
//            dump("123456");

        }

    }

    /**
     * 获取任务日志
     * @param $his_id
     */
    private function getLogList($his_id){

        $result = apiCall(TaskLogApi::QUERY_NO_PAGING,array(array('task_his_id'=>$his_id)));

        if($result['status']){
            $this->assign('log_list',$result['info']);
        }

    }



    /**
     * 获取试民正在进行的任务数
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     */
    private function get_doing_task_cnt(){

        $map = array('uid'=>$this->uid);
        $map['do_status'] = array('not in',array(TaskHisModel::DO_STATUS_CANCEL,TaskHisModel::DO_STATUS_DONE,TaskHisModel::DO_STATUS_RETURNED_MONEY));
        $result = apiCall(TaskHisApi::COUNT,array($map));
        if($result['status']){
            $this->assign('doing_task',$result['info']);
        }else{
            $this->assign('doing_task',0);
        }
    }


    /**
     * 未读消息
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     */
    private function not_read_msg_cnt(){

        $result = apiCall(VMsgInfoApi::COUNT,array(array('to_id'=>$this->uid,'msg_status'=>MsgboxModel::NOT_READ)));
        $not_read_msg_cnt =  $result['info'];
        if(empty($not_read_msg_cnt)){
            $not_read_msg_cnt = 0;
        }

        $this->assign('not_read_msg_cnt',$not_read_msg_cnt);


    }

    /**
     * 获取最新公告信息
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     */
    private function getLastestNotice(){
        $map = array();
        $order = " post_modified desc ";

        $result = apiCall(VPostInfoApi::GET_INFO,array($map, $order));
        $this->assign('zxgg',$result['info']);
    }

}
