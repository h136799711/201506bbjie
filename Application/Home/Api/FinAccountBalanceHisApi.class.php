<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Home\Api;
use \Common\Api\Api;
use \Home\Model\FinAccountBalanceHisModel;

class FinAccountBalanceHisApi extends Api{
    /**
     * 新增
     */
    const ADD = "Home/FinAccountBalanceHis/add";
    /**
     * 删除
     */
    const DELETE = "Home/FinAccountBalanceHis/delete";
    /**
     * 不分页查询
     */
    const QUERY_NO_PAGING = "Home/FinAccountBalanceHis/queryNoPaging";
    /**
     * 分页查询
     */
    const QUERY = "Home/FinAccountBalanceHis/query";

    /**
     * 获取用户信息
     */
    const GET_INFO = "Home/FinAccountBalanceHis/getInfo";

    /**
     * 保存用户信息
     */
    const SAVE_BY_ID = "Home/FinAccountBalanceHis/saveByID";

	protected function _init(){
		$this->model = new FinAccountBalanceHisModel();
	}
}

