<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2015, http://www.gooraye.net. All Rights Reserved.
// |-----------------------------------------------------------------------------------

namespace Home\Api;

use Common\Api\Api;
use Home\Model\LevelAuthModel;

/**
 * LevelAuthApi 卖家用户等级权限
 */
class LevelAuthApi extends Api{


    /**
     * 新增
     */
    const ADD = "Home/LevelAuth/add";
    /**
     * 删除
     */
    const DELETE = "Home/LevelAuth/delete";
    /**
     * 不分页查询
     */
    const QUERY_NO_PAGING = "Home/LevelAuth/queryNoPaging";
    /**
     * 分页查询
     */
    const QUERY = "Home/LevelAuth/query";

    /**
     * 获取用户信息
     */
    const GET_INFO = "Home/LevelAuth/getInfo";

    /**
     * 保存用户信息
     */
    const SAVE_BY_ID = "Home/LevelAuth/saveByID";

    public function _init(){
        $this->model = new LevelAuthModel();
    }
}