<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 15/12/16
 * Time: 10:40
 */

namespace Common\Api;


use Admin\Api\AuthGroupApi;
use Admin\Api\MemberApi;
use Home\Api\BbjmemberApi;
use Home\Api\BbjmemberSellerApi;
use Home\ConstVar\UserTypeConstVar;
use Uclient\Api\UserApi;

class AccountApi {


    /**
     * 获取用户信息
     */
    const GET_INFO = "Common/Account/getInfo";

    /**
     * 注册
     */
    const  Register = "Common/Account/register";

    /**
     * 登录
     */
    const  Login = "Common/Account/login";

    public function getInfo($uid){

        $result = apiCall(UserApi::GET_INFO, array($uid));

        if(!$result['status']){
            return array('status' => false, 'info' => $result['info']);
        }elseif(empty($result['info'])){
            return array('status' => false, 'info' => '未知错误code=1'.$uid);
        }
        //1. 存储userApi里的
        $user_info = $result['info'];

        $result = apiCall(MemberApi::GET_INFO, array(array('uid'=>$uid)));

        if(!$result['status']){
            return array('status' => false, 'info' => $result['info']);
        }elseif(empty($result['info'])){
            return array('status' => false, 'info' => '未知错误code=2'.$uid);
        }

        $member_info = $result['info'];
        $result = $this->getUserInfoByUserGroup($uid);
        if(!$result['status']){
            return array('status' => false, 'info' => $result['info']);
        }elseif(empty($result['info'])){
            return array('status' => false, 'info' => '未知错误code=3'.$uid);
        }

        $extra_info = $result['info'];

        $info = array_merge($user_info,$member_info,$extra_info);


        return array('status'=>true,'info'=>$info);

    }


    /**
     * @param array $entity username,password,from,user_type,invite_id,invite_username,daily_task_money
     * @return array
     */
    public function register($entity){

        if (!isset($entity['username']) || !isset($entity['password']) || !isset($entity['from'])) {
            return array('status' => false, 'info' => "账户信息缺失!");
        }

        $username = $entity['username'];
        if(strlen($username) < 6 || strlen($username) > 64){
            return array('status'=>false,'info'=>'用户名的长度必须大于6小于64');
        }

        if(!preg_match("/^[a-zA-Z]{1}[a-zA-Z0-9_]{6,64}$/",$username)){
            return array('status'=>false,'info'=>'账户名必须为字母、数字或下划线的组合且第一个必须为字母!');
        }

        $password = $entity['password'];
        if(strlen($password) < 6 || strlen($password) > 64){
            return array('status'=>false,'info'=>'密码的长度必须大于6小于64');
        }

        if(!preg_match("/^[0-9a-zA-Z\\\\,.?><;:!@#$%^&*()_+-=\[\]\{\}\|]{6,64}$/",$password)){
            return array('status'=>false,'info'=>'密码只能包含数字、英文字母以及如下字符 \,.?><;:!@#$%^&*()_+-=!');
        }

        $user_type = $entity['user_type'];//用户类型，2种 试民，商家

        $mobile = !empty($entity['mobile'])?$entity['mobile']:'';
        $email = !empty($entity['email'])?$entity['email']:'';
        $qq = !empty($entity['qq'])?$entity['qq']:'';
        $from  = !empty($entity['from'])?$entity['from']:'';
        $realname  = !empty($entity['realname'])?$entity['realname']:'';
        $nickname  = !empty($entity['nickname'])?$entity['nickname']:'';
        $birthday = !empty($entity['birthday'])?$entity['birthday']:0;
        $head = !empty($entity['head'])?$entity['head']:'';
        $sex = !empty($entity['sex'])?$entity['sex']:"";

        $trans = M();
        $trans->startTrans();
        $error = "";
        $flag = false;
        $result = apiCall(UserApi::REGISTER, array($username, $password, $email, $mobile, $from));
        $uid = 0;

        if ($result['status']) {
            $uid = $result['info'];
            $member = array(
                'uid' => $uid,
                'realname' => $realname,
                'nickname' => $nickname,
                'idnumber' => '',
                'sex' =>  $sex,
                'birthday' => $birthday,
                'qq' => $qq,
                'head'=>$head,
                'score' => 0,
                'login' => 0,
            );

            $result = apiCall(MemberApi::ADD, array($member));

            if (!$result['status']) {
                $flag = true;
                $error = '[用户信息]'.$result['info'];
            }else{

            }
        } else {
            $flag = true;
            $error = '[用户账户]'.$result['info'];
        }

        if(!$flag){
            $result =  $this->registerByUserType($user_type,$uid,$entity);

            if (!$result['status']) {
                $flag = true;
                $error = '[用户信息]'.$result['info'];
            }
        }


        if ($flag) {
            $result = apiCall(UserApi::DELETE_BY_ID, array($uid));
            $trans->rollback();
            return array('status' => false, 'info' => $error);
        } else {
            $trans->commit();
            return array('status' => true, 'info' => $uid);
        }
    }

    public function login($username,$password,$type){

        $result=apiCall(UserApi::LOGIN,array($username, $password,$type));
        return $result;
    }


    /**
     * 根据用户类型不同，新增到不同的表中
     * @param $user_type
     * @param $uid
     * @param $entity
     * @return array
     */
    private function registerByUserType($user_type,$uid,$entity){

        $invite_id = $entity['invite_id'];
        $invite_username = $entity['invite_username'];
        $daily_task_money = $entity['daily_task_money'];
        if($user_type == UserTypeConstVar::BBJ_MEMBER_GROUP){
            //试民
            $member = array(
                'uid'=>$uid,
                'referrer_id'=>$invite_id,
                'referrer_name'=>$invite_username,
                'taobao_account'=>'',
                'aliwawa'=>'',
                'daily_task_money'=>$daily_task_money,
                'dtree_job'=>'',
                'personal_signature'=>'',
                'brief_introduction'=>'',
                'address'=>'',
                'create_time'=>time(),
                'update_time'=>time(),
                'coins'=>0,
                'fucoin'=>0,
            );
            $result = apiCall(BbjmemberApi::ADD,array($member));
            if(!$result['status']){
                return $result;
            }
            $group=array(
                'uid'=>$uid,
                'group_id'=>UserTypeConstVar::BBJ_MEMBER_GROUP,
            );
            $result = apiCall(AuthGroupApi::ADD, array($group));
            return $result;

        }elseif($user_type == UserTypeConstVar::BBJ_SHOP_GROUP){

            $member = array(
                'uid'=>$uid,
                'referrer_id'=>$invite_id,
                'referrer_name'=>$invite_username,
                'taobao_account'=>'',
                'address'=>'',
                'aliwawa'=>'',
                'store_name'=>'',
                'store_url'=>'',
                'linkman'=>'',
                'linkman_tel'=>'',
                'task_linkman'=>'',
                'task_linkman_tel'=>'',
                'task_linkman_qq'=>'',
                'waybill_show'=>'',
                'linkman_qq'=>'',
                'exp'=>0,
                'coins'=>'0.000',
                'vip_level'=>0,
                'auth_status'=>0,
                'linkman_otherlink'=>'',
                'create_time'=>time(),
                'update_time'=>time(),
            );

            $result = apiCall(BbjmemberSellerApi::ADD,array($member));

            if(!$result['status']){
                return $result;
            }

            $group=array(
                'uid'=>$uid,
                'group_id'=>UserTypeConstVar::BBJ_SHOP_GROUP,
            );

            $result = apiCall(AuthGroupApi::ADD, array($group));
            return $result;

        }else{
            return array('status'=>true,'info'=>'');
        }
    }

    private function getUserInfoByUserGroup($uid){
        $map = array(
            'uid'=>$uid,
        );
        $result = apiCall(AuthGroupApi::QUERY_NO_PAGING,array($map));

        if(!$result['status']){
            return $result;
        }

        $group_list = $result['info'];
        $map = array(
            'uid'=>$uid,
        );
        foreach($group_list as $vo){
            if($vo['id'] == UserTypeConstVar::BBJ_SHOP_GROUP){
                $result = apiCall(BbjmemberSellerApi::GET_INFO,array($map));
                if(is_array($result['info'])){
                    $result['info']['user_type'] = UserTypeConstVar::BBJ_SHOP_GROUP;
                }
                return $result;
            }elseif($vo['id'] == UserTypeConstVar::BBJ_MEMBER_GROUP){
                $result = apiCall(BbjmemberApi::GET_INFO,array($map));
                if(is_array($result['info'])){
                    $result['info']['user_type'] = UserTypeConstVar::BBJ_SHOP_GROUP;
                }
                return $result;

            }
        }

    }

}