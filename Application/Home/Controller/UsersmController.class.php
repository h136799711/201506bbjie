<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Home\Controller;
use Admin\Api\MessageApi;
use Admin\Api\MsgboxApi;
use Admin\Model\MsgboxModel;
use Cms\Api\VPostInfoApi;
use Home\Api\BbjmemberApi;
use Home\Api\VMsgInfoApi;
use Home\Api\VTaskHisInfoApi;
use Think\Controller;
use Home\Api\HomePublicApi;
use Admin\Api\AdminPublicApi;
/*
 * 试民操作
 */
class UsersmController extends HomeController {

    public function _initialize(){
        parent::_initialize();

        $upload_url = C('SITE_URL').'/index.php/Home/Avatar/upload';
        $this->getLastestNotice();
        $this->not_read_msg_cnt();
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
	 *
	 * */
	public function index() {
		

		$userid = $user['info']['id'];
		//		dump($userid);
		$map = array('uid' => $userid, );
		$result = apiCall(HomePublicApi::Member_Query, array($map));
		$results = apiCall(HomePublicApi::Bbjmember_Query, array($map));
		$this -> assign('info', $results['info']);
		$this -> assign('mum', $result['info'][0]);
		$this -> assign('cs_xx', 'sed');
		$this->posts();
//		dump($result);

		$this -> display('manager_info');
	}
	
	public function email(){
		$id=I('id',0);
		$entity=array('email'=>I('email',''));
//		dump($entity);dump($id);
		$result=apiCall(HomePublicApi::User_SaveByID, array($id,$entity));
		if($result['status']){
			$this->success('修改成功',U('Home/Usersm/sm_aqzx'));
		}else{
			$this->error($result['info']);
		}
	}
	/*
	 * 商家手机绑定
	 * */
	public function phone(){
		$id=I('id',0);
		$entity=array('mobile'=>I('phone',''));
//		dump($entity);
		$result=apiCall(HomePublicApi::User_SaveByID, array($id,$entity));
		if($result['status']){
			$this->success('修改成功',U('Home/Usersm/sm_aqzx'));
		}else{
			$this->error($result['info']);
		}
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
	 * */
	public function sm_bbqz() {

		$this -> assign('head_title', "宝贝街-宝贝钱庄");

		$uid = $user['info']['id'];
		$map = array('uid' => $uid, );
		$maps = array('uid' => $uid, 'dtree_type'=>3);
		$info = apiCall(HomePublicApi::FinBankaccount_Query, array($map));
		$result = apiCall(HomePublicApi::Bbjmember_Query, array($map));
		$user = apiCall(HomePublicApi::User_GetUser, array($uid));
		$result1 = apiCall(HomePublicApi::Bbjmember_Query, array($map));
		$page = array('curpage' => I('get.p', 0), 'size' => 6);
		$jyjl = apiCall(HomePublicApi::FinAccountBalanceHis_QueryAll, array($map, $page));
		$jyjls = apiCall(HomePublicApi::FinAccountBalanceHis_QueryAll, array($maps, $page));
		$all = apiCall(HomePublicApi::FinAccountBalanceHis_Query, array($map));
		$jilus = $all['info'];
		foreach ($jilus as $key => $value) {
			if ($value['dtree_type'] == 3) {
				$sum += $value['defray'];
			}
		}

		$this -> assign('jilu', $jyjl['info']['list']);
		$this -> assign('jilus', $jyjls['info']['list']);
		$this -> assign('sum', $sum);
		$this -> assign('show', $jyjl['info']['show']);
		$this -> assign('shows', $jyjls['info']['show']);
		if($resulta['info'][0]['auth_status']==1){
			$this -> assign('email', $user['info']['email']);
			$this -> assign('phone', $user['info']['mobile']);
		}
		$this -> assign('cs_zj', 'sed');
		$this -> assign('coins', $result['info'][0]['coins']);
		$this -> assign('frozen_money', $result['info'][0]['frozen_money']);
		$this -> assign('bank', $info['info'][0]);


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
		$headtitle = "宝贝街-已邀请的伙伴";
		$this -> assign('head_title', $headtitle);

		$this -> assign('username', $user['info']['username']);
		$map=array('referrer_id'=>$user['info']['id']);
		$result=apiCall(HomePublicApi::Bbjmember_Query,array($map));
		$results=apiCall(HomePublicApi::Bbjmember_Seller_Query,array($map));
		$users=apiCall(HomePublicApi::UcenterUser_Query,array());
		$this->assign('suser',$result['info']);
		$this->assign('sjuser',$results['info']);
		$this->assign('auser',$users['info']);
		$index=A('Index');
		$index->getcount();
		$this->posts();
		$this -> display();
	}
	/*
	 * 收藏活动
	 * */
	public function sm_schd() {
		$headtitle = "宝贝街-收藏活动";
		$this -> assign('head_title', $headtitle);

		$this -> assign('cs_sc', 'sed');
		$this->posts();
		$index=A('Index');
		$index->getcount();
		$this -> assign('username', $user['info']['username']);
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

		$this -> assign('username', $user['info']['username']);
		$this -> display();
	}
	/*
	 * 活动
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function sm_bbhd() {


		$map    = array('uid'=>$this->uid,'do_status'=>1);
		$page   = array('curpage' => I('get.p', 0), 'size' => 5);
		$result = apiCall(VTaskHisInfoApi::QUERY,array($map,$page));

        $his_list = $result['info']['list'];
		for ($i = 0; $i < count($his_list); $i++) {

			$id = $his_list[$i]['task_id'];
			$map = array('task_id' => $id);
            $result = apiCall(VTaskHisInfoApi::GET_INFO, array($map));
            $his_list[$i]['hasList']= $result['info'];

		}

		$this->assign('his_list',$his_list);
		$this->assign('show',$result['info']['show']);

		$this -> display();
	}
	/*
	 * 试民安全资料认证
	 * */
	public function sm_aqzx() {
		$headtitle = "宝贝街-安全中心";
		$this -> assign('head_title', $headtitle);

		$this->posts();
		$this -> assign('username', $user['info']['username']);
		$this -> assign('phone', $user['info']['mobile']);
		$this -> assign('email', $user['info']['email']);
		$this->assign('user',$user['info']);
		$this -> assign('cs_aq', 'sed');

		$this -> display();
	}
	/*
	 * 幸福一点
	 * */
	public function sm_xfyd() {
		$headtitle = "宝贝街-幸福一点";
		$this -> assign('head_title', $headtitle);


		$this -> assign('cs_xf', 'sed');
		$this -> assign('username', $user['info']['username']);
		$this -> display();
	}
	/*
	 * 勋章管理
	 * */
	public function sm_xzgl() {
		$headtitle = "宝贝街-勋章管理";
		$this -> assign('head_title', $headtitle);


		$this -> assign('cs_xz', 'sed');
		$this -> assign('username', $user['info']['username']);
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
	 * */
	public function addbank() {
		$pwd = I('pwd', '');

		$uid = $this->uid;
		//think_ucenter_md5($password, UC_AUTH_KEY)
		$result = apiCall(HomePublicApi::User_GetbyID, array($uid));
		$password = $result['info']['password'];
		$pp = think_ucenter_md5($pwd, UC_AUTH_KEY);
		if ($password == $pp) {
			$entity = array('uid' => $this->uid, 'bank_name' => I('bank', ''), 'bank_account' => I('bank_num', ''), 'create_time' => time(), 'status' => 0, 'notes' => '', 'cardholder' => I('name', ''), 'province' => I('sheng', ''), 'city' => I('shi', ''), );
			$map = array('uid' => $this->uid, );
			$info = apiCall(HomePublicApi::FinBankaccount_Query, array($map));
			if ($info['info'] == null) {
				$add = apiCall(HomePublicApi::FinBankaccount_Add, array($entity));
				$this -> success('绑定成功', U('Home/Usersm/sm_bbqz'));
			} else {
				$id = $info['info'][0]['id'];
				$update = apiCall(HomePublicApi::FinBankaccount_SaveByID, array($id, $entity));
				$this -> success('修改成功', U('Home/Usersm/sm_bbqz'));
			}
		} else {
			$this -> error('登录密码错误！', U('Home/Usersm/sm_bbqz'));
		}
	}
	/*
	 * 试民收货地址管理
	 * */
	public function address() {

		if (IS_GET) {
			$headtitle = "宝贝街-收货地址";
			$this -> assign('head_title', $headtitle);
			$this -> assign('username', $user['info']['username']);

			$uid = $this->uid;
			$map = array('uid' => $uid, );
			$result = apiCall(HomePublicApi::Address_Query, array($map));
			$this -> assign('address', $result['info']);
			$this->posts();
			$this -> assign('cs_dz', 'sed');
			$this -> display('manager_address');
		} else {
			$ars = array('uid' => $this->uid, 'country' => "中国", 'province' => I('sheng'), 'city' => I('shi'), 'area' => I('qu'), 'detail' => I('address', ''), 'contact_name' => I('name', ''), 'mobile' => I('mobile', ''), 'telphone' => I('phone', ''), 'post_code' => I('yb', ''), 'create_time' => time(), );
			$result = apiCall(HomePublicApi::Address_Add, array($ars));

			if ($result['status']) {
				$this -> success("操作成功！", U('Home/Usersm/address'));
			}
		}

	}
	
	 
	/*
	 * 试民资料添加
	 * */
	public function add() {

		$id = $this->uid;
		$year = I('year', 0);
		$month = I('month', 0);
		$day = I('day', 0);
		$bir = $year . '-' . $month . '-' . $day;
		//		dump($bir);
		$sm = array('birthday' => $bir, 'sex' => I('sex', 0), 'qq' => I('qq', '1'), 'realname' => I('realname', ''), );
		$sheng = I('sheng');
		$shi = I('shi');
		$qu = I('qu', '');
		$smm = array('dtree_job' => I('zhiye', ''), 'personal_signature' => I('grqm', ''), 'brief_introduction' => I('grjj', ''), 'address' => $sheng . $shi . $qu . I('address', ''), );
//		dump($smm);
		$result = apiCall(HomePublicApi::Member_SaveByID, array($id, $sm));
		if ($result['status']) {
			$results = apiCall(HomePublicApi::Bbjmember_SaveByID, array($id, $smm));
			if ($results['status']) {
				$this -> success("操作成功！", U('Home/Usersm/index'));
			}
		}
	}
	/*
	 * 试民收货地址修改
	 * */
	public function edit() {
		if (IS_GET) {

			$id = I('id');
			$map = array('id' => $id, );
			$uid = $this->uid;
			$map1 = array('uid' => $uid, );
			$result1 = apiCall(HomePublicApi::Address_Query, array($map1));
			//			dump($result);
			$this -> assign('address', $result1['info']);
			$result = apiCall(HomePublicApi::Address_Query, array($map));
			$this -> assign('addres', $result['info']);
			$this -> display('manager_edit');
		} else {
			$id = I('id', 0);
			$ars = array('country' => "中国", 'province' => I('sheng'), 'city' => I('shi'), 'area' => I('qu'), 'detail' => I('address', ''), 'contact_name' => I('name', ''), 'mobile' => I('mobile', ''), 'telphone' => I('phone', ''), 'post_code' => I('yb', ''), );
			$result = apiCall(HomePublicApi::Address_SaveByID, array($id, $ars));

			if ($result['status']) {
				$this -> success("修改成功！", U('Home/Usersm/address'));
			}
		}

	}
	/*
	 * 试民收货地址删除
	 * */
	public function del() {

		$id = I('id');
		$map = array('id' => $id, );
		//		dump($map);
		$result = apiCall(HomePublicApi::Address_Del, array($map));
		$this -> success("删除成功！", U('Home/Usersm/address'));
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
