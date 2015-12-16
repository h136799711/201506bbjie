<?php
namespace Admin\Api;
use Admin\Model\MgroupModel;
use Common\Api\Api;

/**
 * Created by PhpStorm.
 * User: an
 * Date: 2015/9/15
 * Time: 15:05
 */
class MgroupApi extends Api{

    const QUERY="Admin/Mgroup/query";

    const QUERY_NO_PAGING="Admin/Mgroup/queryNoPaging";

    const ADD="Admin/Mgroup/add";

    const DELETE="Admin/Mgroup/delete";

    const SAVE="Admin/Mgroup/save";

    const SAVE_BY_ID="Admin/Mgroup/saveById";

    const GET_INFO="Admin/Mgroup/getInfo";

    protected function _init(){
        $this->model=new MgroupModel();
    }
}