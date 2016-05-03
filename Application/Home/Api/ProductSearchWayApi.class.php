<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Home\Api;
use \Common\Api\Api;
use \Common\Model\ProductSearchWayModel;

class ProductSearchWayApi extends Api{

    const QUERY = 'Home/ProductSearchWay/query';
    const QUERY_NO_PAGING = 'Home/ProductSearchWay/queryNoPaging';
    const SAVE_BY_ID = 'Home/ProductSearchWay/saveByID';
    const SAVE = 'Home/ProductSearchWay/save';
    const ADD = 'Home/ProductSearchWay/add';
    const DELETE = 'Home/ProductSearchWay/delete';
    const GET_INFO = 'Home/ProductSearchWay/getInfo';

	protected function _init(){
		$this->model = new ProductSearchWayModel();
	}
}