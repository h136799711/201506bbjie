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
use Home\Model\FinAccountBalanceHisModel;
use Home\Model\FinFucoinHisModel;

class BbjmemberSellerApi extends Api{

    /**
     * 扣除冻结资金
     */
    const MINUS_FROZEN_MONEY = "Home/BbjmemberSeller/minusFrozenMoney";

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


    /**
     * 减少冻结资金
     * @param $uid
     * @param $price
     * @param $notes
     * @return bool|\Common\Api\返回影响记录数
     */
    public function minusFrozenMoney($uid,$price,$notes){

        $result = $this->setDec(array('uid'=>$uid),"frozen_money",$price);

        if($result['status']){
            $entity = array(
                'uid'=>$uid,
                'defray'=>$price,
                'income'=>0,
                'create_time'=>time(),
                'notes'=>$notes,
                'dtree_type'=>FinAccountBalanceHisModel::TYPE_TASK_OVER_MINUS_MONEY,
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

