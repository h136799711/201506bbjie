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
function msubstr($str){
    $new_str = mb_substr($str,0,3);
    return $new_str;
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
