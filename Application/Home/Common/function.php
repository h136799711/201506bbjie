<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016 杭州博也网络科技, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------

/**
 * 是否使用移动设备访问
 * @return true:是 false:否
 */
function isMobile(){
	
	vendor("MobileDetect.Mobile_Detect");
	$mobileDetect = new \Mobile_Detect();
	return $mobileDetect->isMobile();
}

function mask_string($str){

    $new_str = mb_substr($str,0,5);

    $new_str .= '***'.mb_substr($str,mb_strlen($str)-5,5);

    return $new_str;
}

/**
 * 任务操作日志
 * @param $task_his_id
 * @param $plan_id
 * @param $uid
 * @param $task_id
 * @param $type
 * @param $notes
 * @return bool
 */
function task_log($task_his_id,$plan_id,$uid,$task_id,$type,$notes){
    $entity = array(
        'plan_id'=>$plan_id,
        'uid'=>$uid,
        'task_id'=>$task_id,
        'dtree_type'=>$type,
        'notes'=>$notes,
        'task_his_id'=>$task_his_id,
        'log_time'=>time(),
    );
    $api = new \Home\Api\TaskLogApi();

    return $api->add($entity);
}

function getProductUpdateTime($time){

    $now_time = time();

    $elapse = $now_time - $time;
    if($elapse < 0){
        return "时间错误";
    }

    if( ($elapse / (24*3600)) > 30.0){
        return "很久以前";
    }

    return date("Y-m-d H:i",$time);

}


/**
 * 获取发货模式对应描述
 * @param $mode
 * @return string
 */
function getDeliveryMode($mode){
    $desc = "";
    switch($mode){
        case 1:
            $desc = "平台发货";
            break;
        case 2:
            $desc = "自主发货";
            break;
        default:
            $desc = "未知";
            break;
    }

    return $desc;

}