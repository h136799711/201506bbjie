<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Cms\Api;
use \Common\Api\Api;
use \Common\Model\DatatreeModel;

class DatatreeApi extends Api{
	protected function _init(){
		$this->model = new DatatreeModel();
	}
}

