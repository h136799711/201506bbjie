<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Home\Api;
use \Common\Api\Api;
use \Home\Model\TaskHisModel;
use Home\Model\TaskLogModel;

class TaskLogApi extends Api{

    /**
     * 统计
     */
    const COUNT = "Home/TaskLog/count";
    /**
     * 新增
     */
    const ADD = "Home/TaskLog/add";
    /**
     * 删除
     */
    const DELETE = "Home/TaskLog/delete";
    /**
     * 不分页查询
     */
    const QUERY_NO_PAGING = "Home/TaskLog/queryNoPaging";
    /**
     * 分页查询
     */
    const QUERY = "Home/TaskLog/query";

    /**
     * 获取用户信息
     */
    const GET_INFO = "Home/TaskLog/getInfo";

    /**
     * 更新
     */
    const SAVE_BY_ID = "Home/TaskLog/saveByID";

	protected function _init(){
		$this->model = new TaskLogModel();
	}
}

