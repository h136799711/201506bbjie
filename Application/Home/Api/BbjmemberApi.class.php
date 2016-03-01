<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Home\Api;
use \Common\Api\Api;
use \Home\Model\BbjmemberModel;

class BbjmemberApi extends Api{

    /**
     * 新增
     */
    const ADD = "Home/Bbjmember/add";
    /**
     * 删除
     */
    const DELETE = "Home/Bbjmember/delete";
    /**
     * 不分页查询
     */
    const QUERY_NO_PAGING = "Home/Bbjmember/queryNoPaging";
    /**
     * 分页查询
     */
    const QUERY = "Home/Bbjmember/query";

    /**
     * 获取用户信息
     */
    const GET_INFO = "Home/Bbjmember/getInfo";

    /**
     * 保存用户信息
     */
    const SAVE_BY_ID = "Home/Bbjmember/saveByID";

	protected function _init(){
		$this->model = new BbjmemberModel();
	}

    public function saveByID($uid,$entity){
        return $this->save(array('uid'=>$uid),$entity);
    }

}

