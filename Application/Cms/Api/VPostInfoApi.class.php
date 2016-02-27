<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------

namespace Cms\Api;

use Cms\Model\PostModel;
use Cms\Model\VPostInfoModel;
use Common\Api\Api;

class VPostInfoApi extends Api{

    const QUERY_NO_PAGING="Cms/VPostInfo/queryNoPaging";

    const QUERY="Cms/VPostInfo/query";

    const GET_INFO="Cms/VPostInfo/getInfo";


    protected function _init(){
        $this->model = new VPostInfoModel();
    }

}
