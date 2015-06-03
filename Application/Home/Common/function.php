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



