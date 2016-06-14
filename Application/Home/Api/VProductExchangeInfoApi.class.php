<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Home\Api;
use \Common\Api\Api;
use Home\Model\VProductExchangeInfoModel;

class VProductExchangeInfoApi extends Api{

    /**
     * 统计
     */
    const COUNT = "Home/VProductExchangeInfo/count";
    /**
     * 新增
     */
    const ADD = "Home/VProductExchangeInfo/add";
    /**
     * 删除
     */
    const DELETE = "Home/VProductExchangeInfo/delete";
    /**
     * 不分页查询
     */
    const QUERY_NO_PAGING = "Home/VProductExchangeInfo/queryNoPaging";
    /**
     * 分页查询
     */
    const QUERY = "Home/VProductExchangeInfo/query";

    /**
     * 获取用户信息
     */
    const GET_INFO = "Home/VProductExchangeInfo/getInfo";

    /**
     * 更新
     */
    const SAVE_BY_ID = "Home/VProductExchangeInfo/saveByID";

	protected function _init(){
		$this->model = new VProductExchangeInfoModel();
	}

    public function getPersonInfo($map,$pager){
        $result = $this->model->where($map)->order(" create_time desc ")->field("create_time,p_id,uid,id,username,head")-> page($pager['curpageindex'] . ',' . $pager['pagesize']) ->select();
        if($result === false){
            return $this->apiReturnErr($this->model->getDbError());
        }else{

            $ret = array(
                'list'=>$result,
                'pager'=>array(
                    'pageindex'=>$pager['curpageindex'] ,
                    'pagesize'=>$pager['pagesize'],
                    'total'=>0,
                )
            );

            $count = $this -> model -> where($map) -> count();

            $ret['pager']['total'] = $count;

            return $this->apiReturnSuc($ret);
        }
    }

}

