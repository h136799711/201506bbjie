<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Home\Api;
use \Common\Api\Api;
use \Home\Model\UcenterMemberModel;

class UcenterMemberApi extends Api{
	protected function _init(){
		$this->model = new UcenterMemberModel();
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

