<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Uclient\Api;

use Uclient\Model\OAuth2TypeModel;
use Uclient\Model\UcenterMemberModel;

class UserApi extends Api
{

    /**
     * 检测邮箱是否存在
     */
    const DELETE_BY_ID = "Uclient/User/deleteByID";
    /**
     * 检测邮箱是否存在
     */
    const CHECK_EMAIL = "Uclient/User/checkEmail";


    /**
     * 根据查询
     */
    const FIND="Uclient/User/find";
    /**
     * 检测用户名是否存在
     */
    const CHECK_USER_NAME = "Uclient/User/checkUsername";

    const CHECK_MOBILE="Uclient/User/checkMobile";
    /**
     * Get info
     * @param  string $uid 用户ID或用户名
     * @return array 用户信息
     * @internal param bool $is_username 是否使用用户名查询
     */
    const GET_INFO = "Uclient/User/getInfo";
    /**
     * 注册接口
     * @param  string $username 用户名
     * @param  string $password 用户密码
     * @param  string $email 用户邮箱
     * @param  string $mobile 用户手机号码
     * @param int $from 第三方Oauth2登录\注册来源
     * @return array 注册失败-错误信息
     */
    const REGISTER = "Uclient/User/register";
    /**
     * 登录接口
     * @param  string $username 用户名
     * @param  string $password 用户密码
     * @param  integer $type 用户名类型 （1-用户名，2-邮箱，3-手机，4-UID）
     * @return integer           登录成功-用户ID，登录失败-错误编号
     */
    const LOGIN = "Uclient/User/login";
    /**
     * 获取用户信息
     * @param  string $uid 用户ID或用户名
     * @param  boolean $is_username 是否使用用户名查询
     * @return array                用户信息
     */
    const INFO = "Uclient/User/info";


    const UPDATEPWD="Uclient/User/updatePwd";


    /**
     * 更新数据
     */
    const UPDATE="Uclient/User/update";


    const SAVE_BY_ID="Uclient/User/saveByID";


    public function saveByID($ID, $entity) {
        unset($entity['id']);

        return $this -> save(array('id' => $ID), $entity);
    }

    /**
     * 保存
     * @return status|boolean , info 错误信息或更新条数
     */
    public function save($map, $entity) {

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
     * 返回错误结构
     * @return array('status'=>boolean,'info'=>Object)
     */
    protected function apiReturnErr($info) {
        return array('status' => false, 'info' => $info);
    }

    /**
     * 返回成功结构
     * @return array('status'=>boolean,'info'=>Object)
     */
    protected function apiReturnSuc($info) {
        return array('status' => true, 'info' => $info);
    }



    /**
     * 构造方法，实例化操作模型
     */
    protected function _init()
    {
        $this->model = new UcenterMemberModel();
    }

    public function deleteByID($uid){

        $result = $this->model->where(array('id'=>$uid))->delete();
        if($result === false){
            return array('status' => false, 'info' => $this->model->getDbError());
        }else{
            return array('status' => true, 'info' => $result);
        }
    }
    /**
     * 获取用户信息
     * @param  string $uid 用户ID
     * @return array 用户信息
     * @internal param bool $is_username 是否使用用户名查询
     */
    public function getInfo($uid)
    {
        $map = array();
        $map['id'] = $uid;

        $user = $this->model->where($map)->field('id,password,username,email,mobile,status')->find();

        if($user == false){
            return array('status' => false, 'info' => $this->model->getDbError());
        }
        if (is_array($user)) {
            return array('status' => true, 'info' => $user);
        } else {
            return array('status' => true, 'info' => "用户不存在");
        }

    }

    /**
     * 获取数据find
     */
    public function find($map,$order=false) {
        if($order === false){
            $result = $this -> model -> where($map) -> find();
        }else{
            $result = $this->model->where($map)->order($order)->find();
        }
        if ($result === false) {
            $error = $this -> model -> getDbError();
            return $this -> apiReturnErr($error);
        } else {
            return $this -> apiReturnSuc($result);
        }
    }


    /**
     * 注册一个新用户
     * @param  string $username 用户名
     * @param  string $password 用户密码|明文
     * @param  string $email 用户邮箱
     * @param  string $mobile 用户手机号码
     * @param int $from 第三方Oauth2登录\注册来源
     * @return array 注册失败-错误信息
     */
    public function register($username, $password, $email, $mobile = '', $regFrom = 0)
    {
        if (!OAuth2TypeModel::checkType($regFrom)) {
            $regFrom = OAuth2TypeModel::OTHER_APP;
        }

        if(!$this->is_username($username)){
            $this->apiReturnErr("用户名非法,仅中英文!");
        }

        $data = array(
            'username' => $username,
            'password' => $password,
            'email' => $email,
            'mobile' => $mobile,
            'reg_from' => $regFrom,
        );

        //验证手机
        if (empty($data['mobile'])) unset($data['mobile']);
        //验证邮箱
        if (empty($data['email'])) unset($data['email']);
//        $this->model->startTrans();
        $uid = 0;
        $error = "";
        /* 添加Ucenter_member用户 */
        if ($this->model->create($data)) {
            $uid = $this->model->add();
            if ($uid > 0) {
            } else {
                $error = "未知错误!";
            }
        } else {
            $error = $this->model->getError();
            $error = $this->getRegisterError($error);
        }
        /* 添加common_member用户 */

//        if($uid > 0){
        //ucenter_member添加成功了
//        }
        //
        if (empty($error)) {
//            $this->model->commit();
            return array('status' => true, 'info' => $uid);
//            return $this->apiReturnSuc($uid);
        } else {
//            $this->model->rollback();
            return array('status' => false, 'info' => $error);
//            return $this->apiReturnErr($error);
        }

    }

    /**
     * 用户登录认证
     * @param  string $username 用户名
     * @param  string $password 用户密码
     * @param  integer $type 用户名类型 （1-用户名，2-邮箱，3-手机，4-UID）
     * @return integer           登录成功-用户ID，登录失败-错误编号
     */
    public
    function login($username, $password, $type = 1)
    {
        //dump($this->model);
        $result = $this->model->login($username, $password, $type);
        if ($result > 0) {
            return array('status' => true, 'info' => $result);
        } else {
            switch ($result) {
                case 0:
                    $result = "参数错误";
                    break;
                case -1:
                    $result = "用户不存在";
                    break;
                case -2:
                    $result = "密码错误";
                    break;
                default:
                    $result = "未知";
                    break;
            }
            return array('status' => false, 'info' => $result);
        }
    }

    /**
     * 获取用户信息
     * @param  string $uid 用户ID或用户名
     * @param  boolean $is_username 是否使用用户名查询
     * @return array                用户信息
     */
    public
    function info($uid, $is_username = false)
    {
        $result = $this->model->info($uid, $is_username);
        if ($result === -1) {
            return array('status' => false, 'info' => '用户不存在');
        } else {
            return array('status' => true, 'info' => $result);
        }
    }

    /**
     * 检测用户名
     * @param $username
     * @return int 错误编号
     * @internal param string $field 用户名
     */
    public
    function checkUsername($username)
    {
        $result = $this->model->checkField($username, 1);
        if ($result > 0) {
            return array('status' => true, 'info' => $result);
        } else {
            return array('status' => false, 'info' => $result);
        }
    }

    /**
     * 检测邮箱
     * @param  string $email 邮箱
     * @return integer         错误编号
     */
    public
    function checkEmail($email)
    {
        $result = $this->model->checkField($email, 2);
        if ($result > 0) {
            return array('status' => true, 'info' => $result);
        } else {
            return array('status' => false, 'info' => $result);
        }
    }

    /**
     * 检测手机
     * @param  string $mobile 手机
     * @return integer         错误编号
     */
    public
    function checkMobile($mobile)
    {
        $result = $this->model->checkField($mobile, 3);
        if ($result > 0) {
            return array('status' => true, 'info' => $result);
        } else {
            return array('status' => false, 'info' => $result);
        }
    }

    /**
     * 更新密码
     * @param integer @uid 用户id
     * @return array(status,info)
     */
    public
    function updatePwd($uid, $password)
    {
        if ($this->model->updatePwd($uid, $password) !== false) {

            $return['status'] = true;

        } else {
            $return['status'] = false;
            $return['info'] = $this->model->getDbError();
        }

        return $return;

    }



    /**
     * 更新用户信息
     * @param int $uid 用户id
     * @param string $password 密码，用来验证
     * @param array $data 修改的字段数组
     * @return true 修改成功，false 修改失败
     * @author huajie <banhuajie@163.com>
     */
    public
    function updateInfo($uid, $password, $data)
    {
        if ($this->model->updateUserFields($uid, $password, $data) !== false) {
            $return['status'] = true;
        } else {
            $return['status'] = false;
            $return['info'] = $this->getRegisterError($this->model->getError());
        }
        return $return;
    }

    /**
     * 删除用户/假删除
     * @param integer @uid 用户id
     * @return array(status,info)
     */
    public
    function delete($uid)
    {
        if ($this->model->where(array('id' => $uid))->save(array('status' => -1)) !== false) {

            $return['status'] = true;

        } else {
            $return['status'] = false;
            $return['info'] = $this->model->getDbError();
        }

        return $return;

    }

    /**
     * 检查用户名是否符合规定 (两个字符以上，只能有中文，字母，数字，下划线的)
     *
     * @param STRING $username 要检查的用户名
     * @return  TRUE or FALSE
     */
    function is_username($username) {
        $strlen = strlen($username);
        if(!preg_match("/^[a-zA-Z0-9_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]+$/", $username)){
            return false;
        } elseif ( 20 < $strlen || $strlen < 2 ) {
            return false;
        }
        return true;
    }

    /**
     * 获取注册错误代码的描述信息
     *
     */
    public
    function getRegisterError($error)
    {
        $errDesc = "";
        switch ($error) {
            case -1:
                $errDesc = "用户名长度不合法";
                break;
            case -2:
                $errDesc = "用户名禁止注册";
                break;
            case -3:
                $errDesc = "用户名被占用";
                break;
            case -4:
                $errDesc = "密码长度不合法";
                break;
            case -5:
                $errDesc = "邮箱格式不正确";
                break;
            case -6:
                $errDesc = "邮箱长度不合法";
                break;
            case -7:
                $errDesc = "邮箱禁止注册";
                break;
            case -8:
                $errDesc = "邮箱被占用";
                break;
            case -9:
                $errDesc = "手机格式不正确";
                break;
            case -10:
                $errDesc = "手机禁止注册";
                break;
            case -11:
                $errDesc = "手机号被占用";
                break;

            default:
                $errDesc = $error;
                break;
        }

        return $errDesc;
    }

}
