<?php
namespace Admin\Api;

use Admin\Model\UidMgroupModel;
use Common\Api\Api;
use Shop\Api\MemberConfigApi;

/**
 * Created by PhpStorm.
 * User: an
 * Date: 2015/9/15
 * Time: 15:05
 */
class UidMgroupApi extends Api{
    const QUERY="Admin/UidMgroup/query";

    const ADD="Admin/UidMgroup/add";

    const DELETE="Admin/UidMgroup/delete";

    const SAVE="Admin/UidMgroup/save";

    const SAVE_BY_ID="Admin/UidMgroup/saveById";

    const QUERY_VIEW="Admin/UidMgroup/queryView";

    const GET_INFO="Admin/UidMgroup/getInfo";

    const QUERY_WITH_UID="Admin/UidMgroup/queryWithUid";

    /**
     * 查询信息包含店铺名称，用户组名称
     */
    const QUERY_WITH_STORE_AND_GROUP_INFO="Admin/UidMgroup/queryWithStoreAndGroupInfo";

    /**
     * 更新用户组信息
     * @param int $uid 用户ID
     * @param int $store_id 店铺ID
     * @return array
     */
    const UPDATE_GROUP = "Admin/UidMgroup/updateGroup";



    protected function _init(){
        $this->model=new UidMgroupModel();
    }

    /**
     * @param null $map
     * @param array $page
     * @param bool $order
     * @param bool $params
     * @param bool $fields
     * @return array
     */
    public function queryView($map = null, $page = array('curpage'=>0,'size'=>10), $order = false, $params = false, $fields = false){
        $query = $this->model;
        if(!is_null($map)){
            $query = $query->where($map);
        }
        if(!($order === false)){
            $query = $query->order($order);
        }
        if(!($fields === false)){
            $query = $query->field($fields);
        }
        $list = $query -> page($page['curpage'] . ',' . $page['size']) ->alias('a')->join('common_member b on a.uid=b.uid')->join('__MGROUP__ c on a.groupid=c.id')->field('a.id id,b.uid,b.nickname nickname,c.name,c.discount_ratio,c.commission_ratio,a.createtime')-> select();


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

    /**
     *
     */
    public function queryWithUid($uid=0,$storeid=false){
        $query = $this->model;
        $where="a.uid=".$uid;
        if($storeid===false){

        }else{
            $where.=' and c.store_id='.$storeid;
        }
        $result = $this->model -> alias('a')->join('__MGROUP__ AS c on a.groupid=c.id')->where($where)->field('a.id as id,a.uid as  uid,c.name,c.store_id,c.discount_ratio,c.commission_ratio,a.createtime,c.uid storeuid,c.remark')-> select();
        if ($result === false) {
            $error = $this -> model -> getDbError();
            return $this -> apiReturnErr($error);
        } else {
            return $this -> apiReturnSuc($result);
        }
    }

    /**
     * 更新用户组
     * @param int $uid 用户ID
     * @param int $store_id 店铺ID
     * @return array
     */
    public function updateGroup($uid,$store_id){

        //1. 查找用户的经验值
        $result = apiCall(MemberConfigApi::GET_INFO,array(array('uid'=>$uid)));
        if(!$result['status']){
            LogRecord($result['info'],__FILE__.__LINE__);
            return $this -> apiReturnErr("用户信息获取失败!");
        }

        $member_config = $result['info'];

        if(is_null($member_config)){
            return $this -> apiReturnErr("用户信息为空!");
        }

        //1. 查找满足条件的用户组
        $map = array(
            'conditions'=>array('ELT',$member_config['exp']),
            'store_id'=>$store_id
        );

        $order = " conditions desc ";

        $result = apiCall(MgroupApi::GET_INFO,array($map,$order));

        $m_group = $result['info'];

        if(is_null($m_group)){
            return $this -> apiReturnErr("用户组不能更新!");
        }
        //2. 查找对应的用户组ID
        $where = array(
            'a.uid'=>$uid,
            'store_id'=>$store_id,
        );
        $result = $this->model -> alias('a')->join('__MGROUP__ AS c on a.groupid=c.id')->where($where)
            ->field('a.id as aid,c.id as mid')-> find();

        if($result === false){
            return $this -> apiReturnErr($this->model ->getDbError());
        }

        $id = 0;
        $mid = 0;
        if(is_array($result)){
            $id = $result['aid'];
            $mid = $result['mid'];
        }

        if($id == 0){
            return $this -> apiReturnErr("无法获取用户组信息!");
        }

        if($mid == $m_group['id']){
            return $this -> apiReturnErr("不需要更新用户组信息!");
        }

        //3. 更新用户组信息
        $map = array(
            'id'=>$id,
        );
        $entity = array('groupid'=>$m_group['id']);
        $result = $this -> model -> create($entity, 2);
        if($result === false){
            $error = $this -> model -> getError();
            return $this -> apiReturnErr($error);
        }

        $result = $this -> model -> where($map) -> save();
        if ($result === false) {
            $error = $this -> model -> getDbError();
            return $this -> apiReturnErr($error);
        } else {
            return $this -> apiReturnSuc($result);
        }
    }

    /**
     *
     */
    public function queryWithStoreAndGroupInfo($uid=0){
        $where="a.uid=".$uid;
        $result = $this->model -> alias('a')->join('__MGROUP__ AS c on a.groupid=c.id')->join('__STORE__ AS s on s.id=c.store_id')->where($where)
            ->field('s.name as store_name,s.logo as store_logo,s.service_phone as store_phone,a.id as id,a.uid as  uid,c.name,c.store_id,c.discount_ratio,c.commission_ratio,a.createtime,c.uid storeuid,c.remark')
            -> select();
        if ($result === false) {
            $error = $this -> model -> getDbError();
            return $this -> apiReturnErr($error);
        } else {
            return $this -> apiReturnSuc($result);
        }
    }

}