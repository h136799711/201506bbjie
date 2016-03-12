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
    /**
     * 获取需要系统自动取消的任务
     */
    const GET_NEED_CANCEL_TASK_HIS = "Home/TaskHis/getNeedCancelTaskHis";

	protected function _init(){
		$this->model = new TaskHisModel();
	}

    /**
     * 获取需要系统自动取消的任务
     * @param $interval
     * @param $limit
     * @return array
     */
    public function getNeedCancelTaskHis($interval,$limit){

        $map = array();
        $map['do_status'] = TaskHisModel::DO_STATUS_NOT_START;
        $map['get_task_time'] = array('lt',time()-$interval);
        $result = $this -> model -> where($map)->limit(0,$limit)->select();
        if($result === false){
            return $this->apiReturnErr($this->model->getDbError());
        }
        return $this->apiReturnSuc($result);

    }

}

