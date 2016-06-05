<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 16/6/5
 * Time: 23:33
 */

namespace Money\Logic;


use Home\Api\BbjmemberApi;
use Home\Api\ProductExchangeApi;
use Home\Api\TaskApi;
use Home\Api\TaskHisApi;
use Home\Api\TaskPlanApi;
use Home\Api\VBbjmemberInfoApi;
use Home\Api\VCanDoTaskApi;
use Home\Api\VCanDoTaskWithAutoIdApi;
use Home\Api\VProductExchangeInfoApi;
use Home\Model\BbjmemberModel;
use Home\Model\ProductExchangeModel;
use Home\Model\TaskHisModel;
use Home\Model\TaskLogModel;
use Home\Model\TaskModel;

class TaskLogic {

    const TASK_DO_INTERVAL = 3;//同一家店铺接受任务的间隔时间

    /**
     * 商家分配任务给用户
     * @param $uid
     * @param $task_id
     * @param $tp_id
     * @return array
     */
    public function sellerGiveTaskTo($uid,$task_id,$tp_id){
        $now_time = time();
        $map = array('uid'=>$uid);
        $result = apiCall(VBbjmemberInfoApi::GET_INFO,array($map));
        if(!$result['status']){
            return $result;
        }

        $userinfo = $result['info'];

        $map['do_status'] = array('notin',array(TaskHisModel::DO_STATUS_SUSPEND,TaskHisModel::DO_STATUS_RETURNED_MONEY ,TaskHisModel::DO_STATUS_CANCEL ));

        $result = apiCall(TaskHisApi::GET_INFO,array($map));

        if($userinfo['auth_status'] != BbjmemberModel::AUTH_PASS){
            return array('status'=>false,'info'=>"该用户信息未审核或被删除，无法分配");
        }

        //增加数量限制
        if(is_array($result['info'])) {
            return array('status'=>false,'info'=>"该用户有正在做的任务，所以不能分配给他!");
        }

        $result = apiCall(TaskApi::GET_INFO,array(array('id'=>$task_id)));
        if(!$result['status'] || is_null(($result['info']))){
            return array('status'=>false,'info'=>"发生错误，请联系管理员");
        }

        if($result['info']['task_status'] != TaskModel::STATUS_TYPE_OPEN){
            return array('status'=>false,'info'=>"暂无可接任务，请稍候再试(-2)");
        }

        //检查该任务对应店铺是否在10天内已接收到

        $seller_uid = $result['info']['uid'];//商家ID

        if(!$this->isLegalTask($uid,$seller_uid)){
            return array('status'=>false,'info'=>"暂无可接任务，请稍候再试(-3)");
        }

        $task_brokerage  = $result['info']['task_brokerage'];

        //新增到接收任务表
        $entity = array(
            'tpid'=>$tp_id,
            'uid'=>$uid,
            'order_status'=>2,
            'create_time'=>$now_time,
            'get_task_time'=>$now_time,
            'do_status'=>TaskHisModel::DO_STATUS_NOT_START,
            'task_id'=>$task_id,
            'task_brokerage'=>$task_brokerage,
            'notes'=>'',
            'tb_orderid'=>'',
            'tb_address'=>'',
            'tb_price'=>0,
            'tb_pay_type'=>TaskHisModel::PAY_TYPE_LEGAL,
            'tb_account'=>$userinfo['taobao_account'],
            'task_from'=>TaskHisModel::COME_FROM_SELLER_GIVEN,
        );

        $notes = "商家分配给你一个任务";

        return $this->addToTaskHis($entity,$userinfo,$notes);

    }

    /*
     * 用户自主领取
     */
    public function giveTaskTo($uid){

        $now_time = time();
        $map = array('uid'=>$uid);
        $result = apiCall(VBbjmemberInfoApi::GET_INFO,array($map));
        if(!$result['status']){
            return $result;
        }

        $userinfo = $result['info'];

        $map['do_status'] = array('notin',array(TaskHisModel::DO_STATUS_SUSPEND,TaskHisModel::DO_STATUS_RETURNED_MONEY ,TaskHisModel::DO_STATUS_CANCEL ));

        $result = apiCall(TaskHisApi::GET_INFO,array($map));

        if($userinfo['auth_status'] == 0){
            return array('status'=>false,'info'=>"信息正在审核中... 审核完成才可接任务");
        }

        //增加数量控制
        if(is_array($result['info'])) {
            return array('status'=>false,'info'=>"请先完成或取消之前接的任务!");
        }

        $map = array();

        $result = apiCall(VCanDoTaskApi::COUNT,array($map));
        $total = 0;
        if($result['status']){
            $total = $result['info'];
        }else{
            return array('status'=>false,'info'=>$result['info']);
        }

        $rand_id = rand(1,$total);
        $map['row_id'] = array('egt',$rand_id);
        $result = apiCall(VCanDoTaskWithAutoIdApi::GET_INFO,array($map,'id desc'));

        if( !is_array($result['info']) ){
            return array('status'=>false,'info'=>"暂无可接任务，请稍候再试(-1)");
        }else{
            $task_plan = $result['info'];
            $result = apiCall(TaskApi::GET_INFO,array(array('id'=>$task_plan['task_id'])));
            if(!$result['status'] || is_null(($result['info']))){
                return array('status'=>false,'info'=>"发生错误，请联系管理员");
            }

            if($result['info']['task_status'] != TaskModel::STATUS_TYPE_OPEN){
                return array('status'=>false,'info'=>"暂无可接任务，请稍候再试(-2)");
            }

            //检查该任务对应店铺是否在10天内已接收到
            $task_id = $result['info']['id'];
            $seller_uid = $result['info']['uid'];//商家ID

            if(!$this->isLegalTask($uid,$seller_uid)){
                return array('status'=>false,'info'=>"暂无可接任务，请稍候再试(-3)");
            }

            $task_brokerage  = $result['info']['task_brokerage'];

            //新增到接收任务表
            $entity = array(
                'tpid'=>$task_plan['id'],
                'uid'=>$uid,
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
                'tb_account'=>$userinfo['taobao_account'],
                'task_from'=>TaskHisModel::COME_FROM_USER_APPLY,
            );

            $notes = "用户 (".$userinfo['username'].") 领取了任务";

            return $this->addToTaskHis($entity,$userinfo,$notes);
        }
    }

    /**
     * 添加到任务接受历史表中
     * @param $entity
     * @param $userinfo
     * @param $notes | 日志备注
     * @return array
     */
    public function addToTaskHis($entity,$userinfo,$notes){
        $tp_id = $entity['tpid'];
        $uid = $entity['uid'];
        $task_id = $entity['task_id'];
        //获取兑换商品信息
        $exchange_map = array('exchange_status'=>ProductExchangeModel::CHECK_SUCCESS,'uid'=>$uid);
        $result = apiCall(VProductExchangeInfoApi::GET_INFO,array($exchange_map,"update_time desc"));
        $exchange_id = 0;
        if($result['status'] && is_array($result['info'])){
            $product_info = $result['info'];
            $entity['exchange_id'] = $product_info['id'];
            $entity['express_pid'] = $product_info['p_id'];
            $entity['express_name'] = $product_info['name'];
            $exchange_id = $product_info['id'];
        }

        $result = apiCall(TaskHisApi::ADD,array($entity));


        if($result['status']){


            task_log($result['info'] ,$tp_id,$uid,$task_id,TaskLogModel::TYPE_GET_TASK,$notes);

            if($exchange_id > 0){
                apiCall(ProductExchangeApi::SAVE_BY_ID,array($exchange_id,array('exchange_status'=>ProductExchangeModel::ALLOC_TASK)));
            }

            $map = array('id'=>$tp_id);
            $result = apiCall(TaskPlanApi::SET_DEC,array($map,'yuecount',1));

            if($result['status']){
                return array('status'=>true,'info'=>$result['info']);
            }else{
                return array('status'=>false,'info'=>"领取任务失败");
            }

        }
    }


    /**
     * 判断领取的任务是否为该用户在10天内领取过的
     * @param  $task_id | 任务ID
     * @param  $uid | 领取人ID
     * @param $seller_uid
     * @return bool
     */
    public function isLegalTask($uid,$seller_uid){
        $order = " get_task_time desc";
        $map = array(
            'seller_uid'=>$seller_uid,
            'uid'=>$uid,
            'do_status'=>array('not in',array(TaskHisModel::DO_STATUS_CANCEL)),
        );

        $result = apiCall(TaskHisApi::GET_INFO,array($map,$order));

        if($result['status'] && is_array($result['info'])){
            $task_his = $result['info'];
            $min_time = time() - TaskLogic::TASK_DO_INTERVAL * 24 * 3600;
            if($task_his['get_task_time'] > $min_time){
                return false;
            }

        }

        return true;

    }



}