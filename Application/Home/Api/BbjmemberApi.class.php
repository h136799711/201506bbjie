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
use Home\Model\FinAccountBalanceHisModel;
use Home\Model\FinFucoinHisModel;

class BbjmemberApi extends Api{

    /**
     * 增加虚拟币
     */
    const ADD_FU_COINS = "Home/Bbjmember/addFucoins";
    /**
     * 增加金额
     */
    const ADD_MONEY = "Home/Bbjmember/addMoney";
    /**
     * 增加
     */
    const SET_INC = "Home/Bbjmember/setInc";

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

    public function addFucoins($uid,$fucoin,$notes,$left_fucoin=0){

        $result = $this->setInc(array('uid'=>$uid),"fucoin",$fucoin);

        if($result['status']){
            $entity = array(
                'uid'=>$uid,
                'defray'=>0,
                'income'=>$fucoin,
                'create_time'=>time(),
                'notes'=>$notes,
                'dtree_type'=>FinFucoinHisModel::PLUS_COMPLETE_TASK,
                'status'=>1,
                'left_fucoin'=>$left_fucoin,
            );
            $api = new FinFucoinHisApi();
            $result = $api->add($entity);
            return $result;
        }

        return $result;

    }

    public function addMoney($uid,$price,$notes){

        $result = $this->setInc(array('uid'=>$uid),"coins",$price);

        if($result['status']){
            $entity = array(
                'uid'=>$uid,
                'defray'=>0,
                'income'=>$price,
                'create_time'=>time(),
                'notes'=>$notes,
                'dtree_type'=>FinAccountBalanceHisModel::TYPE_TASK_OVER_RETURN_TO_USER,
                'imgurl'=>'',
                'status'=>1,
            );
            $api = new FinAccountBalanceHisApi();
            $result = $api->add($entity);
            return $result;
        }

        return $result;

    }

}

