<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Home\Api;

use \Common\Api\Api;
use Home\Model\VMsgInfoModel;
use Home\Model\VTaskHisInfoModel;

class VMsgInfoApi extends Api{
    /**
    * 统计
    */
    const COUNT = "Home/VMsgInfo/count";
    /**
     * 新增
     */
    const ADD = "Home/VMsgInfo/add";
    /**
     * 删除
     */
    const DELETE = "Home/VMsgInfo/delete";
    /**
     * 不分页查询
     */
    const QUERY_NO_PAGING = "Home/VMsgInfo/queryNoPaging";
    /**
     * 分页查询
     */
    const QUERY = "Home/VMsgInfo/query";

    /**
     * 获取用户信息
     */
    const GET_INFO = "Home/VMsgInfo/getInfo";

    /**
     * 更新
     */
    const SAVE_BY_ID = "Home/VMsgInfo/saveByID";

	protected function _init(){
		$this->model = new VMsgInfoModel();
	}

}

