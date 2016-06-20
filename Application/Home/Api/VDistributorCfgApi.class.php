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
use Home\Model\VDistributorCfgModel;

class VDistributorCfgApi extends Api {

    /**
     * 获取单个用户信息
     */
    const QUERY = "Home/VDistributorCfg/query";

    /**
     * 获取单个用户信息
     */
    const GET_INFO = "Home/VDistributorCfg/getInfo";

    public function _init(){
        $this->model = new VDistributorCfgModel();
    }


}