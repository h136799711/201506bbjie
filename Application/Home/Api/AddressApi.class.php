<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Home\Api;
use \Common\Api\Api;
use \Home\Model\AddressModel;

class AddressApi extends Api{

    /**
     * 新增
     */
    const ADD = "Home/Address/add";
    /**
     * 删除
     */
    const DELETE = "Home/Address/delete";
    /**
     * 不分页查询
     */
    const QUERY_NO_PAGING = "Home/Address/queryNoPaging";
    /**
     * 分页查询
     */
    const QUERY = "Home/Address/query";

    /**
     * 获取一条信息
     */
    const GET_INFO = "Home/Address/getInfo";


    /**
     * 更新
     */
    const SAVE_BY_ID = "Home/Address/saveByID";


	protected function _init(){
		$this->model = new AddressModel();
	}

}

