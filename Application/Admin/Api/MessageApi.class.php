<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------

namespace Admin\Api;

use Common\Api\Api;
use Admin\Model\MessageModel;

class MessageApi extends Api{

    /**
     * 添加
     */
    const ADD = "Admin/Message/add";
    /**
     * 保存
     */
    const SAVE = "Admin/Message/save";
    /**
     * 保存根据ID主键
     */
    const SAVE_BY_ID = "Admin/Message/saveByID";

    /**
     * 删除
     */
    const DELETE = "Admin/Message/delete";

    /**
     * 查询
     */
    const QUERY = "Admin/Message/query";
    /**
     * 查询一条数据
     */
    const GET_INFO = "Admin/Message/getInfo";
    /**
     * 查询数据
     */
    const QUERY_NO_PAGING = "Admin/Message/queryNoPaging";

	protected function _init(){
		$this->model = new MessageModel();
	}
	
}
