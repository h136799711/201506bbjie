<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Home\Api;
use \Common\Api\Api;
use Home\Model\ProductExchangeModel;

class ProductExchangeApi extends Api{
    /**
     * 统计
     */
    const COUNT = "Home/ProductExchange/count";

    /**
     * 新增
     */
    const ADD = "Home/ProductExchange/add";

    /**
     * 删除
     */
    const DELETE = "Home/ProductExchange/delete";
    /**
     * 不分页查询
     */
    const QUERY_NO_PAGING = "Home/ProductExchange/queryNoPaging";
    /**
     * 分页查询
     */
    const QUERY = "Home/ProductExchange/query";

    /**
     * 获取信息
     */
    const GET_INFO = "Home/ProductExchange/getInfo";

    /**
     * 保存用户信息
     */
    const SAVE_BY_ID = "Home/ProductExchange/saveByID";

	protected function _init(){
		$this->model = new ProductExchangeModel();
	}

}

