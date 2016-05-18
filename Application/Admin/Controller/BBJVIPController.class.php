<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 青<99701759@qq.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Admin\Controller;
use Admin\Api\DatatreeApi;
use Common\Api\AccountApi;
use Home\Api\BbjmemberApi;
use Home\Api\BbjmemberSellerApi;
use Home\Api\FinAccountBalanceHisApi;
use Home\Api\HomePublicApi;
use Home\Api\ProductSearchWayApi;
use Home\Api\TaskApi;
use Home\Api\TaskHisApi;
use Home\Api\TaskLogApi;
use Home\Api\VBbjmemberInfoApi;
use Home\Api\VBbjmemberSellerInfoApi;
use Home\Api\VFinAccountBalanceHisApi;
use Home\Api\VTaskHisInfoApi;
use Home\Api\VTaskProductSearchWayApi;
use Home\ConstVar\UserTypeConstVar;
use Home\Logic\TaskHelperLogic;
use Home\Model\BbjmemberSellerModel;
use Home\Model\FinAccountBalanceHisModel;
use Home\Model\TaskHisModel;
use Home\Model\TaskLogModel;
use Home\Model\TaskModel;

class BBJVIPController extends AdminController{
    /**
     * 财务审核
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     */
	public function index(){
        $status = $this->_param("status",'2');
        $uid = $this->_param('uid',0);

		$page = array('curpage' => I('get.p', 0), 'size' => 10);

		$map=array(
            'status'=>$status
        );

        $param = array(
            'status'=>$status
        );

        if($uid > 0){
            $map['uid'] = $uid;
            $param['uid'] = $uid;
        }

        $order = "create_time desc";

        $result = apiCall(VFinAccountBalanceHisApi::QUERY,array($map,$page,$order,$param));
        $this->assign('status',$status);
        $list = $result['info']['list'];

        foreach($list as &$vo){

            if($vo['dtree_type'] == FinAccountBalanceHisModel::TYPE_WITHDRAW){
                $vo['_withdraw'] = json_decode($vo['extra'],JSON_OBJECT_AS_ARRAY);
            }
        }

		$this->assign('list',$list);
		$this->assign('show',$result['info']['show']);
        $this->assign("withdraw",FinAccountBalanceHisModel::TYPE_WITHDRAW);
		$this->display();
	}	
	/*
	 * 商品搜索管理审核
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function check_search(){
        $status = $this->_param('status','2');
		$map = array('status'=>$status);
		$page = array('curpage' => I('get.p', 0), 'size' => 10 );
        $order = "create_time desc";
        $result = apiCall(VTaskProductSearchWayApi::QUERY,array($map,$page,$order));

        $this->assign('status',$status);
        $this->assign('list',$result['info']['list']);
        $this->assign('show',$result['info']['show']);
		$this->display();
	}

    /**
     * 资金变动审核，
     * 提现、充值
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     */
	public function check(){

		$id = I('id','');
		$map = array( 'id'=>$id );
		$result=apiCall(FinAccountBalanceHisApi::GET_INFO, array($map));
		$uid=$result['info']['uid'];
        $dtree_type = $result['info']['dtree_type'];
        $defray = $result['info']['defray'];
        $income = $result['info']['income'];

        $result = apiCall(AccountApi::GET_INFO,array($uid));
        $left_entity = array(
            'left_money'=>0,
            'frozen_money'=>0,
        );
        $userinfo = $result['info'];
        if($userinfo['user_type'] == UserTypeConstVar::BBJ_MEMBER_GROUP){
            //试民
            if($dtree_type == FinAccountBalanceHisModel::TYPE_WITHDRAW){//提现
                $left_frozen_money = $userinfo['frozen_money'] - $defray;
                if($left_frozen_money < 0){
                    $this->error("该账户出现异常!");
                }
                $entity = array(
                    'frozen_money'=>$left_frozen_money,
                );
                $left_entity['left_money'] = $userinfo['coins'];
                $left_entity['frozen_money'] = $left_frozen_money;
                $result = apiCall(BbjmemberApi::SAVE_BY_ID,array($uid,$entity));
            }

        }else if($userinfo['user_type'] == UserTypeConstVar::BBJ_SHOP_GROUP){
            //商家
            if($dtree_type == FinAccountBalanceHisModel::TYPE_RECHARGE){//充值
                $entity = array(
                    'coins'=>$income+$userinfo['coins'],
                );

                $left_entity['left_money'] = $entity['coins'];
                $left_entity['frozen_money'] = $userinfo['frozen_money'];
                $result = apiCall(BbjmemberSellerApi::SAVE_BY_ID,array($uid,$entity));

            }elseif($dtree_type == FinAccountBalanceHisModel::TYPE_WITHDRAW){
                //提现
                $entity = array(
                    'frozen_money'=> $userinfo['frozen_money'] - $defray,
                );
                if($entity['frozen_money'] < 0){
                    $this->error("该账户出现异常!");
                }

                $left_entity['left_money'] = $userinfo['coins'];
                $left_entity['frozen_money'] = $entity['frozen_money'];

                $result = apiCall(BbjmemberSellerApi::SAVE_BY_ID,array($uid,$entity));

            }

        }else{
            $this->error("无法识别的用户类型");
        }

        if($result['status']){
            //设置 账户余额、记录改成通过状态
            $left_entity['status'] = FinAccountBalanceHisModel::STATUS_PASSED;

            $result = apiCall(FinAccountBalanceHisApi::SAVE_BY_ID,array($id,$left_entity));
            if($result['status']){
                $this->success("充值成功!",U('Admin/BBJVIP/index'));
            }
        }

	}

	/*
	 * 资金审核失败
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function moneychecksb(){

        $id = I('id','');
        $map = array( 'id'=>$id );
        $result=apiCall(FinAccountBalanceHisApi::GET_INFO, array($map));
        $uid=$result['info']['uid'];
        $dtree_type = $result['info']['dtree_type'];
        $defray = $result['info']['defray'];
        $income = $result['info']['income'];
        if($result['info']['status'] != FinAccountBalanceHisModel::STATUS_WAIT_CHECK){
            $this->error("该记录状态非法!");
        }

        $result = apiCall(AccountApi::GET_INFO,array($uid));

        $userinfo = $result['info'];
        if($userinfo['user_type'] == UserTypeConstVar::BBJ_MEMBER_GROUP){
            //试民
            if($dtree_type == FinAccountBalanceHisModel::TYPE_WITHDRAW){//提现

                $entity = array(
                    'coins'=>$userinfo['coins']+$defray,
                    'frozen_money'=>$userinfo['frozen_money']-$defray,
                );

                if($entity['frozen_money'] < 0){
                    $this->error("该账户出现异常!");
                }

                $result = apiCall(BbjmemberApi::SAVE_BY_ID,array($uid,$entity));

            }

        }else if($userinfo['user_type'] == UserTypeConstVar::BBJ_SHOP_GROUP){
            //商家
            if($dtree_type == FinAccountBalanceHisModel::TYPE_WITHDRAW){
                //提现
                $entity = array(
                    'coins'=>$userinfo['coins']+$defray,
                    'frozen_money'=>$userinfo['frozen_money']-$defray,
                );

                if($entity['frozen_money'] < 0){
                    $this->error("该账户出现异常!");
                }

                $result = apiCall(BbjmemberSellerApi::SAVE_BY_ID,array($uid,$entity));

            }

        }else{
            $this->error("无法识别的用户类型");
        }

        if($result['status']){

            $entity['status'] = 4;
            $result = apiCall(FinAccountBalanceHisApi::SAVE_BY_ID,array($id,$entity));
            if($result['status']){
                $this->success("操作成功!",U('Admin/BBJVIP/index'));
            }
        }

	}
	/**
	 * 会员管理
     * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function vipmanager(){
		$page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
        $map = array();

		$result = apiCall(VBbjmemberSellerInfoApi::QUERY, array($map,$page));

		$this->assign('list',$result['info']['list']);
		$this->assign('show',$result['info']['show']);

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

    /**
     * 试民
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     */
	public function checksm(){
        $taobao = I('post.taobao','');
        if(empty($taobao)){
            $taobao = I('get.taobao','');
        }
        $auth_status = I('post.auth_status','');
        if(empty($auth_status)){
            $auth_status = I('get.auth_status','0');
        }
        $map = array(
            'auth_status'=>$auth_status,
        );
        $param = array(
            'auth_status'=>$auth_status,
        );
        if(!empty($taobao)){
            $map['taobao_account'] = $taobao;
            $param['taobao_account'] = $taobao;
        }

		$page = array('curpage' => I('get.p', 0), 'size' => 10);
        $order = "create_time desc";
		$result=apiCall(BbjmemberApi::QUERY, array($map,$page,$order,$param));

        $this->assign('taobao',$taobao);
        $this->assign('auth_status',$auth_status);
		$this->assign('list',$result['info']['list']);
		$this->assign('show',$result['info']['show']);
		$this->display();
	}

    /**
     * 试民管理
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     */
	public function managersm(){
		$username = $this->_param('username','');

        $map = array();
        $param = array();
        if (!empty($username)) {
			$map['username'] = array('like','%'. $username . '%');
            $param['username'] = $username;
		}

        $page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
        $order = "create_time desc";

        $result = apiCall(VBbjmemberInfoApi::QUERY, array($map,$page,$order,$param));

		$this->assign('list',$result['info']['list']);
		$this->assign('show',$result['info']['show']);

		$this->display();
	}

    /**
     * 商家审核
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     */
	public function checksj(){
        $aliwawa = I('post.aliwawa','');
        if(empty($aliwawa)){
            $aliwawa = I('get.aliwawa','');
        }

        $auth_status = I('post.auth_status','');
        if(empty($auth_status)){
            $auth_status = I('get.auth_status','0');
        }

		if (!empty($aliwawa)) {
			$map['aliwawa'] = array('like',$aliwawa . '%');
		}
        $map['auth_status']= $auth_status;
		$params = array(
            'auth_status'=>$auth_status,
            'aliwawa'=>$aliwawa,
        );

		$page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
        $order = "update_time desc";
		$result=apiCall(VBbjmemberSellerInfoApi::QUERY, array($map,$page,$order,$params));

        $this->assign("auth_status",$auth_status);
        $this->assign("aliwawa",$aliwawa);
		$this->assign('list',$result['info']['list']);
		$this->assign('show',$result['info']['show']);

		$this->display();
	}

    /**
     * 管理商家
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     */
	public function managersj(){
		$username = $this->_param('username','');

        $map = array();
        $params = array();
        if (!empty($username)) {
			$map['username'] = array('like','%'. $username . '%');
            $params['username'] = $username;
		}
		
		$page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
        $order = "create_time desc";
        $result = apiCall(VBbjmemberSellerInfoApi::QUERY,array($map,$page,$order,$params));

		$this->assign('list',$result['info']['list']);
		$this->assign('show',$result['info']['show']);

		$this->display();
	}

    /**
     * 试民 和 商家的审核
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     */
	public function check_user(){

		$id=I('get.id',0);
        $type = I('get.type','0');

		$entity=array('auth_status'=>1);

        if($type == 0){
            $result=apiCall(BbjmemberApi::SAVE_BY_ID, array($id,$entity));

            if($result['status']){
                $this->success('审核成功',U('Admin/BBJVIP/checksm'));
            }
        }else{
            $result=apiCall(BbjmemberSellerApi::SAVE_BY_ID, array($id,$entity));

            if($result['status']){
                $this->success('审核成功',U('Admin/BBJVIP/checksj'));
            }
        }

        if(!$result['status']){
            $this->error($result['info']);
        }
	}

	public function checksb(){

		$id=I('get.id',0);
		$entity=array('auth_status'=>2);
		$result=apiCall(BbjmemberSellerApi::SAVE_BY_ID, array($id,$entity));

		if($result['status']){
			$this->success('操作成功',U('Admin/BBJVIP/checksj'));
		}
	}


    /**
     * 查看用户信息
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     */
	public function view_user(){
		$map=array('uid'=>I('get.id',0));

        $uid = I('get.id',0);

        $result = apiCall(AccountApi::GET_INFO,array($uid));

        $this->assign("user_info",$result['info']);
        $this->display();
	}

    public function view_user_wallet(){
        $uid = I('get.id',0);
        $map = array(
            'uid'=>$uid,
        );
        $page = array('curpage'=>I('get.p',0),'size'=>10);
        $order = 'create_time desc';
        $result = apiCall(VFinAccountBalanceHisApi::QUERY,array($map,$page,$order));

        $this->assign("withdraw",FinAccountBalanceHisModel::TYPE_WITHDRAW);
        $this->assign("list",$result['info']['list']);
        $this->assign("show",$result['info']['show']);
        $this->display();
    }

	/*
	 * 所有任务
	 * */
	public function alltask(){
        $task_status = I('post.task_status',TaskModel::STATUS_TYPE_OPEN);
		$taskname=I('taskname','');
		if (!empty($taskname)) {
			$map['task_name'] = array('like','%'. $taskname . '%');
		}
        if(!empty($task_status)){
            $map['task_status'] = $task_status;
        }
		$page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
		$order = " create_time desc ";
		$result = apiCall(TaskApi::QUERY, array($map, $page, $order));

        $this->assign("open",TaskModel::STATUS_TYPE_OPEN);
        $this->assign("pause",TaskModel::STATUS_TYPE_PAUSE);
        $this->assign("over",TaskModel::STATUS_TYPE_OVER);

		$this->assign('list',$result['info']['list']);
		$this->assign('show',$result['info']['show']);
		$this->display();
	}
	/*
	 * 查看搜索
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function searchview(){
		$map=array('id'=>I('id',0));

		$result = apiCall(VTaskProductSearchWayApi::GET_INFO, array($map));
		$this->assign('vo',$result['info']);
		$this->display();
	}
	/*
	 * 审核通过/驳回
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function sh(){

		$id=I('id',0);
        $status = $this->_param('status',1);
		if($id!=0){
			$map=array('status'=>$status);
			$result = apiCall(HomePublicApi::ProductSearchWay_SaveByID, array($id,$map));
			if($result['status']){
				$this->success('操作成功',U('Admin/BBJVIP/check_search'));
			}
		}else{
			$this->error('参数错误');
		}
	}


	/*
	 * 任务领取管理
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function taskgetview(){


        $status = $this->_param('status','');

		$map=array(
            'task_id'=>I('get.id',0),
        );

        if(!empty($status)){
            $map['do_status'] = $status;
        }

		$page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
		$order = " create_time desc ";
		$result = apiCall(VTaskHisInfoApi::QUERY, array($map, $page, $order));
        $suspend = TaskHisModel::DO_STATUS_CANCEL.','.TaskHisModel::DO_STATUS_SUSPEND.','.TaskHisModel::DO_STATUS_RETURNED_MONEY;

        $this->assign("suspend",$suspend);
        $this->assign('status',$status);
        $this->assign('status_suspend',TaskHisModel::DO_STATUS_SUSPEND);
        $this->assign('status_return_money',TaskHisModel::DO_STATUS_RETURNED_MONEY);
        $this->assign('status_submit',TaskHisModel::DO_STATUS_SUBMIT_ORDER);
        $this->assign('status_wait_return_money',TaskHisModel::DO_STATUS_RECEIVED_GOODS);
        $this->assign('status_cancel',TaskHisModel::DO_STATUS_CANCEL);
        $this->assign('status_not_start',TaskHisModel::DO_STATUS_NOT_START);
        $this->assign('status_delivery_goods',TaskHisModel::DO_STATUS_DELIVERY_GOODS);
        $this->assign('status_wait_delivery',TaskHisModel::DO_STATUS_PASS);
		$this->assign('list',$result['info']['list']);
		$this->assign('show',$result['info']['show']);
		$this->display();
	}

    /**
     * 平台发货
     */
    public function platform_delivery(){

        $type = $this->_param('status','delivery_platform');

        $this->assign('status',$type);
        if(empty($type)){
            $this->assign('status','all');
        }

        $param = array(
            'status'=>$type,
        );

        if(!empty($type)){
            switch($type){
                case "delivery_platform":
                    $map['do_status'] = TaskHisModel::DO_STATUS_PASS;
                    break;
                case "delivery":
                    $map['express_no'] = array('gt',"0");
                    break;
                default:
                    break;
            }
        }
        //平台发货的已提交订单的任务
        $map['delivery_mode'] = TaskModel::DELIVERY_MODE_PLATFORM;

//        dump($map);
        $page = array('curpage'=>I('get.p',1),'size'=>10);
        $order = "get_task_time desc";
        $result = apiCall(VTaskHisInfoApi::QUERY,array($map,$page,$order,$param));

        if($result['status']){
            $this->assign("list",$result['info']['list']);
            $this->assign("show",$result['info']['show']);
        }

//        $helper = new TaskHelperLogic();
//        $result = $helper->countStatusCnt($this->uid);
//        $this->assign('count',$result);

        $this -> assign('received_goods', TaskHisModel::DO_STATUS_RECEIVED_GOODS);
        $this -> assign('reject_order', TaskHisModel::DO_STATUS_REJECT);
        $this -> assign('submit_order', TaskHisModel::DO_STATUS_SUBMIT_ORDER);

        $result = apiCall(DatatreeApi::QUERY_NO_PAGING,array(array('parentid'=>'120')));

        if($result['status']){
            $this->assign("express_list",$result['info']);
        }
        $this->display();
    }

    /**
     * 平台发货
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

            $notes = "系统已发出快递，".$express_name." , ".$express_no;
            task_log($id,$his['tpid'],$his['uid'],$his['task_id'],TaskLogModel::TYPE_PLATFORM_DELIVERY,$notes);
            $this->success("操作成功!");
        }else{
            $this->error("操作失败!");
        }

    }

    /**
     * 已经将提现金额转入用户账户转账
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     */
    public function task_view(){

        $map = array(
            'id'=>I('get.id',0),
        );
        $result = apiCall(TaskApi::GET_INFO,array($map));

        if($result['status']){
            $this->assign("info",$result['info']);
        }

        $this->display();
    }

    /**
     * 挂起任务
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     */
    public function suspend(){

        $id = $this->_param('id',0);

        if($id > 0){
            $result = apiCall(TaskHisApi::GET_INFO,array(array('id'=>$id)));
            if(!$result['status']){
                $this->error($result['info']);
            }

            if($task_his['do_status'] == TaskHisModel::DO_STATUS_CANCEL
            || $task_his['do_status'] == TaskHisModel::DO_STATUS_RETURNED_MONEY){
               $this->error("修改失败！");
            }

            $task_his = $result['info'];

            $task_id = $task_his['task_id'];
            $uid = $task_his['uid'];
            $plan_id = $task_his['tpid'];

            $entity = array(
                'do_status'=>TaskHisModel::DO_STATUS_SUSPEND,
            );

            $result = apiCall(TaskHisApi::SAVE_BY_ID,array($id,$entity));

            if($result['status']){
                $entity = array(
                    'task_id'=>$task_id,
                    'plan_id'=>$plan_id,
                    'uid'=>$uid,
                    'task_his_id'=>$id,
                    'dtree_type'=>TaskLogModel::TYPE_SUSPEND_TASK,
                    'log_time'=>time(),
                    'notes'=>"系统挂起了任务！" ,
                );
                apiCall(TaskLogApi::ADD,array($entity));
            }
            $this->success("操作成功");
        }else{
            $this->error('参数错误');
        }

    }


}
