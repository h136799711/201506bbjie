<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Home\Api;

use \Common\Api\Api;
use Home\Model\VDoTaskUserModel;
use Home\Model\VTaskHisInfoModel;

class VDoTaskUserApi extends Api{

    /**
     * 统计
     */
    const SUM = "Home/VDoTaskUser/sum";
    /**
     * 新增
     */
    const ADD = "Home/VDoTaskUser/add";
    /**
     * 删除
     */
    const DELETE = "Home/VDoTaskUser/delete";
    /**
     * 不分页查询
     */
    const QUERY_NO_PAGING = "Home/VDoTaskUser/queryNoPaging";
    /**
     * 分页查询
     */
    const QUERY = "Home/VDoTaskUser/query";

    /**
     * 获取用户信息
     */
    const GET_INFO = "Home/VDoTaskUser/getInfo";

    /**
     * 更新
     */
    const SAVE_BY_ID = "Home/VDoTaskUser/saveByID";

	protected function _init(){
		$this->model = new VDoTaskUserModel();
	}

}

