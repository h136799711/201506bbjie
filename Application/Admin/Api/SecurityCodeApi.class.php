<?php
namespace Admin\Api;

use Admin\Model\SecurityCodeModel;
use Common\Api\Api;
/**
 * Created by PhpStorm.
 * User: an
 * Date: 2015/9/2
 * Time: 15:34
 */
class SecurityCodeApi extends Api{

    const ADD="Admin/SecurityCode/add";

    const QUERY="Admin/SecurityCode/query";

    const QUERY_NO_PAGING="Admin/SecurityCode/queryNoPaging";

    const SAVE_BY_ID="Admin/SecurityCode/saveById";

    const GET_INFO = "Admin/SecurityCode/getInfo";

    /**
     * 验证验证码是否有效
     * @param $code         编码
     * @param $mobile       手机
     * @param $type         使用场景  （1. 注册  2. 密码更新）
     * @return array
     */
    const IS_LEGAL_CODE = "Admin/SecurityCode/isLegalCode";

    public function _init(){
        $this->model=new SecurityCodeModel();
    }

    /**
     * 验证验证码是否有效
     * @param $code         编码
     * @param $mobile       手机      不能为空
     * @param $type         使用场景  （1. 注册  2. 密码更新）
     * @return array
     */
    public function isLegalCode($code,$mobile,$type){

        $map=array(
            'code'=>$code,
            'accepter'=>$mobile,
            'type'=>$type,
        );
        $order="endtime desc";
        $result = $this->model->where($map)->order($order)->find();
        if($result === false){
            return $this->apiReturnErr($this->model->getDbError());
        }

        if(is_null($result)){
            return $this->apiReturnErr("验证码无效!");
        }

        $codeEntity = $result;

        if($codeEntity['status'] != 0){
            return $this->apiReturnErr("验证码已使用，请重新获取!");
        }

        if($codeEntity['endtime'] < NOW_TIME){
            return $this->apiReturnErr("验证码已过期，请重新获取!");
        }

        $result=$this->model->where(array('accepter'=>$mobile))->save(array('status'=>1));
        if($result === false){
            return $this->apiReturnErr($this->model->getDbError());
        }

        return $this->apiReturnSuc($codeEntity);

    }


}