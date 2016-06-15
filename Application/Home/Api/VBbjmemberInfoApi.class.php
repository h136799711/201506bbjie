<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Home\Api;

use \Common\Api\Api;
use Home\Model\BbjmemberModel;
use Home\Model\VBbjmemberInfoModel;
use Home\Model\VTaskHisInfoModel;

class VBbjmemberInfoApi extends Api{

    /**
     * 新增
     */
    const ADD = "Home/VBbjmemberInfo/add";
    /**
     * 删除
     */
    const DELETE = "Home/VBbjmemberInfo/delete";
    /**
     * 不分页查询
     */
    const QUERY_NO_PAGING = "Home/VBbjmemberInfo/queryNoPaging";
    /**
     * 分页查询
     */
    const QUERY = "Home/VBbjmemberInfo/query";
    /**
     * API分页查询
     */
    const API_QUERY = "Home/VBbjmemberInfo/apiQuery";


    /**
     * 获取用户信息
     */
    const GET_INFO = "Home/VBbjmemberInfo/getInfo";

    /**
     * 更新
     */
    const SAVE_BY_ID = "Home/VBbjmemberInfo/saveByID";

    /**
     * 获取审批的用户信息
     */
    const QUERY_SH_USER = "Home/VBbjmemberInfo/queryShUser";

	protected function _init(){
		$this->model = new VBbjmemberInfoModel();
	}

    public function queryShUser($page,$params){
        $map = array();
        $map['task_status'] = BbjmemberModel::TASK_OPEN;
        $map['auth_status'] = BbjmemberModel::AUTH_PASS;


        return $this->query($map,$page,"update_time desc",$params);
    }


}

