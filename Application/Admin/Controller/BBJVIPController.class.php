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
                $entity = array(
                    'coins'=>$userinfo['coins'],
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
                    'frozen_money'=>$userinfo['frozen_money'] - $defray,
                );

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
	 * */
	public function vipmanager(){
		$page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
		$result=apiCall(HomePublicApi::Bbjmember_Seller_QueryAll, array($maps,$page));
		$result1=apiCall(HomePublicApi::UcenterUser_Query, array());
		$map=array('dtree_type'=>4);
		//$order=" create_time desc ";
		$result2=apiCall(HomePublicApi::FinAccountBalanceHis_Query, array($map,$order));
		for($i=0;$i<count($result['info']['list']);$i++){
			for($j=0;$j<count($result2['info']);$j++){
				if($result['info']['list'][$i]['uid']==$result2['info'][$j]['uid']){
					$result['info']['list'][$i]['time']=$result2['info'][$j]['create_time'];
					$result['info']['list'][$i]['text']=$result2['info'][$j]['notes'];
					continue;
				}
			}
			
		}
		//dump($result);
		
		$this->assign('seller',$result['info']['list']);
		$this->assign('sellershow',$result['info']['show']);
		$this->assign('user',$result1['info']);
		$this->assign('jilu',$result2['info']);
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
	public function managersm(){
		$username=I('username','');
		if (!empty($username)) {
			$map['username'] = array('like','%'. $username . '%');
		}
		$page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
		$result=apiCall(HomePublicApi::Bbjmember_QueryAll, array($maps,$page));
		$result1=apiCall(HomePublicApi::UcenterUser_Query, array($map));
		$this->assign('jilu',$result['info']['list']);
		$this->assign('show',$result['info']['show']);
		$this->assign('user',$result1['info']);
		$this->display();
	}
	public function delete_sm(){
		$uid=array('uid'=>I('id'));
		$id=array('id'=>I('id'));
		$result=apiCall(HomePublicApi::Bbjmember_Del, array($uid));
		if($result['status']){
			$result1=apiCall(HomePublicApi::UcenterUser_Del, array($id));
			if($result1['status']){
				$result2=apiCall(HomePublicApi::Member_Del, array($uid));
				if($result2['status']){
					$this->success('删除成功',U('Admin/BBJVIP/managersm'));
				}
			}
		
		}
	}

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
		$result=apiCall(BbjmemberSellerApi::QUERY, array($map,$page,$order,$params));

        $this->assign("auth_status",$auth_status);
        $this->assign("aliwawa",$aliwawa);
		$this->assign('list',$result['info']['list']);
		$this->assign('show',$result['info']['show']);

		$this->display();
	}
	public function managersj(){
		$username=I('username','');
		if (!empty($username)) {
			$map['username'] = array('like','%'. $username . '%');
		}
		
		$page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
		$result=apiCall(HomePublicApi::Bbjmember_Seller_QueryAll, array($maps,$page));
		$result1=apiCall(HomePublicApi::UcenterUser_Query, array($map));
		$this->assign('jilu',$result['info']['list']);
		$this->assign('show',$result['info']['show']);
		$this->assign('user',$result1['info']);
		$this->display();
	}
	
	public function delete_sj(){
		$uid=array('uid'=>I('id'));
		$id=array('id'=>I('id'));
		$result=apiCall(HomePublicApi::Bbjmember_Seller_Del, array($uid));
		if($result['status']){
			$result1=apiCall(HomePublicApi::UcenterUser_Del, array($id));
			if($result1['status']){
				$result2=apiCall(HomePublicApi::Member_Del, array($uid));
				if($result2['status']){
					$this->success('删除成功',U('Admin/BBJVIP/managersj'));
				}
			}
		}
	}
	public function checksuccess(){

		$id=I('id');
		$entity=array('auth_status'=>1);
		$result=apiCall(HomePublicApi::Bbjmember_SaveByID, array($id,$entity));
//		dump($result);
		if($result['status']){
			$this->success('审核成功',U('Admin/BBJVIP/checksm'));
		}
	}
	public function checksuccesssj(){

		$id=I('id');
		$entity=array('auth_status'=>1);
		$result=apiCall(HomePublicApi::Bbjmember_Seller_SaveByID, array($id,$entity));
//		dump($result);
		if($result['status']){
			$this->success('审核成功',U('Admin/BBJVIP/checksj'));
		}
	}
	public function checksb(){

		$id=I('get.id',0);
		$entity=array('auth_status'=>2);
		$result=apiCall(BbjmemberSellerApi::SAVE_BY_ID, array($id,$entity));

		if($result['status']){
			$this->success('操作成功',U('Admin/BBJVIP/checksj'));
		}
	}

	public function view(){
		$map=array('uid'=>I('id'));
		$result=apiCall(HomePublicApi::Bbjmember_Query, array($map));
		$result1=apiCall(HomePublicApi::UcenterUser_Query, array($maps));
		if($result['info']=='' ||$result['info']==null ){
			$result=apiCall(HomePublicApi::Bbjmember_Seller_Query, array($map));
			$result1=apiCall(HomePublicApi::UcenterUser_Query, array($maps));
			if($result['status']){
				$this->assign('user',$result1['info']);
				$this->assign('entity',$result['info'][0]);
				$this->display();
			}
		}else{
			$this->assign('user',$result1['info']);
			$this->assign('entity',$result['info'][0]);
			$this->display();
		}
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
