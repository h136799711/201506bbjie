<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2015, http://www.gooraye.net. All Rights Reserved.
// |-----------------------------------------------------------------------------------

namespace Admin\Api;

use Admin\Model\VMemberModel;
use Common\Api\Api;

class VMemberApi extends Api{

    /**
     * 查询
     */
    const QUERY = "Admin/VMember/query";
    /**
     * 查询一条数据
     */
    const GET_INFO = "Admin/VMember/getInfo";

	//初始化
	protected function _init(){
		$this->model = new VMemberModel();
	}



}
