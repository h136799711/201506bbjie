<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Home\Api;
use \Common\Api\Api;
use \Home\Model\BbjmemberSellerModel;

class BbjmemberSellerApi extends Api{


    const SET_INC = "Home/BbjmemberSeller/setInc";
    const SET_DESC = "Home/BbjmemberSeller/setDec";
    /**
     * 新增
     */
    const ADD = "Home/BbjmemberSeller/add";
    /**
     * 删除
     */
    const DELETE = "Home/BbjmemberSeller/delete";
    /**
     * 不分页查询
     */
    const QUERY_NO_PAGING = "Home/BbjmemberSeller/queryNoPaging";
    /**
     * 分页查询
     */
    const QUERY = "Home/BbjmemberSeller/query";

    /**
     * 获取用户信息
     */
    const GET_INFO = "Home/BbjmemberSeller/getInfo";

    /**
     * 保存用户信息
     */
    const SAVE_BY_ID = "Home/BbjmemberSeller/saveByID";


    protected function _init(){
		$this->model = new BbjmemberSellerModel();
	}

    public function saveByID($uid,$entity){
        return $this -> save(array('uid' => $uid), $entity);
    }

}

