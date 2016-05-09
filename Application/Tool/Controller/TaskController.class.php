<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------

namespace Tool\Controller;
use Admin\Api\AuthRuleApi;
use Home\Api\BbjmemberApi;
use Home\Api\BbjmemberSellerApi;
use Home\Api\TaskApi;
use Home\Api\TaskHisApi;
use Home\Api\TaskLogApi;
use Home\Api\TaskPlanApi;
use Home\Model\TaskHisModel;
use Home\Model\TaskLogModel;
use Think\Controller;

/**
 * 任务运行
 */
class TaskController extends Controller{
	
	protected function _initialize(){
//		$key = I('get.key','');

		//20分钟以内的请求只处理一次
//		$prev_pro_time = S('TASK_PROCESS_TIME');
//		if($prev_pro_time === false){
//			S('TASK_PROCESS_TIME',time(),20*60);
//		}else{
//			echo "Cached-Time: ". date("Y-m-d H:i:s",$prev_pro_time);
//			//缓冲处理
//			exit();
//		}
		
	}
	
	/**
	 * 任务自动处理\异步
	 */
	public function index(){
		header('Content-type',"text/html");
		$url = C('SITE_URL').'/index.php/Tool/Task/aysnc';
        $post_data = array('from'=>'index');
		$result = fsockopenRequest($url,$post_data);
        echo "var url = \"".$url."\";var result = ".$result.";if(typeof(aysncTask) == 'undefined'){console.log('accept request!'+result);console.log(url);}else{";
		echo "aysncTask('Accept Request!')}";
	}
	
	/**
	 * 任务处理区域
	 */
	public function aysnc(){
		
		ignore_user_abort(true); // 后台运行
		set_time_limit(0); // 取消脚本运行时间的超时上限
        LogRecord("执行任务",__FILE__.__LINE__);

//		$this->toRecieved();
//		$this->toCompleted();
//		$this->toCancel();
        $this->cancelTaskHis();
        $this->autoConfirmReturnMoney();
	}

    /**
     * 1天内未确认还款则自动确认还款
     */
    private function autoConfirmReturnMoney(){
//        $interval = 24*3600;
        $interval = 5*60;
        $limit = 20;
        $result = apiCall(TaskHisApi::GET_NEED_RETURN_MONEY,array($interval,$limit));
        if(!$result['status']){
            LogRecord($result['info'], __FILE__.'LINE:'.__LINE__);
        }else{

            $list = $result['info'];
            $now_time = time();
            foreach($list as $vo){
                $notes = "超过".($interval/3600).'小时，系统自动确认还款';
                $entity = array(
                    'task_id'=>$vo['task_id'],
                    'plan_id'=>$vo['tpid'],
                    'uid'=>$vo['uid'],
                    'task_his_id'=>$vo['id'],
                    'dtree_type'=>TaskLogModel::TYPE_TASK_OVER,
                    'notes'=>$notes,
                    'log_time'=>$now_time,
                );
                $result = apiCall(TaskLogApi::ADD,array($entity));
                //任务更改为取消状态
                $result = apiCall(TaskHisApi::SAVE_BY_ID,array($vo['id'],array('do_status'=>TaskHisModel::DO_STATUS_RETURNED_MONEY)));
                $this->task_over($vo);

            }

        }
    }


    /**
     * 任务结束归还
     * 1. 增加用户虚拟币
     * 2. 增加用户余额
     * 3. 扣除商家冻结资金
     */
    private function task_over($his){

        $tb_price = $his['tb_price'];

        $map = array('uid'=>$his['uid']);

        $result = apiCall(BbjmemberApi::GET_INFO,array($map));
        $bbj_member = array();
        if($result['status']){
            $bbj_member = $result['info'];
        }

        ifFailedLogRecord($result,__FILE__.__LINE__);

        $result = apiCall(TaskApi::GET_INFO,array(array('id'=>$his['task_id'])));
        ifFailedLogRecord($result,__FILE__.__LINE__);

        $task = false;
        if($result['status']){
            $task = $result['info'];
        }

        if(empty($task)){
            LogRecord("完成任务归还时，获取任务信息失败!",__FILE__.__LINE__);
            return;
        }

        $uid = $his['uid'];
        $notes = "商家确认还款，退还资金!";
        //1. 增加用户余额
        $result = apiCall(BbjmemberApi::ADD_MONEY,array($uid,$tb_price,$notes));

        if(!$result['status']){
            $this->error("操作失败!");
        }

        //2. 增加用户虚拟币
        $left_coin = $bbj_member['fucoin']+$task['coin'];
        $notes = "您完成了任务#".$his['id']."#,获得了 ".$task['coin'].VIRTUAL_CURRENCY;
        $result = apiCall(BbjmemberApi::ADD_FU_COINS,array($uid,$task['coin'],$notes,$left_coin));

        LogRecord(json_encode($result),__FILE__.__LINE__);

        //3. 扣除商家冻结资金
        $notes = "用户完成了任务#".$his['id']."#,还款给用户#".$uid."#,".$tb_price;
        $result = apiCall(BbjmemberSellerApi::MINUS_FROZEN_MONEY,array($task['uid'],$tb_price,$notes));
        LogRecord(json_encode($result),__FILE__.__LINE__);


        //4. 增加用户经验值
        $exp = $task['task_gold']*0.1;
//        +100经验，最低+20经验。
        if($exp < 20){
            $exp = 20;
        }
        if($exp > 100){
            $exp = 100;
        }
        $result = apiCall(BbjmemberApi::SET_INC,array(array('uid'=>$uid),'exp',$exp ));

        LogRecord(json_encode($result),__FILE__.__LINE__);

    }


    /**
     * 1天内未提交订单信息的话
     * 自动取消任务
     */
    private function cancelTaskHis(){
        $interval = 24*3600;
        $limit = 20;

        $result = apiCall(TaskHisApi::GET_NEED_CANCEL_TASK_HIS,array($interval,$limit));

        if(!$result['status']){
            LogRecord($result['info'], __FILE__.__LINE__);
        }else{

            $list = $result['info'];
            $now_time = time();
            foreach($list as $vo){
                $notes = "超过".($interval/3600).'小时，系统自动取消任务';
                $entity = array(
                    'task_id'=>$vo['task_id'],
                    'plan_id'=>$vo['tpid'],
                    'uid'=>$vo['uid'],
                    'task_his_id'=>$vo['id'],
                    'dtree_type'=>TaskLogModel::TYPE_CANCEL_TASK,
                    'notes'=>$notes,
                    'log_time'=>$now_time,
                );

                $result = apiCall(TaskLogApi::ADD,array($entity));
                //任务更改为取消状态123456
                $result = apiCall(TaskHisApi::SAVE_BY_ID,array($vo['id'],array('do_status'=>TaskHisModel::DO_STATUS_CANCEL)));

                $map = array('id'=>$vo['tpid']);
                //任务计划剩余数量+1
                $result = apiCall(TaskPlanApi::SET_INC,array($map,"yuecount",1));
                //任务取消数量+1
                $result = apiCall(BbjmemberApi::SET_INC,array($map,'cancel_task_cnt',1));
            }

        }
    }

	
	
	
}
