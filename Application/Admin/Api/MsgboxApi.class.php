<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------

namespace Admin\Api;

use Common\Api\Api;
use Admin\Model\MsgboxModel;

class MsgboxApi extends Api{

    /**
     * 统计
     */
    const COUNT = "Admin/Msgbox/count";
    /**
     * 添加
     */
    const ADD = "Admin/Msgbox/add";
    /**
     * 保存
     */
    const SAVE = "Admin/Msgbox/save";
    /**
     * 保存根据ID主键
     */
    const SAVE_BY_ID = "Admin/Msgbox/saveByID";

    /**
     * 删除
     */
    const DELETE = "Admin/Msgbox/delete";

    /**
     * 查询
     */
    const QUERY = "Admin/Msgbox/query";
    /**
     * 查询一条数据
     */
    const GET_INFO = "Admin/Msgbox/getInfo";
    /**
     * 查询数据
     */
    const QUERY_NO_PAGING = "Admin/Msgbox/queryNoPaging";


    /**
     * 根据消息uid和消息状态查询数据
     */
    const COUNT_BY_UID_AND_MSG_STATUS = "Admin/Msgbox/countByUidAndMsgStatus";

    /**
     * 根据消息uid和消息状态查询数据
     */
    const QUERY_BY_UID_AND_MSG_STATUS = "Admin/Msgbox/queryByUidAndMsgStatus";



	protected function _init(){
		$this->model = new MsgboxModel();
	}

    /**
     * 获取未读消息数目
     * @param $uid
     * @param $msg_status 0,1,2
     * @param array $page
     * @param array $params 参数
     * @return array
     */
    public function queryByUidAndMsgStatus($uid,$page,$params){

        $uid = intval($uid);
        $list = $this->model->alias("mb")->join("LEFT JOIN __MESSAGE__ as msg on msg.id = mb.msg_id ")
            ->join("LEFT JOIN common_datatree as dt on dt.id = msg.dtree_type ")
            ->join("LEFT JOIN common_member as member on member.uid = msg.from_id ")
            ->page($page['curpage'] . ',' . $page['size'])
            ->field("msg.id as msg_id,mb.id as msg_box_id,member.nickname,dt.name as msg_type_name,msg.dtree_type,msg.content,msg.title,msg.create_time,msg.send_time,msg.from_id,msg.summary,mb.msg_status")
            ->where("mb.to_id = ".$uid." and msg.status = 1 and mb.msg_status < 2")
            ->order("msg.create_time desc")
            ->select();


        if ($list === false) {
            $error = $this -> model -> getDbError();
            return $this -> apiReturnErr($error);
        }


        $count = $this -> model->alias("mb")->join("LEFT JOIN __MESSAGE__ as msg on msg.id = mb.msg_id ") -> where("mb.to_id = ".$uid." and msg.status = 1 and mb.msg_status < 2") -> count();
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

    /**
     * 获取未读消息数目
     * @param $uid
     * @param $msg_status 0,1,2
     * @return array
     */
    public function countByUidAndMsgStatus($uid,$msg_status){

        $uid = intval($uid);
        $result = $this->model->alias("mb")->join("LEFT JOIN __MESSAGE__ as msg on msg.id = mb.msg_id ")
//            ->join("LEFT JOIN common_datatree as dt on dt.id = msg.dtree_type ")
//            ->join("LEFT JOIN common_member as member on member.uid = msg.from_id ")
            ->field("msg.dtree_type,msg.content,msg.title,msg.create_time,msg.send_time,msg.from_id,msg.summary,mb.msg_status")->where("mb.to_id = ".$uid." and msg.status = 1 and mb.msg_status = ".$msg_status)->count();
//        dump($result);
//        dump($this->model->getLastSql());

        if($result === false){
            return $this->apiReturnErr($this->model->getDbError());
        }


        return $this->apiReturnSuc($result);


    }
	
}
