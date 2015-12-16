<?php
namespace Admin\Api;

use Admin\Model\AlipayNotifyModel;
use Common\Api\Api;

/**
 * Created by PhpStorm.
 * User: an
 * Date: 2015/9/24
 * Time: 11:39
 */
class AlipayNotifyApi extends Api{
    const ADD="Admin/AlipayNotify/add";



    protected function _init(){
        $this->model=new AlipayNotifyModel();
    }



}