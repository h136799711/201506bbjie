<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 16/3/11
 * Time: 15:11
 */

namespace Home\Controller;


use Home\Api\BbjmemberSellerApi;
use Home\Api\FinAccountBalanceHisApi;
use Home\Api\TaskApi;
use Home\Api\TaskPlanApi;
use Home\Model\FinAccountBalanceHisModel;

class TaskPlanController extends SjController {

    /**
     * 删除任务计划
     */
    public function delete(){
        $plan_id = I('get.id',0);

        $result = apiCall(TaskPlanApi::GET_INFO,array(array('id'=>$plan_id)));
        if(!$result['status'] || is_null($result['info'])){
            $this->error("删除失败，任务计划信息获取失败!");
        }

        $count = $result['info']['task_cnt'];
        $task_id = $result['info']['task_id'];

        $result = apiCall(TaskApi::GET_INFO,array(array('id'=>$task_id)));
        if(!$result['status'] || is_null($result['info'])){
            $this->error("删除失败,任务信息获取失败!");
        }

        $income = $result['info']['task_gold'];

        //欲解冻的资金
//        $income = $count * $income;
        $income = $result['info']['frozen_money'];

        if(!empty($plan_id)){
            $result = apiCall(TaskPlanApi::DELETE,array(array('id'=>$plan_id)));
            if($result['status']){

                $entity = array(
                    'uid' => $this->uid,
                    'defray' =>  0,
                    'income' => $income,
                    'create_time' => time(),
                    'notes' => '用户删除了任务计划',
                    'dtree_type' => FinAccountBalanceHisModel::TYPE_UNFREEZE_DELETE_TASK_PLAN,
                    'status' => 1,
                );

                $result = apiCall(FinAccountBalanceHisApi::ADD, array($entity));
                if ($result['status']) {

                    apiCall(BbjmemberSellerApi::SET_INC, array(array('uid' => $this->uid), "coins", $income));
                    apiCall(BbjmemberSellerApi::SET_DESC, array(array('uid' => $this->uid), "frozen_money", $income));
                    apiCall(TaskApi::SET_DESC,array(array('id'=>$task_id),"frozen_money",$income));

                    $this->success('删除成功！');
                }
            }
        }

        $this->error("删除失败!");

    }

}