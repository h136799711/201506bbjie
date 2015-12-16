<?php
namespace Admin\Api;
use Admin\Model\OrdersPaycodeModel;
use Common\Api\Api;



/**
 * Created by PhpStorm.
 * User: an
 * Date: 2015/9/30
 * Time: 13:56
 */
class OrdersPaycodeApi extends Api{

    const SAVE_BY_ID="Admin/OrdersPaycode/saveByID";

    const ADD="Admin/OrdersPaycode/add";

    const QUERY="Admin/OrdersPaycode/query";

    const QUERY_NO_PAGING="Admin/OrdersPaycode/queryNoPaging";

    const GET_INFO="Admin/OrdersPaycode/getInfo";


    protected function _init(){
        $this->model=new OrdersPaycodeModel();
    }
}