<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 青<99701759@qq.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Admin\Controller;
use Common\Api\AccountApi;
use Home\Api\BbjmemberApi;
use Home\Api\BbjmemberSellerApi;
use Home\Api\FinAccountBalanceHisApi;
use Home\Api\HomePublicApi;
use Home\Api\ProductSearchWayApi;
use Home\Api\VBbjmemberInfoApi;
use Home\Api\VBbjmemberSellerInfoApi;
use Home\Api\VTaskProductSearchWayApi;
use Home\ConstVar\UserTypeConstVar;
use Home\Model\BbjmemberSellerModel;

class BBJVIPController extends AdminController{
    /**
     * 财务审核
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     */
	public function index(){
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

        $order = "create_time desc";

        $result = apiCall(FinAccountBalanceHisApi::QUERY,array($map,$page,$order,$param));
        $this->assign('status',$status);
		$this->assign('list',$result['info']['list']);
		$this->assign('show',$result['info']['show']);
		$this->display();
	}	
	/*
	 * 商品搜索管理审核
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function check_search(){
        $status = $this->_param('status','2');
		$map = array('status'=>$status);
		$page = array('curpage' => I('get.p', 0), 'size' => 10 );
        $order = "create_time desc";
        $result = apiCall(VTaskProductSearchWayApi::QUERY,array($map,$page,$order));

        $this->assign('status',$status);
        $this->assign('list',$result['info']['list']);
        $this->assign('show',$result['info']['show']);
		$this->display();
	}

    /**
     * 资金变动审核，
     * 提现、充值
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     */
	public function check(){

		$id = I('id','');
		$map = array( 'id'=>$id );
		$result=apiCall(FinAccountBalanceHisApi::GET_INFO, array($map));
		$uid=$result['info']['uid'];
        $dtree_type = $result['info']['dtree_type'];
        $defray = $result['info']['defray'];
        $income = $result['info']['income'];

        $result = apiCall(AccountApi::GET_INFO,array($uid));

        $userinfo = $result['info'];
        if($userinfo['user_type'] == UserTypeConstVar::BBJ_MEMBER_GROUP){
            //试民
            if($dtree_type == 3){//提现
                $left_frozen_money = $userinfo['frozen_money'] - $defray;
                if($left_frozen_money < 0){
                    $this->error("该账户出现异常!");
                }
                $entity = array(
                    'frozen_money'=>$left_frozen_money,
                );
                $result = apiCall(BbjmemberApi::SAVE_BY_ID,array($uid,$entity));
            }

        }else if($userinfo['user_type'] == UserTypeConstVar::BBJ_SHOP_GROUP){
            //商家
            if($dtree_type == 1){//充值
                $entity = array(
                    'coins'=>$income+$userinfo['coins'],
                );
                $result = apiCall(BbjmemberSellerApi::SAVE_BY_ID,array($uid,$entity));

            }elseif($dtree_type == 3){
                //提现
                $entity = array(
                    'frozen_money'=> $userinfo['frozen_money'] - $defray,
                );
                if($entity['frozen_money'] < 0){
                    $this->error("该账户出现异常!");
                }

                $result = apiCall(BbjmemberSellerApi::SAVE_BY_ID,array($uid,$entity));

            }

        }else{
            $this->error("无法识别的用户类型");
        }

        if($result['status']){
            $result = apiCall(FinAccountBalanceHisApi::SAVE_BY_ID,array($id,array('status'=>1)));
            if($result['status']){
                $this->success("充值成功!",U('Admin/BBJVIP/index'));
            }
        }

	}

	/*
	 * 资金审核失败
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function moneychecksb(){

        $id = I('id','');
        $map = array( 'id'=>$id );
        $result=apiCall(FinAccountBalanceHisApi::GET_INFO, array($map));
        $uid=$result['info']['uid'];
        $dtree_type = $result['info']['dtree_type'];
        $defray = $result['info']['defray'];
        $income = $result['info']['income'];

        $result = apiCall(AccountApi::GET_INFO,array($uid));

        $userinfo = $result['info'];
        if($userinfo['user_type'] == UserTypeConstVar::BBJ_MEMBER_GROUP){
            //试民
            if($dtree_type == 3){//提现

                $entity = array(
                    'coins'=>$userinfo['coins']+$defray,
                    'frozen_money'=>$userinfo['frozen_money']-$defray,
                );
                $result = apiCall(BbjmemberApi::SAVE_BY_ID,array($uid,$entity));
            }

        }else if($userinfo['user_type'] == UserTypeConstVar::BBJ_SHOP_GROUP){
            //商家
            if($dtree_type == 3){
                //提现
                $entity = array(
                    'coins'=>$userinfo['coins']+$defray,
                    'frozen_money'=>$userinfo['frozen_money']-$defray,
                );

                $result = apiCall(BbjmemberSellerApi::SAVE_BY_ID,array($uid,$entity));

            }

        }else{
            $this->error("无法识别的用户类型");
        }

        if($result['status']){
            $result = apiCall(FinAccountBalanceHisApi::SAVE_BY_ID,array($id,array('status'=> 4)));
            if($result['status']){
                $this->success("操作成功!",U('Admin/BBJVIP/index'));
            }
        }

	}
	/**
	 * 会员管理
     * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function vipmanager(){
		$page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
        $map = array();

		$result = apiCall(VBbjmemberSellerInfoApi::QUERY, array($map,$page));

		$this->assign('list',$result['info']['list']);
		$this->assign('show',$result['info']['show']);

		$this->display();
	}
	/*
	 * 撤销vip
	 * */
	public function quxiaovip(){
		$id=I('id',0);
		if($id!=0){
			$entity=array('vip_level'=>0);
			$result=apiCall(HomePublicApi::Bbjmember_Seller_SaveByID, array($id,$entity));
			if($result['status']){
				$this->success('撤销成功',U('Admin/BBJVIP/vipmanager'));
			}else{
				$this->error('撤销失败',U('Admin/BBJVIP/vipmanager'));
			}
		}
		
	}

    /**
     * 试民
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     */
	public function checksm(){
        $taobao = I('post.taobao','');
        if(empty($taobao)){
            $taobao = I('get.taobao','');
        }
        $auth_status = I('post.auth_status','');
        if(empty($auth_status)){
            $auth_status = I('get.auth_status','0');
        }
        $map = array(
            'auth_status'=>$auth_status,
        );
        $param = array(
            'auth_status'=>$auth_status,
        );
        if(!empty($taobao)){
            $map['taobao_account'] = $taobao;
            $param['taobao_account'] = $taobao;
        }

		$page = array('curpage' => I('get.p', 0), 'size' => 10);
        $order = "create_time desc";
		$result=apiCall(BbjmemberApi::QUERY, array($map,$page,$order,$param));

        $this->assign('taobao',$taobao);
        $this->assign('auth_status',$auth_status);
		$this->assign('list',$result['info']['list']);
		$this->assign('show',$result['info']['show']);
		$this->display();
	}

    /**
     * 试民管理
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     */
	public function managersm(){
		$username = $this->_param('username','');

        $map = array();
        $param = array();
        if (!empty($username)) {
			$map['username'] = array('like','%'. $username . '%');
            $param['username'] = $username;
		}

        $page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
        $order = "create_time desc";

        $result = apiCall(VBbjmemberInfoApi::QUERY, array($map,$page,$order,$param));

		$this->assign('list',$result['info']['list']);
		$this->assign('show',$result['info']['show']);

		$this->display();
	}

    /**
     * 商家审核
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     */
	public function checksj(){
        $aliwawa = I('post.aliwawa','');
        if(empty($aliwawa)){
            $aliwawa = I('get.aliwawa','');
        }

        $auth_status = I('post.auth_status','');
        if(empty($auth_status)){
            $auth_status = I('get.auth_status','0');
        }

		if (!empty($aliwawa)) {
			$map['aliwawa'] = array('like',$aliwawa . '%');
		}
        $map['auth_status']= $auth_status;
		$params = array(
            'auth_status'=>$auth_status,
            'aliwawa'=>$aliwawa,
        );

		$page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
        $order = "update_time desc";
		$result=apiCall(VBbjmemberSellerInfoApi::QUERY, array($map,$page,$order,$params));

        $this->assign("auth_status",$auth_status);
        $this->assign("aliwawa",$aliwawa);
		$this->assign('list',$result['info']['list']);
		$this->assign('show',$result['info']['show']);

		$this->display();
	}

    /**
     * 管理商家
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     */
	public function managersj(){
		$username = $this->_param('username','');

        $map = array();
        $params = array();
        if (!empty($username)) {
			$map['username'] = array('like','%'. $username . '%');
            $params['username'] = $username;
		}
		
		$page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
        $order = "create_time desc";
        $result = apiCall(VBbjmemberSellerInfoApi::QUERY,array($map,$page,$order,$params));

		$this->assign('list',$result['info']['list']);
		$this->assign('show',$result['info']['show']);

		$this->display();
	}

    /**
     * 试民 和 商家的审核
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     */
	public function check_user(){

		$id=I('get.id',0);
        $type = I('get.type','0');

		$entity=array('auth_status'=>1);

        if($type == 0){
            $result=apiCall(BbjmemberApi::SAVE_BY_ID, array($id,$entity));

            if($result['status']){
                $this->success('审核成功',U('Admin/BBJVIP/checksm'));
            }
        }else{
            $result=apiCall(BbjmemberSellerApi::SAVE_BY_ID, array($id,$entity));

            if($result['status']){
                $this->success('审核成功',U('Admin/BBJVIP/checksj'));
            }
        }

        if(!$result['status']){
            $this->error($result['info']);
        }
	}

	public function checksuccesssj(){

		$id=I('id');
		$entity=array('auth_status'=>1);
	}
	public function checksb(){

		$id=I('get.id',0);
		$entity=array('auth_status'=>2);
		$result=apiCall(BbjmemberSellerApi::SAVE_BY_ID, array($id,$entity));

		if($result['status']){
			$this->success('操作成功',U('Admin/BBJVIP/checksj'));
		}
	}


    /**
     * 查看用户信息
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     */
	public function view_user(){
		$map=array('uid'=>I('get.id',0));

        $uid = I('get.id',0);

        $result = apiCall(AccountApi::GET_INFO,array($uid));

        $this->assign("user_info",$result['info']);
        $this->display();
	}

	/*
	 * 所有任务
	 * */
	public function alltask(){
		$taskname=I('taskname','');
		if (!empty($taskname)) {
			$map['task_name'] = array('like','%'. $taskname . '%');
		}
		$page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
//		$order = " create_time desc ";
		$result = apiCall(HomePublicApi::Task_QueryAll, array($map, $page, $order));
//		dump($result);
		$this->assign('list',$result['info']['list']);
		$this->assign('show',$result['info']['show']);
		$this->display();
	}
	/*
	 * 查看搜索
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function searchview(){
		$map=array('id'=>I('id',0));

		$result = apiCall(VTaskProductSearchWayApi::GET_INFO, array($map));
		$this->assign('vo',$result['info']);
		$this->display();
	}
	/*
	 * 审核通过/驳回
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function sh(){

		$id=I('id',0);
        $status = $this->_param('status',1);
		if($id!=0){
			$map=array('status'=>$status);
			$result = apiCall(HomePublicApi::ProductSearchWay_SaveByID, array($id,$map));
			if($result['status']){
				$this->success('操作成功',U('Admin/BBJVIP/check_search'));
			}
		}else{
			$this->error('参数错误');
		}
	}


	/*
	 * 任务领取管理
	 * */
	public function taskgetview(){
		$map=array('task_id'=>I('id',0));
		$page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
//		$order = " create_time desc ";
		$result = apiCall(HomePublicApi::Task_His_QueryAll, array($map, $page, $order));
		$result1 = apiCall(HomePublicApi::Task_Query, array());
//		dump($result);dump($result1);
		$this->assign('list',$result['info']['list']);
		$this->assign('task',$result1['info']);
		$this->assign('show',$result['info']['show']);
		$this->display();
	}
	/*
	 * 删除任务
	 * */
	public function delete(){
		$id=I('id',0);
		$map=array('order_status'=>0);
		$result = apiCall(HomePublicApi::Task_SaveByID, array($id,$map));
		dump($result);
	}
}
