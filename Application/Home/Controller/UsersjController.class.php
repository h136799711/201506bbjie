<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Home\Controller;
use Think\Controller;
use Think\Storage;
use Home\Api\HomePublicApi;
use Admin\Api\AdminPublicApi;

/*
 * 官网首页
 */
class UsersjController extends CheckLoginController {
	/*
	 * 商家中心
	 * 
	 * */
	public function index(){
		$headtitle="宝贝街-商家中心";
		$this->assign('head_title',$headtitle);
		$user=session('user');
		$id=$user['info']['id'];
		$map=array(
			'uid'=>$id,
		);					
		$order = " post_modified desc ";
		$result = apiCall(AdminPublicApi::Post_QueryNoPaging,array($map, $order));
		$this->assign('info',$result['info']);
		$sj=apiCall(HomePublicApi::Bbjmember_Seller_Query, array($map));
		$this->assign('money',$sj['info'][0]['coins']);
		$this->assign('username',$user['info']['username']);
		$this->assign('sj',$sj['info'][0]);
		$this->assign('head_img',$sj['info'][0]['head_img']);
		$this->checklevel();
		$this->getcount();
		$this->wdcount();
		$this->display();
	}
	/*
	 * 用户头像上传
	 * */
	public function uploadheadimg(){
		$user=session('user');
		$id=$user['info']['id'];
		$entity=array('head_img'=>I('picurl',''));
		$result=apiCall(HomePublicApi::Bbjmember_Seller_SaveByUID, array($id,$entity));
		if($result['status']){
			$this->success('用户头像修改成功',U('Home/Usersj/index'));
		}
	}
	
	
	/*
	 * 商家账号信息
	 * */
	public function sj_zhxx(){
		$headtitle="宝贝街-账号信息";
		$this->assign('head_title',$headtitle);
		$user=session('user');
		$this->assign('username',$user['info']['username']);
		$map=array(
			'uid'=>$user['info']['id'],
		);
		$result=apiCall(HomePublicApi::Bbjmember_Seller_GetInfo, array($map));
		$this->assign('entity',$result['info']);
		$this->wdcount();
		$this->display();
	}
	/*
	 * 商家基本信息修改
	 * */
	public function edit1(){
		$id=I('id',0);
		$sheng=I('sheng','');$shi=I('shi','');$qu=I('qu','');$address=I('address');
		$entity=array(
			'aliwawa'=>I('aliwawa',''),
			'store_name'=>I('store_name',''),
			'store_url'=>I('store_url',''),
			'address'=>$sheng.$shi.$qu.$address,
		);
		$result=apiCall(HomePublicApi::Bbjmember_Seller_SaveByID, array($id,$entity));
		if($result['status']){
			$this->success('修改成功',U('Home/Usersj/sj_zhxx'));
		}else{
			$this->error($result['info']);
		}
	}
	/*
	 * 商家负责人信息修改
	 * */
	public function edit2(){
		$id=I('id',0);
		$entity=array(
			'task_linkman'=>I('rwfzr',''),
			'task_linkman_tel'=>I('fzrdh',''),
			'task_linkman_qq'=>I('fzrqq',''),
			'waybill_show'=>I('ydxs'),
		);
		$result=apiCall(HomePublicApi::Bbjmember_Seller_SaveByID, array($id,$entity));
		if($result['status']){
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
		$result=apiCall(HomePublicApi::Bbjmember_Seller_SaveByID, array($id,$entity));
		if($result['status']){
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
		$this->wdcount();
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
		$headtitle="宝贝街-站内消息";
		$this->assign('head_title',$headtitle);
		$user=session('user');
		$map=array('to_id'=>$user['info']['id'],'msg_status'=>array('neq',2));
		$page = array('curpage' => I('get.p', 0), 'size' => 6);
		$result = apiCall(AdminPublicApi::Msgbox_QueryAll,array($map,$page));
		$result1 = apiCall(AdminPublicApi::Message_Query,array($maps));
		$this->assign('info',$result['info']['list']);
		$this->assign('show',$result['info']['show']);
		$this->assign('msg',$result1['info']);
		$this->assign('username',$user['info']['username']);
		$this->wdcount();
//		dump($result['info']['list']);dump($result1['info']['list']);
		$this->display();
	}
	
	/*
	 * VIP开通
	 * */
	public function sj_viptd(){
		$headtitle="宝贝街-VIP通道";
		$this->assign('head_title',$headtitle);
		$user=session('user');
		$uid = $user['info']['id'];
		$map = array('uid' => $uid, );
		$result = apiCall(HomePublicApi::Bbjmember_Seller_Query, array($map));
		$this -> assign('coins', $result['info'][0]['coins']);
		$this -> assign('vip', $result['info'][0]['vip_level']);
		$this->assign('username',$user['info']['username']);
//		dump($result);
		$this->wdcount();
		$this->display();
	}
	/*
	 * 商家课堂
	 * */
	public function sj_sfkt(){
		$headtitle="宝贝街-商家课堂";
		$this->assign('head_title',$headtitle);
		$user=session('user');
		$this->assign('username',$user['info']['username']);
		$this->wdcount();
		$this->display();
	}
	/*
	 * 商家资金管理
	 * */
	public function sj_zjgl(){
		$headtitle="宝贝街-资金管理";
		$this->assign('head_title',$headtitle);
		$user=session('user');
		$uid = $user['info']['id'];
		$map = array('uid' => $uid, );
		$mapv = array('uid' => $uid,'status'=>2 );
		$info = apiCall(HomePublicApi::FinBankaccount_Query, array($map));
		$result = apiCall(HomePublicApi::Bbjmember_Seller_Query, array($map));
		$user = apiCall(HomePublicApi::User_GetUser, array($uid));
		$page = array('curpage' => I('get.p', 0), 'size' => 6);
		$jyjl = apiCall(HomePublicApi::FinAccountBalanceHis_QueryAll, array($map, $page));
		
		$we=array('uid' => $uid, 'dtree_type'=>1);$where=array('uid' => $uid, 'dtree_type'=>3);
		$chongzhi = apiCall(HomePublicApi::FinAccountBalanceHis_QueryAll, array($we));
		$tixian = apiCall(HomePublicApi::FinAccountBalanceHis_QueryAll, array($where));
		$this->assign('chongzhi',$chongzhi['info']['list']);
		$this->assign('tixian',$tixian['info']['list']);
		$this->assign('all',$jyjl['info']['list']);
		$this -> assign('sum', $sum);
		$this -> assign('show', $jyjl['info']['show']);
		$this -> assign('show2', $tixian['info']['show']);
		$this -> assign('show3', $chongzhi['info']['show']);
		if($result['info'][0]['auth_status']==1){
			$this -> assign('email', $user['info']['email']);
			$this -> assign('phone', $user['info']['mobile']);
		}
		$this -> assign('coins', $result['info'][0]['coins']);
		$this -> assign('djcoins', $result['info'][0]['frozen_money']);
		$this -> assign('bank', $info['info'][0]);
		$this->assign('username',$user['info']['username']);
		$this->wdcount();
		$this->display();
	}
	/*
	 * 退出登录
	 * */
	public function exits(){
		
		session('[destroy]'); // 删除session
		$this->display('Index/login');
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
//dump($count_wtj);
//dump($result2);
//dump($count_qrhk);
		$this->assign('wtj',$count_wtj);
		$this->assign('ddsh',$count_ddsh);
		$this->assign('qrhk',$count_qrhk);
		 
	}
	/*
	 *获得未读消息
	 * */
	 public function wdcount(){
	 	$user=session('user');
		$map=array('to_id'=>$user['info']['id'],'msg_status'=>0);
		$result = apiCall(AdminPublicApi::Msgbox_QueryAll,array($map));
		$this->assign('wdcount',count($result['info']['list']));
	 }
	/*
	 * 检测用户vip等级
	 * */
	public function checklevel(){
		$user=session('user');
		$map=array('uid'=>$user['info']['id']);
		$result=apiCall(HomePublicApi::Bbjmember_Seller_Query,array($map));
		if($result['status']){
			$exp=$result['info'][0]['exp'];
			if($exp<100){
					$this->assign('level',1);
					$this->assign('exp',$exp-0);
				}else if($exp>=100 && $exp<200){
					$this->assign('level',2);
					$this->assign('exp',$exp-100);
				}else if($exp>=200 && $exp<300){
					$this->assign('level',3);
					$this->assign('exp',$exp-200);
				}else if($exp>=300 && $exp<400){
					$this->assign('level',4);
					$this->assign('exp',$exp-300);
				}else if($exp>=400 && $exp<500){
					$this->assign('level',5);
					$this->assign('exp',$exp-400);
				}else if($exp>=500 && $exp<600){
					$this->assign('level',6);
					$this->assign('exp',$exp-500);
				}else if($exp>=600 && $exp<700){
					$this->assign('level',7);
					$this->assign('exp',$exp-600);
				}
		}
	}    
	
}