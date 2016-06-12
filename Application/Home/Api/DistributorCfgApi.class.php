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



    public function _init(){
        $this->model = new DistributorCfgModel();
    }


}