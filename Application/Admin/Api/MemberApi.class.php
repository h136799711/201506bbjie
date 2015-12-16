<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2015, http://www.gooraye.net. All Rights Reserved.
// |-----------------------------------------------------------------------------------

namespace Admin\Api;

use Admin\Model\MemberModel;
use Common\Api\Api;

class MemberApi extends Api{

    /**
     * 添加
     */
    const ADD = "Admin/Member/add";
    /**
     * 保存
     */
    const SAVE = "Admin/Member/save";
    /**
     * 保存根据ID主键
     */
    const SAVE_BY_ID = "Admin/Member/saveByID";

    /**
     * 删除
     */
    const DELETE = "Admin/Member/delete";

    /**
     * 查询
     */
    const QUERY = "Admin/Member/query";
    /**
     * 查询一条数据
     */
    const GET_INFO = "Admin/Member/getInfo";
    /**
     *
     */
    const QUERY_BY_GROUP = "Admin/Member/queryByGroup";
	
	const QUERY_NO_PAGING = "Admin/Member/queryNoPaging";

    const PRETEND_DELETE = "Admin/Member/pretendDelete";

    const QUERY_WITH_GROUP = "Admin/Member/queryWithGroup";

	//初始化
	protected function _init(){
		$this->model = new MemberModel();
	}



    /**
     * 根据id保存数据
     */
    public function saveByID($uid, $entity) {
        unset($entity['id']);

        return $this -> save(array('uid' => $uid), $entity);
    }

    /**
     * 与 queryWithGroup 类似，这个是较早版本
     * @param $map
     * @param $page
     * @return array
     */
	public function queryByGroup($map,$page)
    {
        $result = $this->model->queryByGroup($map, $page);
        if ($result === false) {
            return $this->apiReturnErr($this->model->getDbError());
        } else {
            return $this->apiReturnSuc($result);
        }
    }

    public function queryWithGroup($nicknameOrUid = '',$add_uid=0,$group_id=0, $page = array('curpage'=>0,'size'=>10), $order = false, $params = false, $fields = false){

        $map = array();
        if(!empty($nicknameOrUid)){
            $map['nickname']=array('like','%'.$nicknameOrUid.'%');
        }
        $map['add_uid'] = $add_uid;
        $map['group_id'] = $group_id;
        $list = $this->model->alias("m")->join(" __AUTH_GROUP_ACCESS__ as  ac on m.uid = ac.uid  ","RIGHT")
            ->where($map)
            -> page($page['curpage'] . ',' . $page['size']) ->select();

        if ($list === false) {
            $error = $this -> model -> getDbError();
            return $this -> apiReturnErr($error);
        }

        $count = $this -> model -> where($map) -> count();
        // 查询满足要求的总记录数
        $Page = new \Think\Page($count, $page['size']);

        //分页跳转的时候保证查询条件
        if ($params !== false) {
            foreach ($params as $key => $val) {
                $Page -> parameter[$key] = urlencode($val);
            }
        }

        // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page -> show();

        return $this -> apiReturnSuc(array("show" => $show, "list" => $list));

    }

}
