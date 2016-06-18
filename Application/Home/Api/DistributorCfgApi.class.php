<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 16/6/10
 * Time: 20:44
 */

namespace Home\Api;


use Common\Api\Api;
use Home\Model\DistributorCfgModel;

class DistributorCfgApi extends Api {

    /**
     * 获取单个用户信息
     */
    const GET_INFO = "Home/DistributorCfg/getInfo";

    public function _init(){
        $this->model = new DistributorCfgModel();
    }


}