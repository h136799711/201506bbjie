<?php
namespace Admin\Api;
use Common\Api\Api;
use Admin\Model\ParcelNumberModel;
/**
 * Created by PhpStorm.
 * User: an
 * Date: 2015/8/22
 * Time: 9:18
 */
class ParcelNumberApi extends Api{
    const ADD="Admin/ParcelNumber/add";
    const DELETE="Admin/ParcelNumber/delete";
    const SAVE_BY_ID="Admin/ParcelNumber/saveById";
    const QUERY="Admin/ParcelNumber/query";
    const QUERY_NO_PAGING="Admin/ParcelNumber/queryNoPaging";

    protected function _init(){
        $this->model=new ParcelNumberModel();
    }
}