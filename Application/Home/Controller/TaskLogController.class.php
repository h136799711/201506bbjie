<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 16/3/11
 * Time: 17:35
 */

namespace Home\Controller;


use Home\Api\BbjmemberSellerApi;
use Home\Api\TaskLogApi;
use Home\Api\VTaskHisInfoApi;
use Home\Api\VTaskProductInfoApi;
use Uclient\Api\UserApi;

class TaskLogController extends HomeController {

    /**
     * 任务日志查看
     */
    public function view(){
        $task_his_id = I('get.task_his_id',0);

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