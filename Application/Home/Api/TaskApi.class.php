<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Home\Api;
use \Common\Api\Api;
use \Home\Model\TaskModel;

class TaskApi extends Api{


    /**
     * 新增
     */
    const ADD = "Home/Task/add";
    /**
     * 删除
     */
    const DELETE = "Home/Task/delete";
    /**
     * 不分页查询
     */
    const QUERY_NO_PAGING = "Home/Task/queryNoPaging";
    /**
     * 分页查询
     */
    const QUERY = "Home/Task/query";

    /**
     * 获取用户信息
     */
    const GET_INFO = "Home/Task/getInfo";

    /**
     * 保存用户信息
     */
    const SAVE_BY_ID = "Home/Task/saveByID";

	protected function _init(){
		$this->model = new TaskModel();
	}

//	public function register($username, $password, $email, $mobile = '',$entity,$list,$list2){
//      $result = $this->model->register($username, $password, $email, $mobile);
//	    	if($result > 0){//成功
//	    		return array('status'=>true,'info'=>$result);
//	    	}else{
//	    		return array('status'=>false,'info'=>$this->getRegisterError($result));
//	    	}
//	}
}

