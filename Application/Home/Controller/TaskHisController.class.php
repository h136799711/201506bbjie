<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 16/3/22
 * Time: 09:44
 */

namespace Home\Controller;


use Admin\Api\DatatreeApi;
use Home\Api\TaskLogApi;
use Home\Api\VTaskHisInfoApi;
use Home\Api\VTaskProductInfoApi;
use Home\Logic\TaskHelperLogic;
use Home\Model\TaskHisModel;
use Uclient\Api\UserApi;

class TaskHisController extends SjController {

    protected function _initialize(){
        parent::_initialize();
    }


    /**
     * 所有做任务的
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     */
    public function all(){
        $type = $this->_param('status','');

        $this->assign('status',$type);
        if(empty($type)){
            $this->assign('status','all');
        }

        $map = array(
            'seller_uid'=>$this->uid,
        );

        $param = array(
            'status'=>$type,
        );

    //     等待审核   wait_check
    //     确认还款   wait_return_money
    //     平台发货   delivery_platform
    //     等待用户收货   user_receive
    //     未开始   not_start
    //     驳回   reject

        if(!empty($type)){
            switch($type){
                case "wait_check":
                    $map['do_status'] = TaskHisModel::DO_STATUS_SUBMIT_ORDER;
                    break;
                case "wait_return_money":
                    $map['do_status'] = TaskHisModel::DO_STATUS_RECEIVED_GOODS;
                    break;
                case "delivery_platform":
                    $map['do_status'] = TaskHisModel::DO_STATUS_PASS;
                    break;
                case "user_receive":
                    $map['do_status'] = TaskHisModel::DO_STATUS_DELIVERY_GOODS;
                    break;
                case "not_start":
                    $map['do_status'] = TaskHisModel::DO_STATUS_NOT_START;
                    break;
                case "reject":
                    $map['do_status'] = TaskHisModel::DO_STATUS_REJECT;
                    break;
                default:
                    break;
            }
        }
        $page = array('curpage'=>I('get.p',1),'size'=>10);
        $order = "get_task_time desc";
        $result = apiCall(VTaskHisInfoApi::QUERY,array($map,$page,$order,$param));

        if($result['status']){
            $this->assign("list",$result['info']['list']);
            $this->assign("show",$result['info']['show']);
        }

        $helper = new TaskHelperLogic();
        $result = $helper->countStatusCnt($this->uid);
        $this->assign('count',$result);

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
     * 订单审核，ajax返回部分html
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     */
    public function sh_view(){
        $task_his_id = I('get.id',0);

        if($task_his_id > 0 ){


            $result = apiCall(TaskLogApi::QUERY_NO_PAGING,array(array('task_his_id'=>$task_his_id)));
            $this->assign("log_list",$result['info']);
            $result = apiCall(VTaskHisInfoApi::GET_INFO,array(array('id'=>$task_his_id)));
            $this->assign("task_his",$result['info']);
            $uid = $result['info']['seller_uid'];
            $task_id = $result['info']['task_id'];
            $result = apiCall(UserApi::GET_INFO,array($uid));
            $this->assign("seller_user",$result['info']);

            $result = apiCall(VTaskProductInfoApi::GET_INFO,array(array('task_id'=>$task_id)));
            $this->assign("product",$result['info']);
        }

        $this->display();
    }
}