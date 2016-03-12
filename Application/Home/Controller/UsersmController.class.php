<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Home\Controller;
use Admin\Api\DatatreeApi;
use Admin\Api\MemberApi;
use Admin\Api\MessageApi;
use Admin\Api\MsgboxApi;
use Admin\Model\DatatreeModel;
use Admin\Model\MsgboxModel;
use Cms\Api\VPostInfoApi;
use Home\Api\AddressApi;
use Home\Api\BbjmemberApi;
use Home\Api\FinAccountBalanceHisApi;
use Home\Api\FinBankaccountApi;
use Home\Api\TaskHisApi;
use Home\Api\VBbjmemberSellerInfoApi;
use Home\Api\VMsgInfoApi;
use Home\Api\VTaskHisInfoApi;
use Home\Api\VTaskProductInfoApi;
use Home\Model\FinAccountBalanceHisModel;
use Home\Model\TaskHisModel;
use Think\Controller;
use Home\Api\HomePublicApi;
use Admin\Api\AdminPublicApi;
/*
 * 试民操作
 */
class UsersmController extends HomeController {

    public function _initialize(){
        parent::_initialize();
        $this->checkLogin();
        $upload_url = C('SITE_URL').'/index.php/Home/Avatar/upload';
        $this->getLastestNotice();
        $this->not_read_msg_cnt();
        $this->get_doing_task_cnt();

        $this->assign("upload_url",$upload_url);
    }

    /*
     * 用户头像上传
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     * */
    public function avatar(){
        $this->boye_display();
    }

	/*
	 * 试民资料
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function index() {

        $result = apiCall(DatatreeApi::QUERY_NO_PAGING,array(array('parentid'=>'46')));

        if($result['status']){
            $this->assign("job_list",$result['info']);
        }

		$this -> display('manager_info');
	}

	/*
	 * 试民任务设置
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function manager_rw() {


		$this -> assign('head_title', "试民中心-任务");

		$this -> display();
	}
	

	/*
	 * 试民钱庄
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function sm_bbqz() {

        $this->reloadUserInfo();
		$this -> assign('head_title', "宝贝街-宝贝钱庄");

        //获取绑定的提现银行帐号信息
		$uid = $this->uid;
		$map = array('uid' => $uid, );
        $result = apiCall(FinBankaccountApi::GET_INFO, array($map));

        $this->assign("bank",$result['info']);

        $result = apiCall(DatatreeApi::QUERY_NO_PAGING,array(array('parentid'=>DatatreeModel::BANK_LIST)));

        $this->assign("bank_list",$result['info']);


        //收支明细

        $result = apiCall(FinAccountBalanceHisApi::QUERY,array($map));

        $this->assign("detail_list",$result['info']['list']);
        $this->assign("detail_show",$result['info']['show']);


        //提现明细
        $map['dtree_type'] = FinAccountBalanceHisModel::TYPE_WITHDRAW;
        $result = apiCall(FinAccountBalanceHisApi::QUERY,array($map));

        $this->assign("withdraw_list",$result['info']['list']);
        $this->assign("withdraw_show",$result['info']['show']);


		$this -> display();
	}
	/*
	 * 设置淘宝账号
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function settaobao(){

		$entity=array('taobao_account'=>I('taobao','无'));

		$result = apiCall(BbjmemberApi::SAVE_BY_ID,array($this->uid,$entity));
		if($result['status']){
			$this->success('绑定成功',U('Home/Index/sm_manager'));
		}else{
            $this->error("绑定失败");
        }
		
	}

	/*
	 * 预定商品
	 * */
	public function sm_ydsp() {
		$headtitle = "宝贝街-预定商品";
		$this -> assign('head_title', $headtitle);

		$this -> assign('username', $user['info']['username']);
		$this -> assign('cs_yd', 'sed');

		$this -> display();
	}
	/*
	 * 因祸得福
	 * */
	public function sm_yhdf() {
		$headtitle = "宝贝街-因祸得福";
		$this -> assign('head_title', $headtitle);

		$this -> assign('username', $user['info']['username']);
		$map=array('referrer_id'=>$user['info']['id']);
		$result=apiCall(HomePublicApi::Bbjmember_Query,array($map));
		$results=apiCall(HomePublicApi::Bbjmember_Seller_Query,array($map));
		$this->assign('smcount',count($result['info']));
		$this->assign('sjcount',count($results['info']));

		$this -> display();
	}
	/*
	 * 已邀请的小伙伴
	 * */
	public function sm_sfkt() {

		$this -> assign('head_title', "宝贝街-已邀请的伙伴");

		$map=array('referrer_id'=>$this->uid);
		$result=apiCall(HomePublicApi::Bbjmember_Query,array($map));
		$results=apiCall(HomePublicApi::Bbjmember_Seller_Query,array($map));
		$users=apiCall(HomePublicApi::UcenterUser_Query,array());
		$this->assign('suser',$result['info']);
		$this->assign('sjuser',$results['info']);
		$this->assign('auser',$users['info']);
		$this -> display();
	}
	/*
	 * 收藏活动
	 * */
	public function sm_schd() {

		$this -> assign('head_title', "宝贝街-收藏活动");

		$this -> display();
		
	}
	/*
	 * 兑换商品
	 * */
	public function sm_dhsp() {

		$this -> assign('head_title', "宝贝街-兑换商品");

		$map=array('uid'=>$user['info']['id']);
		$re=apiCall(HomePublicApi::ExchangeProduct_Query,array($map));
		$result=apiCall(AdminPublicApi::Wxproduct_QueryNoPaging,array($map));
		$this->assign('product',$result['info']);
		$this->assign('exchange',$re['info']);

		$this -> display();
	}


	/*
	 * 幸福一点
	 * */
	public function sm_xfyd() {
		$this -> assign('head_title', "宝贝街-幸福一点");

		$this -> display();
	}

	/*
	 * 站内消息
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function sm_znxx() {

		$this -> assign('head_title', "宝贝街-站内消息");

		$map = array('to_id'=>$this->uid,'msg_status'=>array('neq',2));
		$page = array('curpage' => I('get.p', 0), 'size' => 6);

		$result = apiCall(VMsgInfoApi::QUERY , array($map,$page));

		$this->assign('info',$result['info']['list']);
		$this->assign('show',$result['info']['show']);

		$this -> display();
	}

    /**
     * 详情
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     */
	public function detail(){

		$msg_id = I('get.msg_id',0);

		$this -> assign('head_title', "宝贝街-站内消息");

		$map=array('msg_status'=>1);

		$result = apiCall(MsgboxApi::SAVE_BY_ID, array($msg_id,$map));

        $result = apiCall(MessageApi::GET_INFO, array(array('id'=>$msg_id)));

		$this->assign("msg",$result['info']);
		$this->display();
	}

	/*
	 * 试民添加银行卡信息
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function addbank() {
		$pwd = I('post.pwd', '');

		$uid = $this->uid;

		$password = $this->userinfo['password'];
		$pp = think_ucenter_md5($pwd, UC_AUTH_KEY);
		if ($password == $pp) {
			$entity = array('uid' => $this->uid, 'bank_name' => I('bank', ''), 'bank_account' => I('bank_num', ''), 'create_time' => time(), 'status' => 0, 'notes' => '', 'cardholder' => I('name', ''), 'province' => I('sheng', ''), 'city' => I('shi', ''), );
			$map = array('uid' => $this->uid, );
			$result = apiCall(FinBankaccountApi::GET_INFO, array($map));
			if ($result['info'] == null) {
				$add = apiCall(FinBankaccountApi::ADD, array($entity));
				$this -> success('绑定成功', U('Home/Usersm/sm_bbqz'));
			} else {
				$id = $result['info']['id'];
				$update = apiCall(FinBankaccountApi::SAVE_BY_ID, array($id, $entity));
				$this -> success('修改成功', U('Home/Usersm/sm_bbqz'));
			}
		} else {
			$this -> error('登录密码错误！', U('Home/Usersm/sm_bbqz'));
		}
	}

	/*
	 * 试民收货地址管理
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function address() {

		if (IS_GET) {

			$this -> assign('head_title', "宝贝街-收货地址");
			$uid = $this->uid;
			$map = array('uid' => $uid, );
			$result = apiCall(AddressApi::QUERY, array($map));

            $this -> assign('list', $result['info']['list']);
            $this -> assign('show', $result['info']['show']);

			$this -> display('manager_address');
		} else {
			$entity = array(
                'uid' => $this->uid,
                'country' => "中国",
                'province' => I('post.province',''),
                'city' => I('post.city',''),
                'area' => I('post.area',''),
                'detail' => I('post.address', ''),
                'contact_name' => I('post.name', ''),
                'mobile' => I('post.mobile', ''),
                'telphone' => I('post.phone', ''),
                'post_code' => I('post.yb', ''),
                'create_time' => time(), );
			$result = apiCall(AddressApi::ADD, array($entity));

			if ($result['status']) {
				$this -> success("操作成功！", U('Home/Usersm/address'));
			}
		}

	}
	/*
	 * 试民资料添加/更新
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function update() {

		$id = $this->uid;


		$sm = array(
            'sex' => I('post.sex', 0),
            'qq' => I('post.qq', '1'),
            'realname' => I('post.realname', ''),
            );

		$result = apiCall(MemberApi::SAVE_BY_ID, array($id, $sm));
		if ($result['status']) {

            $entity = array(
                'dtree_job'=>I('post.job',''),
                'personal_signature'=>I('post.personal_signature',''),
                'brief_introduction'=>I('post.brief_introduction',''),
                'contact_tel'=>I('post.tel',''),
            );
            $result = apiCall(BbjmemberApi::SAVE_BY_ID,array($id,$entity));

            if ($result['status']) {
                $this->reloadUserInfo();
                $this->success("操作成功！", U('Home/Usersm/index'));
            }
		}

        $this->error("操作失败!");
	}
	/*
	 * 试民收货地址修改
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function edit() {

        $id = I('get.id',0);

		if (IS_GET) {


            $map = array('uid' => $this->uid);
            $result = apiCall(AddressApi::QUERY, array($map));

            $this -> assign('list', $result['info']['list']);
            $this -> assign('show', $result['info']['show']);

			$result = apiCall(AddressApi::GET_INFO, array(array('id'=>$id)));

            if($result['status']){
                $this->assign("address",$result['info']);
            }

			$this -> display('manager_edit');
		} else {

            $entity = array(
                'country' => "中国",
                'province' => I('post.province',''),
                'city' => I('post.city',''),
                'area' => I('post.area',''),
                'detail' => I('post.address', ''),
                'contact_name' => I('post.name', ''),
                'mobile' => I('post.mobile', ''),
                'telphone' => I('post.phone', ''),
                'post_code' => I('post.yb', '')
            );

            $result = apiCall(AddressApi::SAVE_BY_ID, array($id,$entity));

            if ($result['status']) {
                $this -> success("操作成功！", U('Home/Usersm/address'));
            }else{
                $this -> error("操作失败! ");
            }
		}

	}
	/*
	 * 试民收货地址删除
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function del() {

		$id = I('get.id');
		$map = array('id' => $id, );
		$result = apiCall(AddressApi::DELETE, array($map));
        if($result['status']){
		    $this -> success("删除成功！", U('Home/Usersm/address'));
	    }else{
            $this->error($result['info']);
        }
    }
	/*
	 * 计时器改变任务状态
	 * */
	public function tasktimeover(){
		$id=I('id');
		$entity=array('do_status'=>0);
		$result=apiCall(HomePublicApi::Task_His_SaveByID,array($id,$entity));
		if($result['status']){
			$this->success('时间超时，由系统取消',U('Home/Usersm/sm_bbhd'));
		}
	}

    /**
     * 获取试民正在进行的任务数
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     */
    private function get_doing_task_cnt(){

        $map = array('uid'=>$this->uid);
        $map['do_status'] = array('not in',array(TaskHisModel::DO_STATUS_CANCEL,TaskHisModel::DO_STATUS_DONE));
        $result = apiCall(TaskHisApi::COUNT,array($map));
        if($result['status']){
            $this->assign('doing_task',$result['info']);
        }else{
            $this->assign('doing_task',0);
        }
    }


    /**
     * 未读消息
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     */
    private function not_read_msg_cnt(){

        $result = apiCall(VMsgInfoApi::COUNT,array(array('to_id'=>$this->uid,'msg_status'=>MsgboxModel::NOT_READ)));
        $not_read_msg_cnt =  $result['info'];
        if(empty($not_read_msg_cnt)){
            $not_read_msg_cnt = 0;
        }

        $this->assign('not_read_msg_cnt',$not_read_msg_cnt);


    }

    /**
     * 获取最新公告信息
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     */
    private function getLastestNotice(){
        $map = array();
        $order = " post_modified desc ";

        $result = apiCall(VPostInfoApi::GET_INFO,array($map, $order));
        $this->assign('zxgg',$result['info']);
    }

}
