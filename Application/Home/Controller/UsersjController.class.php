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
use Home\Api\BbjmemberSellerApi;
use Home\Api\FinAccountBalanceHisApi;
use Home\Api\FinBankaccountApi;
use Think\Controller;
use Home\Api\HomePublicApi;

/*
 * 官网首页
 */
class UsersjController extends SjController {


	/*
	 * 商家中心
	 * 
	 * */
	public function index(){
		$headtitle="宝贝街-商家中心";
		$this->assign('head_title',$headtitle);

		$map=array(
			'uid'=>UID,
		);

		$this->getcount();

        $this->boye_display();
	}



	/*
	 * 用户头像上传
	 * */
	public function avatar(){
        $this->boye_display();
	}
	
	
	/*
	 * 商家账号信息
	 * */
	public function sj_zhxx(){

		$this->assign('head_title',"宝贝街-账号信息");

		$map=array(
			'uid'=>$this->userinfo['uid'],
		);

		$result=apiCall(BbjmemberSellerApi::GET_INFO, array($map));

		$this->assign('entity',$result['info']);

		$this->display();
	}
	/*
	 * 商家基本信息修改
	 * */
	public function edit_base(){
		$id=I('post.id',0);
		$sheng=I('sheng','');
        $shi=I('shi','');
        $qu=I('qu','');
        $address=I('address');

		$entity=array(
			'aliwawa'=>I('aliwawa',''),
			'store_name'=>I('store_name',''),
			'store_url'=>I('store_url',''),
			'address'=>$sheng.$shi.$qu.$address,
            'auth_status'=>3,
		);

		$result=apiCall(BbjmemberSellerApi::SAVE_BY_ID, array($id,$entity));

		if($result['status']){
            $this->reloadUserInfo();
			$this->success('修改成功',U('Home/Usersj/sj_zhxx'));
		}else{
			$this->error($result['info']);
		}
	}

	/*
	 * 商家负责人信息修改
	 * */
	public function edit_fzr(){
		$id=I('id',0);
		$entity=array(
			'task_linkman'=>I('post.rwfzr',''),
			'task_linkman_tel'=>I('post.fzrdh',''),
			'task_linkman_qq'=>I('post.fzrqq',''),
			'waybill_show'=>I('post.ydxs'),
		);
		$result=apiCall(BbjmemberSellerApi::SAVE_BY_ID, array($id,$entity));
		if($result['status']){
            $this->reloadUserInfo();
			$this->success('修改成功',U('Home/Usersj/sj_zhxx'));
		}else{
			$this->error($result['info']);
		}
	}
	/*
	 * 商家负责人联系信息修改
	 * */
	public function edit3(){
		$id=I('id',0);
		$entity=array(
			'linkman'=>I('lxr',''),
			'linkman_tel'=>I('tel',''),
			'linkman_qq'=>I('qq',''),
			'linkman_otherlink'=>I('qt',''),
		);
//		dump($entity);
        $result=apiCall(BbjmemberSellerApi::SAVE_BY_ID, array($id,$entity));
		if($result['status']){
            $this->reloadUserInfo();
			$this->success('修改成功',U('Home/Usersj/sj_zhxx'));
		}else{
			$this->error($result['info']);
		}
	}
	/*
	 * 商家账号安全
	 * */
	public function sj_zhaq(){
		$headtitle="宝贝街-账号安全";
		$this->assign('head_title',$headtitle);
		$user=session('user');
		$this->assign('username',$user['info']['username']);
		$id=$user['info']['id'];
//		dump($map);
		$result=apiCall(HomePublicApi::User_GetUser, array($id));
		$this->assign('entity',$result['info']);

		$this->display();
	}
	/*
	 * 商家邮箱绑定
	 * */
	public function email(){
		$id=I('id','');
		$entity=array('email'=>I('email',''));
//		dump($entity);
		$result=apiCall(HomePublicApi::User_SaveByID, array($id,$entity));
		if($result['status']){
			$this->success('修改成功',U('Home/Usersj/sj_zhaq'));
		}else{
			$this->error($result['info']);
		}
	}
	/*
	 * 商家手机绑定
	 * */
	public function phone(){
		$id=I('id','');
		$entity=array('mobile'=>I('phone',''));
//		dump($entity);
		$result=apiCall(HomePublicApi::User_SaveByID, array($id,$entity));
		if($result['status']){
			$this->success('修改成功',U('Home/Usersj/sj_zhaq'));
		}else{
			$this->error($result['info']);
		}
	}
	/*
	 * 商家密码修改
	 * */
	public function sj_xgmm(){
		if(IS_GET){
			$headtitle="宝贝街-修改密码";
			$this->assign('head_title',$headtitle);
			$user=session('user');
			$this->assign('username',$user['info']['username']);

			$this->display();
		}else{
			$user=session('user');
			$uid=$user['info']['id'];
			$pwd=I('old_password','');
			$data=array('password'=>I('password',''),);
			$result=apiCall(HomePublicApi::User_EditPwd, array($uid,$pwd,$data));
			if($result['status']){
			$this->success('修改成功',U('Home/Usersj/sj_xgmm'));
			}else{
				$this->error('请检查您输入的原密码');
			}
		}
		
	}
	/*
	 * 站内消息
	 * */
	public function sj_znxx(){

		$this->assign('head_title',"宝贝街-站内消息");

		$map=array('to_id'=> UID);

		$page = array('curpage' => I('get.p', 0), 'size' => 10);

		$order =  ' id desc ';
		$result = apiCall(MsgboxApi::QUERY_BY_UID_AND_MSG_STATUS,array(UID,$page,$order));

		$this->assign('list',$result['info']['list']);
		$this->assign('show',$result['info']['show']);


		$this->display();
	}

	public function detail(){

        $msg_id = I('get.msg_id',0);
        $msg_box_id = I('get.msg_box_id',0);
        $this->assign('head_title' , "宝贝街-站内消息");


        $entity = array('msg_status'=>MsgboxModel::READ);

		$result = apiCall(MsgboxApi::SAVE_BY_ID, array($msg_box_id,$entity));

        if(!$result['status']){
            $this->error($result['info']);
        }

        $result = apiCall(MessageApi::GET_INFO,array(array('id'=>$msg_id)));

        if($result['status']){
            $this->assign("msg",$result['info']);
        }

		$this->display();
	}
	/*
	 * VIP开通
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function sj_viptd(){

		$this->assign('head_title',"宝贝街-VIP通道");
        $this->reloadUserInfo();
		$user= $this->userinfo;

		$this -> assign('coins', $user['coins']);
		$this -> assign('vip', $user['vip_level']);
		$this->display();
	}
	/*
	 * 商家课堂
	 * */
	public function sj_sfkt(){
		$headtitle="宝贝街-商家课堂";
		$this->assign('head_title',$headtitle);

		$this->display();
	}
	/*
	 * 商家资金管理
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function sj_zjgl(){

		$this->assign('head_title',"宝贝街-资金管理");

		$uid = UID;
		$map = array('uid' => $uid, );
        $page = array('curpage' => I('get.p', 0), 'size' => 10);
        $order = "create_time desc";

        $result = apiCall(FinAccountBalanceHisApi::QUERY, array($map, $page,$order));
        $this->assign('all',$result['info']['list']);
        $this->assign('show',$result['info']['show']);

        $info = apiCall(FinBankaccountApi::GET_INFO, array($map));

		$this -> assign('bank', $info['info']);

        //充值
        $map['dtree_type'] = 1;
        $result = apiCall(FinAccountBalanceHisApi::QUERY, array($map, $page,$order));
        $this->assign('list_1',$result['info']['list']);
        $this->assign('show_1',$result['info']['show']);
        //
        $map['dtree_type'] = 3;
        $result = apiCall(FinAccountBalanceHisApi::QUERY, array($map, $page,$order));
        $this->assign('list_3',$result['info']['list']);
        $this->assign('show_3',$result['info']['show']);
        $this->reloadUserInfo();

		$this->display();
	}


    /*
     * 获取统计数据
     * */
    public function getcount(){
        $user=session('user');
        $count_wtj=0;$count_ddsh=0;$count_qrhk=0;
        $map=array('uid'=>$user['info']['id']);
        $result=apiCall(HomePublicApi::Task_Query, array($map));
        for ($i=0; $i <count($result['info']) ; $i++) {
            //订单未提交
            $map1=array('task_id'=>$result['info'][$i]['id'],'do_status'=>1);
            $result1[]=apiCall(HomePublicApi::Task_His_Query, array($map1));
            //待订单审核
            $map2=array('task_id'=>$result['info'][$i]['id'],'do_status'=>3);
            $result2[]=apiCall(HomePublicApi::Task_His_Query, array($map2));
            //待确认还款
            $map3=array('task_id'=>$result['info'][$i]['id'],'do_status'=>4);
            $result3[]=apiCall(HomePublicApi::Task_His_Query, array($map3));
            if(count($result1[$i]['info'])!=0){
                $count_wtj=count($result1[$i]['info']);
            }
            if(count($result2[$i]['info'])!=0){
                $count_ddsh=count($result2[$i]['info']);
            }
            if(count($result3[$i]['info'])!=0){
                $count_qrhk=count($result3[$i]['info']);
            }
        }

        $this->assign('wtj',$count_wtj);
        $this->assign('ddsh',$count_ddsh);
        $this->assign('qrhk',$count_qrhk);

    }

	/*
	 * 退出登录
	 * */
	public function exits(){
		session('[destroy]'); // 删除session
		F('wdcount2',NULL);
		$this->display('Index/login');
	}


	
}