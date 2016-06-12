<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 16/6/8
 * Time: 10:09
 */

namespace Admin\Controller;


use Admin\Api\AuthGroupAccessApi;
use Admin\Api\AuthGroupApi;
use Admin\Api\MemberApi;
use Admin\Api\VMemberApi;
use Common\Api\AccountApi;
use Ucenter\Model\AuthGroupModel;
use Uclient\Api\UserApi;

class DistributorController extends AdminController {

    public function view(){
        $id = I('get.id',0);

        $result = apiCall(MemberApi::GET_INFO, array(array("uid"=>$id)));

        if(!$result['status']){
            $this->error($result['info']);
        }
        $this->assign("userinfo",$result['info']);

        $result = apiCall(UserApi::GET_INFO, array($id));
        if(!$result['status']){
            $this->error($result['info']);
        }
        $this->assign("useraccount",$result['info']);


        $result = apiCall(AuthGroupAccessApi::QUERY_GROUP_INFO, array($id));
        if(!$result['status']){
            $this->error($result['info']);
        }

        $this->assign("userroles",$result['info']);


        $this->display();
    }

    /**
     *
     * 添加经销商
     */
    public function add(){

        if(IS_POST){

            $username = I('post.username','');
            $password = I('post.password','');
            $repassword = I('post.repassword','');
            $email = $this->_param('email','','请填写邮件地址');

            if($password != $repassword){
                $this->error("密码和重复密码不一致！");
            }

            /* 调用注册接口注册用户 */
            $result = apiCall(UserApi::REGISTER, array($username, $password, $email));

            if($result['status']){ //注册成功
                $uid = $result['info'];
                $entity = array(
                    'uid'=>$uid,
                    'nickname'=>$username,
                    'realname'=>'',
                    'idnumber'=>'',
                    'email'=>$email,
                );
                $result = apiCall(MemberApi::ADD, array($entity));
                if(!$result['status']){
                    $this->error('经销商添加失败！');
                } else {

                    $result = apiCall(AuthGroupAccessApi::ADD_TO_GROUP,array($uid,AuthGroupModel::DISTRIBUTOR_GROUP_ID));

                    $this->success('经销商添加成功！',U('Distributor/index'));

                }
            } else { //注册失败，显示错误信息
                $this->error($result['info']);
            }

        }

        $this->display();
    }

    /**
     *
     */
    public function index(){
        $params = array();


        $where['nickname'] = array('like', "%" . I('nickname', '', 'trim') . "%");
        $where['uid'] = I('nickname',-1);
        $where['_logic'] = 'OR';

        $map['_complex'] = $where;
        $map['status'] = array('neq',-1);

        $page = array('curpage' => I('get.p'), 'size' => C('LIST_ROW'));
        $order = " last_login_time desc ";
        $params['nickname'] = I('nickname','','trim');
        $map['group_id'] = AuthGroupModel::DISTRIBUTOR_GROUP_ID;

        $result = apiCall(VMemberApi::QUERY, array($map, $page, $order));

        if ($result['status']) {

            $this -> assign("show", $result['info']['show']);
            $this -> assign("list", $result['info']['list']);
            $this -> display();
        } else {
            $this -> error($result['info']);
        }

    }
}