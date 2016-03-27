<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 16/3/22
 * Time: 14:00
 */

namespace Home\Logic;


use Admin\Model\MsgboxModel;
use Home\Api\TaskHisApi;
use Home\Api\VMsgInfoApi;
use Home\Api\VTaskHisInfoApi;
use Home\Model\TaskHisModel;

class TaskHelperLogic {
    /**
     * 获取试民正在进行的任务数
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     * @param $uid int 用户ID
     * @return int
     */
    public function get_doing_task_cnt($uid){

        $map = array('uid'=>$uid);
        $map['do_status'] = array('not in',array(TaskHisModel::DO_STATUS_CANCEL,TaskHisModel::DO_STATUS_DONE,TaskHisModel::DO_STATUS_RETURNED_MONEY));
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