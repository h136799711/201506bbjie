<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 16/3/29
 * Time: 11:19
 */

namespace Admin\Controller;


use Home\Api\VFinAccountBalanceHisApi;
use Home\Model\FinAccountBalanceHisModel;

/**
 * 提现审核
 * Class FinHisController
 * @package Admin\Controller
 */
class FinHisController  extends AdminController{

    public function index(){

        $type = FinAccountBalanceHisModel::TYPE_WITHDRAW;
        $map = array(
            'dtree_type'=>$type,
        );
        $page = array('curpage'=>I('get.p',0),'size'=>10);
        $order = "create_time desc";
        $param = array();
        $result = apiCall(VFinAccountBalanceHisApi::QUERY,array($map,$page,$order,$param));

        if($result['status']){
            $this->assign("list",$result['info']['list']);
            $this->assign("show",$result['info']['show']);
        }

        $this->display();
    }

}