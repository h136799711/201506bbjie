<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Home\Api;

use \Common\Api\Api;
use Home\Model\VTaskHisInfoModel;

class VTaskHisInfoApi extends Api{

    /**
     * 统计
     */
    const SUM = "Home/VTaskHisInfo/sum";
    /**
     * 新增
     */
    const ADD = "Home/VTaskHisInfo/add";
    /**
     * 删除
     */
    const DELETE = "Home/VTaskHisInfo/delete";
    /**
     * 不分页查询
     */
    const QUERY_NO_PAGING = "Home/VTaskHisInfo/queryNoPaging";
    /**
     * 分页查询
     */
    const QUERY = "Home/VTaskHisInfo/query";

    /**
     * 获取用户信息
     */
    const GET_INFO = "Home/VTaskHisInfo/getInfo";

    /**
     * 更新
     */
    const SAVE_BY_ID = "Home/VTaskHisInfo/saveByID";

	protected function _init(){
		$this->model = new VTaskHisInfoModel();
	}

}
