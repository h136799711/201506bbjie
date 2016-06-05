<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 16/3/22
 * Time: 14:00
 */

namespace Home\Logic;


use Admin\Model\MsgboxModel;
use Home\Api\BbjmemberSellerApi;
use Home\Api\ProductExchangeApi;
use Home\Api\TaskApi;
use Home\Api\TaskHisApi;
use Home\Api\VMsgInfoApi;
use Home\Api\VProductExchangeInfoApi;
use Home\Api\VTaskHisInfoApi;
use Home\Model\ProductExchangeModel;
use Home\Model\TaskHisModel;
use Home\Model\TaskLogModel;
use Money\Logic\WalletLogic;
use Uclient\Api\UserApi;

class TaskHelperLogic {

    /**
     * 发放任务
     * @param $uid
     * @param $task_id
     * @return array
     */
//    public function giveTaskFromTo($seller_uid,$uid,$task_id,$tp_id=0){
//        //判断商家是否有足够金额，
//
////        $result = apiCall(BbjmemberSellerApi::GET_INFO,array(array('uid'=>$seller_uid));
////
////        if(!$result['status']){
////            return array('status'=>false,'info'=>$result['info']);
////        }
////        $bbjmember = $result['info'];
//
//
//
//        //1. 扣除金额 , 增加冻结金额 ,
//        //2. 创建task_his
//        //3.
//
//        $map  = array(
//            'id'=>$task_id,
//        );
//        $result = apiCall(TaskApi::GET_INFO,array($map));
//
//        if(!$result['status']){
//            return array('status'=>false,'info'=>$result['info']);
//        }
//        $task = $result['info'];
//        //单份任务保证金
//        $task_gold =  $task['task_gold'];
//
//        $logic = new WalletLogic();
//        $result = $logic->addFrozenFromCoins($seller_uid,$task_gold);
//        if(!$result['status']){
//            return $result;
//        }
//
//        //获取用户名
//        $result = apiCall(UserApi::GET_INFO,array($uid));
//
//        if(!$result['status']){
//            return array('status'=>false,'info'=>$result['info']);
//        }
//
//        $user = $result['info'];
//
//        $task_brokerage = $task['task_brokerage'];
//        $now_time = time();
//        $task_his = array(
//            'express_code'=>'',
//            'express_name'=>'',
//            'express_no'=>'',
//            'express_pid'=>0,
//            'exchange_id'=>0,//兑换的商品ID
//            'express_price'=>0,//快递费用
//            'express_pname'=>'',//兑换的商品名称
//            'tpid'=>$tp_id,//任务计划ID
//            'uid'=>$uid,
//            'order_status'=>2,
//            'create_time'=>$now_time,
//            'get_task_time'=>$now_time,
//            'do_status'=>TaskHisModel::DO_STATUS_NOT_START,
//            'task_id'=>$task_id,
//            'task_brokerage'=>$task_brokerage,
//            'tb_orderid'=>'',
//            'tb_address'=>'',
//            'tb_price'=>0,
//            'return_money'=>0,
//            'tb_pay_type'=>TaskHisModel::PAY_TYPE_LEGAL,
//            'notes'=>'',
//            'tb_seller_notes'=>'',
//            'tb_account'=>'',
//            'task_from'=>TaskHisModel::COME_FROM_SELLER_GIVEN,
//        );
//
//        //获取兑换商品信息
//        $exchange_map = array('exchange_status'=>ProductExchangeModel::CHECK_SUCCESS,'uid'=>$this->uid);
//        $result = apiCall(VProductExchangeInfoApi::GET_INFO,array($exchange_map,"update_time desc"));
//
//        $exchange_id = 0;
//        if($result['status'] && is_array($result['info'])){
//            $product_info = $result['info'];
//            $task_his['exchange_id'] = $product_info['id'];
//            $task_his['express_pid'] = $product_info['p_id'];
//            $task_his['express_name'] = $product_info['name'];
//            $exchange_id = $product_info['id'];
//        }
//
//        $result = apiCall(TaskHisApi::ADD,array($task_his));
//
//        if($result['status']) {
//
//            //添加冻结资金
//            $result = apiCall(TaskApi::SET_INC,array($task_id,"frozen_money",$task_gold));
//
//            if ($exchange_id > 0) {
//                apiCall(ProductExchangeApi::SAVE_BY_ID, array($exchange_id, array('exchange_status' => ProductExchangeModel::ALLOC_TASK)));
//            }
//
//            $notes = "商家分配了任务给用户 (" . $user['username'] . ")";
//            task_log($result['info'], 0, $uid, $task_id, TaskLogModel::TYPE_GET_TASK, $notes);
//
//            return array('status'=>true,'info'=>'分配成功');
//        }
//    }



    /**
     * 获取试民正在进行的任务数
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     * @param $uid int 用户ID
     * @return int
     */
    public function get_doing_task_cnt($uid){

        $map = array('uid'=>$uid);
        $map['do_status'] = array('not in',array(TaskHisModel::DO_STATUS_SUSPEND,TaskHisModel::DO_STATUS_CANCEL,TaskHisModel::DO_STATUS_RETURNED_MONEY));
        $result = apiCall(TaskHisApi::COUNT,array($map));
        if($result['status']){
            return $result['info'];
        }else{
            return 0;
        }
    }

    /**
     * 未读消息
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     * @param $uid int 用户UID
     * @return int
     */
    public function not_read_msg_cnt($uid){

        $result = apiCall(VMsgInfoApi::COUNT,array(array('to_id'=>$uid,'msg_status'=>MsgboxModel::NOT_READ)));
        $not_read_msg_cnt =  $result['info'];
        if(empty($not_read_msg_cnt)){
            $not_read_msg_cnt = 0;
        }

        return $not_read_msg_cnt;
    }

    /**
     * 统计指定商家的任务各状态的数量
     * @param $uid int 用户ID
     * @return array
     *  submit=>'待审核订单、已提交订单'
     *  platform=>'等待发货'
     *  wait_receive=>'待收货'
     *  wait_ret_money=>'等待还款'
     *  not_start=>'未开始'
     *  reject=>'已驳回'
     */
    public function countStatusCnt($uid){

        $ret = array(
            'error'=>'',
        );

        $api = new VTaskHisInfoApi();
        $map = array('seller_uid'=>$uid);
        //1. 已提交订单
        $map['do_status'] = TaskHisModel::DO_STATUS_SUBMIT_ORDER;
        $result = $api->count($map);
        if($result['status']){
            $ret['submit'] = $result['info'];
        }else{
            $ret['submit'] = 0;
            $ret['error'] .= $result['info'];
        }
        //2. 已审核通过订单
        $map['do_status'] = TaskHisModel::DO_STATUS_PASS;
        $result = $api->count($map);
        if($result['status']){
            $ret['platform'] = $result['info'];
        }else{
            $ret['platform'] = 0;
            $ret['error'] .= $result['info'];
        }
        //3. 已发货
        $map['do_status'] = TaskHisModel::DO_STATUS_DELIVERY_GOODS;
        $result = $api->count($map);
        if($result['status']){
            $ret['wait_receive'] = $result['info'];
        }else{
            $ret['wait_receive'] = 0;
            $ret['error'] .= $result['info'];
        }
        //4.  等待还款
        $map['do_status'] = TaskHisModel::DO_STATUS_RECEIVED_GOODS;
        $result = $api->count($map);
        if($result['status']){
            $ret['wait_ret_money'] = $result['info'];
        }else{
            $ret['wait_ret_money'] = 0;
            $ret['error'] .= $result['info'];
        }
        //5.  未开始
        $map['do_status'] = TaskHisModel::DO_STATUS_NOT_START;
        $result = $api->count($map);
        if($result['status']){
            $ret['not_start'] = $result['info'];
        }else{
            $ret['not_start'] = 0;
            $ret['error'] .= $result['info'];
        }
        //6.  已驳回
        $map['do_status'] = TaskHisModel::DO_STATUS_REJECT;
        $result = $api->count($map);
        if($result['status']){
            $ret['reject'] = $result['info'];
        }else{
            $ret['reject'] = 0;
            $ret['error'] .= $result['info'];
        }

        return $ret;
    }

}