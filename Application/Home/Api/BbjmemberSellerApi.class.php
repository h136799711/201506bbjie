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
     * 讲冻结资金的指定金额移动到余额之中
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     */
    const ADD_COINS_FROM_FROZEN_MONEY = "Home/BbjmemberSeller/addCoinsFromFrozenMoney";

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

    public function addMoneyFromFrozen($uid,$price,$notes){

    }

    public function addCoinsFromFrozenMoney($uid,$price,$notes){
        $this->model->startTrans();
        $flag = false;
        $error = "";
        $price = intval($price);
        $result = $this->setDec(array('uid'=>$uid),"frozen_money",$price);

        if($result['status']){

            $result = $this->setInc(array('uid'=>$uid),"coins",$price);

            if(!$result['status']){
                $flag = true;
                $error = $result['info'];
            }

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

            $entity = array(
                'uid'=>$uid,
                'defray'=>0,
                'income'=>$price,
                'create_time'=>time(),
                'notes'=>$notes,
                'dtree_type'=>FinAccountBalanceHisModel::TYPE_UNFREEZE_WHEN_TASK_OVER,
                'imgurl'=>'',
                'status'=>1,
            );
            $api = new FinAccountBalanceHisApi();
            $result = $api->add($entity);
        }else{
            $flag = true;
            $error = $result['info'];
        }


        if($flag){
            $this->model->rollback();
            return array('status'=>false,'info'=>$error);
        }else{
            $this->model->commit();
            return array('status'=>true,'info'=>'操作成功!');
        }


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

