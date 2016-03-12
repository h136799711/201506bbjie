<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 嘟嘟 <99701759@qq.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Home\Controller;
use Home\Api\BbjmemberSellerApi;
use Home\Api\FinAccountBalanceHisApi;
use Home\Api\FinBankaccountApi;
use Home\Model\BbjmemberSellerModel;
use Home\Model\FinBankaccountModel;
use Think\Controller;
use Home\Api\HomePublicApi;
/*
 * 资金提现
 */
class SJMoneyController extends HomeController {


    protected function _initialize() {
        parent::_initialize();
        $this->checkLogin();
    }

	/*
	 * 资金提现
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	public function deposit() {
		$money = I('money', '0.00');

		$entity = array('uid' => $this->uid,
            'income' => '0',
            'defray' => $money . '.00',
            'create_time' => time(),
            'notes' => '用于提现',
            'dtree_type' => 3,
            'status' =>2
            );

		$result = apiCall(FinAccountBalanceHisApi::ADD, array($entity));

        $entity = array(
            'coins'=>$this->userinfo['coins'] - $money,
            'frozen_money'=>$this->userinfo['frozen_money'] + $money,
        );

        $result = apiCall(BbjmemberSellerApi::SAVE_BY_ID,array($this->uid,$entity));

		if ($result['status']) {
			$this -> success('你的充值请求已经提交，正在审核...', U('Home/Usersj/sj_zjgl'));
		}
	}
	/**
	 * 充值
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 */
	public function recharge(){
        $user = $this->userinfo;
		$entity = array(
            'uid' => $user['uid'],
            'imgurl'=>I('picurl'),
            'income' => I('post.money','').'.000'
            , 'defray' => '0.000',
            'create_time' => time(),
            'notes' => I('post.zhanghao','').'流水号：'.I("post.stnum",''),
            'dtree_type' => 1,
            'status' => 2, );

		$result = apiCall(FinAccountBalanceHisApi::ADD, array($entity));

        if ($result['status']) {
    		$this -> success('你的充值请求已经提交，正在审核...', U('Home/Usersj/sj_zjgl'));
		}else{
            $this->error($result['info']);
        }
	}

	/**
	 * 开通vip
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 */
	public function vip() {
        // 会员价格
        $level = array(
            'vip'=>array(
                '1'=>300, //一个月
                '3'=>810, //3个月
                '6'=>1440,//6个月
                '12'=>2160,//12个月
            ),
            'svip'=>array(
                '1'=>600,
                '3'=>1620,
                '6'=>2880,
                '12'=>4320,
            ),
        );

        $month = $this->_param('month','','请选择充值时长');
        $type = $this->_param('type','','请选择会员类型');
        $money = $level[$type][$month];

        $coin = $this->userinfo['coins'];
        $pwd = $this->_param('pwd','','请输入密码');
        $password = think_ucenter_md5($pwd,UC_AUTH_KEY);
        if($password != $this->userinfo['password']){
            $this->error("登录密码错误");
        }

        $cur_vip_level = $this->userinfo['vip_level'];
        $start_time = time();//会员过期时间

        if($cur_vip_level == BbjmemberSellerModel::VIP_TYPE_SUPER
            && $type == 'vip'){
            $this->error("您当前是超级VIP，不能充值一般VIP");
        }


        $coin = $coin - $money;

        if($coin < 0){
            $this->error('余额不足');
        }else{
            $lv  = 0;
            if($type == 'svip'){
                $lv = 2;
            }elseif($type == 'vip'){
                $lv =  1;
            }

            if($cur_vip_level == $lv && $this->userinfo['vip_expire_time'] > 0){
                $start_time = $this->userinfo['vip_expire_time'];//一般会员，充值 超级会员 ，则过期时间从当前时间算起
            }

            $expire_time = $start_time + ($month * 30 * 24 * 3600);

            $entity = array(
                'vip_level'=>$lv,
                'vip_expire_time'=>$expire_time,
                'coins'=>$coin,
            );

            $result = apiCall(BbjmemberSellerApi::SAVE_BY_ID, array($this->uid, $entity));

            if ($result['status']) {
                $this->reloadUserInfo();

                $entity = array('uid' => $this->uid,
                    'defray' => $money . '.000',
                    'income' => '0.000',
                    'create_time' => time(),
                    'notes' => '用于开通'.$type.'会员'.$month.'个月',
                    'dtree_type' => 4,
                    'status' => 1,
                    );
                apiCall(HomePublicApi::FinAccountBalanceHis_Add, array($entity));
                $this->success("您的服务已经开通",U('Home/Usersj/index'));
            }else{
                $this->error('充值失败');
            }
        }

	}

	/**
	 * 绑定银行卡
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 */
	public function addbank() {

        $pwd = $this->_param('pwd','','请输入密码');
        $password = think_ucenter_md5($pwd,UC_AUTH_KEY);
        if($password != $this->userinfo['password']){
            $this->error("登录密码错误");
        }

        $entity = array('uid' => $this->uid,
            'bank_name' => I('bank', ''),
            'bank_account' => I('bank_num', ''),
            'create_time' => time(),
            'status' => 0,
            'notes' => '',
            'cardholder' => I('name', ''),
            'province' => I('sheng', ''),
            'city' => I('shi', '')
            );

        $map = array('uid' => $this->uid, );

        $result = apiCall(FinBankaccountApi::GET_INFO, array($map));

        if ($result['info'] == null) {
            $result = apiCall(FinBankaccountApi::ADD, array($entity));
        } else {
            $id = $result['info']['id'];
            $result = apiCall(FinBankaccountApi::SAVE_BY_ID, array($id, $entity));
        }
        if($result['status']){
            $this -> success('操作成功', U('Home/Usersj/sj_zjgl'));
	    }else{
            $this -> success('操作失败', U('Home/Usersj/sj_zjgl'));
        }
    }
	

}
