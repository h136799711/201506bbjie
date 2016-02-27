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

class TaskHisApi extends Api{


    /**
     * 统计
     */
    const COUNT = "Home/TaskHis/count";
    /**
     * 新增
     */
    const ADD = "Home/TaskHis/add";
    /**
     * 删除
     */
    const DELETE = "Home/TaskHis/delete";
    /**
     * 不分页查询
     */
    const QUERY_NO_PAGING = "Home/TaskHis/queryNoPaging";
    /**
     * 分页查询
     */
    const QUERY = "Home/TaskHis/query";

    /**
     * 获取用户信息
     */
    const GET_INFO = "Home/TaskHis/getInfo";

    /**
     * 更新
     */
    const SAVE_BY_ID = "Home/TaskHis/saveByID";

	protected function _init(){
		$this->model = new TaskHisModel();
	}
}

