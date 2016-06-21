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
     * 修改
     */
    const SET_DEC = "Home/DistributorCfg/setDec";
    /**
     * 修改
     */
    const SET_INC = "Home/DistributorCfg/setInc";
    /**
     * 修改
     */
    const SAVE_BY_ID = "Home/DistributorCfg/saveByID";

    /**
     * 添加
     */
    const ADD = "Home/DistributorCfg/add";
    /**
     * 获取单个用户信息
     */
    const GET_INFO = "Home/DistributorCfg/getInfo";

    public function _init(){
        $this->model = new DistributorCfgModel();
    }


}