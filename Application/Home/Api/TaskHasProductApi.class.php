<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Home\Api;
use \Common\Api\Api;
use \Home\Model\TaskHasProductModel;

class TaskHasProductApi extends Api{


    /**
     * 统计
     */
    const COUNT = "Home/TaskHasProduct/count";
    /**
     * 新增
     */
    const ADD = "Home/TaskHasProduct/add";
    /**
     * 删除
     */
    const DELETE = "Home/TaskHasProduct/delete";
    /**
     * 不分页查询
     */
    const QUERY_NO_PAGING = "Home/TaskHasProduct/queryNoPaging";
    /**
     * 分页查询
     */
    const QUERY = "Home/TaskHasProduct/query";

    /**
     * 获取用户信息
     */
    const GET_INFO = "Home/TaskHasProduct/getInfo";

    /**
     * 更新
     */
    const SAVE_BY_ID = "Home/TaskHasProduct/saveByID";

	protected function _init(){
		$this->model = new TaskHasProductModel();
	}

}

