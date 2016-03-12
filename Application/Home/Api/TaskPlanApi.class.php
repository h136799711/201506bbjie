<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Home\Api;
use \Common\Api\Api;
use \Home\Model\TaskPlanModel;

class TaskPlanApi extends Api{

    /**
     * 增
     */
    const SET_INC = "Home/TaskPlan/setInc";
    /**
     * 减
     */
    const SET_DEC = "Home/TaskPlan/setDec";
    /**
     * 求和
     */
    const SUM = "Home/TaskPlan/sum";
    /**
     * 统计
     */
    const COUNT = "Home/TaskPlan/count";
    /**
     * 新增
     */
    const ADD = "Home/TaskPlan/add";
    /**
     * 删除
     */
    const DELETE = "Home/TaskPlan/delete";
    /**
     * 不分页查询
     */
    const QUERY_NO_PAGING = "Home/TaskPlan/queryNoPaging";
    /**
     * 分页查询
     */
    const QUERY = "Home/TaskPlan/query";

    /**
     * 获取一条信息
     */
    const GET_INFO = "Home/TaskPlan/getInfo";


    /**
     * 更新
     */
    const SAVE_BY_ID = "Home/TaskPlan/saveByID";

	protected function _init(){
		$this->model = new TaskPlanModel();
	}

}

