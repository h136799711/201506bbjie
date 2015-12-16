<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 15/12/15
 * Time: 16:32
 */

namespace Admin\Api;


use Admin\Model\UserExpHisModel;
use Common\Api\Api;

class UserExpHisApi extends Api {


    const QUERY="Admin/UserExpHis/query";

    const ADD="Admin/UserExpHis/add";

    const DELETE="Admin/UserExpHis/delete";

    const SAVE="Admin/UserExpHis/save";

    const SAVE_BY_ID="Admin/UserExpHis/saveById";


    const GET_INFO="Admin/UserExpHis/getInfo";

    public function _init(){
        $this->model = new UserExpHisModel();
    }

}