<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 16/6/20
 * Time: 10:09
 */

namespace Money\Logic;


use Home\Api\DistributorCfgApi;
use Home\Api\FinAccountBalanceHisApi;
use Home\Model\FinAccountBalanceHisModel;

class DistributorMoney {

    /**
     * 分配给推荐人一部分佣金
     * @param $uid
     * @param $brokerage
     */
    public function  allocDistributeMoney($uid,$brokerage){

        $map = array(
            'uid'=>$uid,
        );

        $result  = apiCall(DistributorCfgApi::GET_INFO,array($map));
        $distrbutor_info = $result['info'];

        if($result['status'] && is_array($distrbutor_info)){
            //
            $rebate = floatval($distrbutor_info['rebate']);
//            $money = $distrbutor_info['money'];
//            $frozen_money = $distrbutor_info['frozen_money'];
            $addedMoney = $brokerage * $rebate;
            $this->addMoney($uid,$addedMoney,$distrbutor_info);
        }
    }


    public function addMoney($uid,$addedMoney,$distrbutor_info){
        $map = array(
            'uid'=>$uid,
        );

        $result = apiCall(DistributorCfgApi::SET_INC,array($map,"money",$addedMoney));

        if($result['status']){
            $this->log($uid,0,$addedMoney,$distrbutor_info);
        }else{
            LogRecord("返佣时发生错误addMoney,info=".$result['info'],__FILE__.__LINE__,LOG_EMERG);
        }

    }

    private function log($uid,$defray,$income,$distrbutor_info){
        $notes = "获得分成".$income."元!";

        $entity = array(
            'uid'=>$uid,
            'defray'=>$defray,
            'income'=>$income,
            'left_money'=>$distrbutor_info['money']+$defray,
            'frozen_money'=>0,
            'create_time'=>time(),
            'notes'=>$notes,
            'dtree_type'=>FinAccountBalanceHisModel::TYPE_DISTRIBUTOR_GET,
            'imgurl'=>'',
            'status'=>FinAccountBalanceHisModel::STATUS_PASSED,
            'extra'=>$distrbutor_info['uid'],

        );

        $result = apiCall(FinAccountBalanceHisApi::ADD,array($entity));

        if(!$result['status']){
            LogRecord("返佣时发生错误log,info=".$result['info'],__FILE__.__LINE__,LOG_EMERG);
        }


    }
}