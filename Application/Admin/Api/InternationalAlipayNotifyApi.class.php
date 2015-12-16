<?php
namespace Admin\Api;

use Admin\Model\InternationalAlipayNotifyModel;
use Common\Api\Api;

/**
 * Created by PhpStorm.
 * User: an
 * Date: 2015/9/24
 * Time: 11:39
 */
class InternationalAlipayNotifyApi extends Api{
    const ADD="Admin/InternationalAlipayNotify/add";

    /**
     * getInfo
     */
    const GET_INFO="Admin/InternationalAlipayNotify/getInfo";



    protected function _init(){
        $this->model=new InternationalAlipayNotifyModel();
    }



}