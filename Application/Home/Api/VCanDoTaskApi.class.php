<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Home\Api;

use \Common\Api\Api;
use Home\Model\VCanDoTaskModel;
use Home\Model\VMsgInfoModel;
use Home\Model\VTaskHisInfoModel;

class VCanDoTaskApi extends Api{
    /**
    * 统计
    */
    const COUNT = "Home/VCanDoTask/count";
    /**
     * 新增
     */
    const ADD = "Home/VCanDoTask/add";
    /**
     * 删除
     */
    const DELETE = "Home/VCanDoTask/delete";
    /**
     * 不分页查询
     */
    const QUERY_NO_PAGING = "Home/VCanDoTask/queryNoPaging";
    /**
     * 分页查询
     */
    const QUERY = "Home/VCanDoTask/query";

    /**
     * 获取用户信息
     */
    const GET_INFO = "Home/VCanDoTask/getInfo";

    /**
     * 更新
     */
    const SAVE_BY_ID = "Home/VCanDoTask/saveByID";

    /**
     * 统计
     */
    const COUNT_CAN_DO_TASK_BY_SELLER = "Home/VCanDoTask/countCanDoTaskBySeller";

	protected function _init(){
		$this->model = new VCanDoTaskModel();
	}

    /**
     * 统计可以做任务的商家数量
     * @return array
     */
    public function countCanDoTaskBySeller(){

        $result = $this->model->distinct(true)->field('uid')->select();
        if($result === false){
            return $this->apiReturnErr($this->model->getDbError());
        }
        return $this->apiReturnSuc($result);
    }

}

