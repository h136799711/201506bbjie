<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------

namespace Cms\Api;

use Cms\Model\PostModel;
use Common\Api\Api;

class PostApi extends Api{

    const QUERY_NO_PAGING="Cms/Post/queryNoPaging";

    const QUERY="Cms/Post/query";

    const DELETE="Cms/Post/delete";

    const SAVE="Cms/Post/save";

    const SAVE_BY_ID="Cms/Post/saveByID";

    const ADD="Cms/Post/add";

    const GET_INFO="Cms/Post/getInfo";


    protected function _init(){
        $this->model = new PostModel();
    }

}
