<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 16/3/15
 * Time: 17:20
 */

namespace Home\Api;


use Common\Api\Api;
use Home\Model\FinFucoinHisModel;

class FinFucoinHisApi extends Api {

    /**
     * 减少宝币
     */
    const MINUS = "Home/FinFucoinHis/minus";

    /**
     * 新增宝币
     */
    const PLUS = "Home/FinFucoinHis/plus";

    /**
     * 删除
     */
    const DELETE = "Home/FinFucoinHis/delete";
    /**
     * 不分页查询
     */
    const QUERY_NO_PAGING = "Home/FinFucoinHis/queryNoPaging";
    /**
     * 分页查询
     */
    const QUERY = "Home/FinFucoinHis/query";

    /**
     * 获取信息
     */
    const GET_INFO = "Home/FinFucoinHis/getInfo";


    protected function _init(){
        $this->model = new FinFucoinHisModel();
    }

    /**
     * 增福币
     * @param $uid
     * @param $coin
     * @param $type
     * @param $notes
     * @return array|\Common\Api\返回影响记录数
     */
    public function plus($uid,$coin,$left_fucoin,$type,$notes){
        $entity = array(
            'uid'=>$uid,
            'defray'=>0,
            'income'=>$coin,
            'create_time'=>time(),
            'notes'=>$notes,
            'dtree_type'=>$type,
            'left_fucoin'=>$left_fucoin,
            'status'=>1,
        );

        $result = $this->model->add($entity);
        if($result === false){
            return $this->apiReturnErr($this->model->getDbError());
        }

        $api = new BbjmemberApi();
        $result = $api->setInc(array('uid'=>$uid),'fucoin',$coin);

        return $result;

    }

    /**
     * 减福币
     * @param $uid
     * @param $coin
     * @param $type
     * @param $notes
     * @return array|\Common\Api\返回影响记录数
     */
    public function minus($uid,$coin,$left_fucoin,$type,$notes){
        $entity = array(
            'uid'=>$uid,
            'defray'=>$coin,
            'income'=>0,
            'create_time'=>time(),
            'notes'=>$notes,
            'dtree_type'=>$type,
            'left_fucoin'=>$left_fucoin,
            'status'=>1,
        );

        $result = $this->model->add($entity);
        if($result === false){
            return $this->apiReturnErr($this->model->getDbError());
        }

        $api = new BbjmemberApi();
        $result = $api->setDec(array('uid'=>$uid),'fucoin',$coin);

        return $result;

    }

}