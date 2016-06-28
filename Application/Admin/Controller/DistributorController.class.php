<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 16/6/8
 * Time: 10:09
 */

namespace Admin\Controller;


use Admin\Api\AuthGroupAccessApi;
use Admin\Api\DatatreeApi;
use Admin\Api\MemberApi;
use Admin\Api\VMemberApi;
use Admin\Model\DatatreeModel;
use Home\Api\BbjmemberApi;
use Home\Api\BbjmemberSellerApi;
use Home\Api\DistributorCfgApi;
use Home\Api\FinAccountBalanceHisApi;
use Home\Api\FinBankaccountApi;
use Home\Api\VBbjmemberInfoApi;
use Home\Api\VBbjmemberSellerInfoApi;
use Home\Api\VDistributorCfgApi;
use Home\Api\VFinAccountBalanceHisApi;
use Home\Model\DistributorCfgModel;
use Home\Model\FinAccountBalanceHisModel;
use Money\Logic\DistributorMoney;
use Ucenter\Model\AuthGroupModel;
use Uclient\Api\UserApi;

class DistributorController extends AdminController {

    public function check(){

        $id = $this->_param('id',0);
        if((IS_POST || IS_AJAX ) && $id > 0){


            $type = $this->_param('type','fail');

            if($type == 'fail'){
                $result = apiCall(FinAccountBalanceHisApi::SAVE_BY_ID,array($id,array('status'=>FinAccountBalanceHisModel::STATUS_DENY)));
            }else if($type == 'pass'){
                $result = apiCall(FinAccountBalanceHisApi::SAVE_BY_ID,array($id,array('status'=>FinAccountBalanceHisModel::STATUS_PASSED)));
            }


            if($result['status']){
                $this->success("操作成功!");
            }else{
                $this->error($result['info']);
            }

        }

        $status = $this->_param("status",'2');
        $uid = $this->_param('uid',0);

        $page = array('curpage' => I('get.p', 0), 'size' => 10);

        $map=array(
            'status'=>$status
        );

        $param = array(
            'status'=>$status
        );

        if($uid > 0){
            $map['uid'] = $uid;
            $param['uid'] = $uid;
        }

        $map['dtree_type'] = FinAccountBalanceHisModel::TYPE_WITHDRAW_OF_DISTRIBUTOR;
        $order = "create_time desc";

        $result = apiCall(VFinAccountBalanceHisApi::QUERY,array($map,$page,$order,$param));
        $this->assign('status',$status);
        $list = $result['info']['list'];

        foreach($list as &$vo){
            if($vo['dtree_type'] == FinAccountBalanceHisModel::TYPE_WITHDRAW_OF_DISTRIBUTOR){
                $vo['_bank'] = json_decode($vo['extra'],JSON_OBJECT_AS_ARRAY);
            }
        }

        $this->assign('list',$list);
        $this->assign('show',$result['info']['show']);
        $this->assign("withdraw",FinAccountBalanceHisModel::TYPE_WITHDRAW);
        $this->display();
    }

    /**
     * 发起提现
     */
    public function myWithdrawAdd(){
        $userInfo = $this->getUserInfo();
        $map = array(
            'uid'=>$userInfo['id'],
        );

        $result = apiCall(DistributorCfgApi::GET_INFO,array($map));
        $distributor = $result['info'];
        if($result['status']){
            $this->assign("distributor",$distributor);
        }

        if(IS_GET){


            $map = array(
                'parentid'=>DatatreeModel::BANK_LIST,
            );
            $result = apiCall(DatatreeApi::QUERY_NO_PAGING,array($map));
            if($result['status']){
                $this->assign("bank_list",$result['info']);
            }


            $this->display();
        }else{
            $account = I('post.withdrawAccount','');
            $bank_name = I('post.bank_name','');
            $money = I('post.money',0,'floatval');
            if($money > 0 && floatval($distributor['money']) >= $money){

                $extra = array(
                    'account'=>$account,
                    'bank_name'=>$bank_name,
                );

                $notes = "用户申请提现".$money.'元到';
                $entity = array(
                    'uid'=>$distributor['uid'],
                    'defray'=>$money,
                    'income'=>0,
                    'left_money'=>$distributor['money']-$money,
                    'frozen_money'=>0,
                    'create_time'=>time(),
                    'notes'=>$notes,
                    'dtree_type'=>FinAccountBalanceHisModel::TYPE_WITHDRAW_OF_DISTRIBUTOR,
                    'imgurl'=>'',
                    'status'=>FinAccountBalanceHisModel::STATUS_WAIT_CHECK,
                    'extra'=> json_encode($extra),
                );

                $result = apiCall(FinAccountBalanceHisApi::ADD,array($entity));

                if($result['status']){

                    $his_id = $result['info'];

                    $api = new DistributorMoney();
                    $result = $api->withdrawMoney($distributor['uid'],$money);
                    if(!$result['status']){
                        $result = apiCall(FinAccountBalanceHisApi::SAVE_BY_ID,array($his_id,array('status'=>FinAccountBalanceHisModel::STATUS_DENY)));

                        ifFailedLogRecord($result,__FILE__.__LINE__);
                    }

                    $this->success("申请成功，请等待平台审核!");
                }else{
                    $this->error($result['info']);
                }

            }else{
                $this->error("提现金额不合法!");
            }

        }
    }
    /**
     * 我的提现
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     */
    public function myWithdraw(){

        $userInfo = $this->getUserInfo();
        $map = array(
            'uid'=>$userInfo['id'],
        );

        $result = apiCall(DistributorCfgApi::GET_INFO,array($map));

        if($result['status']){
            $this->assign("distributor",$result['info']);
        }

        //提现纪录
        $map = array(
            'uid'=>$userInfo['id'],
            'dtree_type'=>FinAccountBalanceHisModel::TYPE_WITHDRAW,
        );

        $order = " create_time desc ";
        $page = array('curpage'=>I('get.p',0),'size'=>10);
        $result = apiCall(VFinAccountBalanceHisApi::QUERY,array($map,$page,$order));

        if($result['status']){
            $list = $result['info']['list'];

            foreach($list as &$vo){
                $vo['_account'] = json_decode($vo['extra'],JSON_OBJECT_AS_ARRAY);
            }

            $this->assign("show",$result['info']['show']);
            $this->assign("list",$list);
        }




        $this->display();

    }


    /**
     * 我的收益
     */
    public function myProfit(){
        $userInfo = $this->getUserInfo();
        $map = array(
            'uid'=>$userInfo['id'],
        );

        $result = apiCall(DistributorCfgApi::GET_INFO,array($map));
//        dump($result);
        if($result['status']){
            $this->assign("distributor",$result['info']);
        }

        $map = array(
            'uid'=>$userInfo['id'],
            'dtree_type'=>FinAccountBalanceHisModel::TYPE_DISTRIBUTOR_GET,
        );

        $order = " create_time desc ";
        $page = array('curpage'=>I('get.p',0),'size'=>10);
        $result = apiCall(VFinAccountBalanceHisApi::QUERY,array($map,$page,$order));

        if($result['status']){
            $list = $result['info']['list'];

            foreach($list as &$vo){

                $map = array(
                    'uid'=>$vo['uid'],
                );
                $result = apiCall(MemberApi::GET_INFO,array($map));
                if($result['status']){
                    $vo['_distributor'] = $result['info'];
                }
            }

            $this->assign("show",$result['info']['show']);
            $this->assign("list",$list);
        }

        $this->display();
    }


    /**
     * 经销商个人中心
     */
    public function my(){

        $global_user = session("global_user");
        $invite_id = $global_user['username'];
        $invite_url = C('SITE_URL').U('Home/Index/register_sm',array('reffer'=>$global_user['id']));

        $this->assign("invite_id",$invite_id);
        $this->assign("invite_url",$invite_url);
        $this->display();
    }

    public function myPersonDetail(){

        $uid = I('get.uid',0,'intval');
        $userType = -1;
        $userInfo = null;
        if($uid > 0){
            $map  = array(
                'uid'=>$uid,
            );

            $result = apiCall(BbjmemberApi::GET_INFO,array($map));
            if($result['status'] && is_array($result['info'])){
                $userType = 0;
                $userInfo = $result['info'];
            }else{
                $result = apiCall(BbjmemberSellerApi::GET_INFO,array($map));

                if($result['status'] && is_array($result['info'])){
                    $userType = 1;
                    $userInfo = $result['info'];
                }
            }

        }

        if($userType == -1){
            $this->error("无法获取用户信息!");
        }

        $this->assign("usertype",$userType);
        $this->assign("userinfo",$userInfo);

        if($userType == 1){
            //商家发任务信息

        }else if($userType == 0){
            //试民完成的任务信息
            
        }





        $this->display();
    }

    /**
     * 经销商推荐的人查询
     */
    public function myPerson(){

        $global_user = session("global_user");
        $invite_id = $global_user['id'];
        $map = array(
            'referrer_id'=>$invite_id,
        );

        $order = "create_time desc";
//        dump($map);
        if(IS_POST || IS_AJAX){

            $p = I('get.p',1);
            $pageSize = I('get.pagesize',10);
            $type = I('get.type','seller');
            if($pageSize < 0 || $pageSize > 50){
                $pageSize = 10;
            }
            $page = array(
                'curpage'=>$p,
                'size'=>$pageSize,
            );

            if($type == 'normal'){
                $result = apiCall(VBbjmemberInfoApi::API_QUERY,array($map,$page,$order));

            }else{
                $result = apiCall(VBbjmemberSellerInfoApi::API_QUERY,array($map,$page,$order));
            }

            if($result['status']){
                $list = $result['info']['list'];
                foreach ($list as &$vo){
                    $vo['create_time'] = date("Y-m-d",$vo['create_time']);
                    $vo['last_login_time'] = date("Y-m-d",$vo['last_login_time']);
                }
                $result['info']['list'] = $list;
            }
            $this->apiReturn($result);
            return ;
        }


        $result = apiCall(VBbjmemberInfoApi::QUERY,array($map,$order));
        $normal_cnt = 0;
        if($result['status']){
            $normal_cnt = count($result['info']['list']);
            $this->assign("bbj_list",$result['info']['list']);
            $this->assign("bbj_show",$result['info']['show']);
        }

        //2.
        $map = array(
            'referrer_id'=>$invite_id,
        );

        $order = "create_time desc";

        $result = apiCall(VBbjmemberSellerInfoApi::QUERY,array($map,$order));
        $seller_cnt = 0;
        if($result['status']){
            $seller_cnt = count($result['info']['list']);
            $this->assign("bbjSeller_list",$result['info']['list']);
            $this->assign("bbjSeller_show",$result['info']['show']);
        }

        $this->assign("normal_cnt",$normal_cnt);
        $this->assign("seller_cnt",$seller_cnt);
        $this->display();
    }



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

            $rebate = I('post.rebate',0,'floatval');
            $username = I('post.username','');
            $password = I('post.password','');
            $repassword = I('post.repassword','');
            $email = $this->_param('email','','请填写邮件地址');

            if($password != $repassword){
                $this->error("密码和重复密码不一致！");
            }

            if($rebate < 0 || $rebate > 1){
                $rebate = 0;
                $this->error("分成比例只能在0-1之间的数字");
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

                    if($result['status']){

                        $entity = array(
                            'uid'=>$uid,
                            'rebate'=>$rebate,
                            'money'=>0,
                            'frozen_money'=>0,
                        );

                        $result = apiCall(DistributorCfgApi::ADD,array($entity));
                        if($result['status']){
                            $this->success('经销商添加成功！',U('Distributor/index'));

                        }

                    }

                    $this->error('经销商添加失败！',U('Distributor/index'));

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

        $result = apiCall(VDistributorCfgApi::QUERY, array($map, $page, $order));

        if ($result['status']) {

            $this -> assign("show", $result['info']['show']);
            $this -> assign("list", $result['info']['list']);
            $this -> display();
        } else {
            $this -> error($result['info']);
        }

    }

    public function disable($key=1){

    }

}