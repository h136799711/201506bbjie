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
use Home\Model\VCanDoTaskWithAutoIdModel;
use Home\Model\VMsgInfoModel;
use Home\Model\VTaskHisInfoModel;

class VCanDoTaskWithAutoIdApi extends Api{
    /**
    * 统计
    */
    const COUNT = "Home/VCanDoTaskWithAutoId/count";
    /**
     * 新增
     */
    const ADD = "Home/VCanDoTaskWithAutoId/add";
    /**
     * 删除
     */
    const DELETE = "Home/VCanDoTaskWithAutoId/delete";
    /**
     * 不分页查询
     */
    const QUERY_NO_PAGING = "Home/VCanDoTaskWithAutoId/queryNoPaging";
    /**
     * 分页查询
     */
    const QUERY = "Home/VCanDoTaskWithAutoId/query";

    /**
     * 获取用户信息
     */
    const GET_INFO = "Home/VCanDoTaskWithAutoId/getInfo";

    /**
     * 更新
     */
    const SAVE_BY_ID = "Home/VCanDoTaskWithAutoId/saveByID";

	protected function _init(){
		$this->model = new VCanDoTaskWithAutoIdModel();
	}

}

