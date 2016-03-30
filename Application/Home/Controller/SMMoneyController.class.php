<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 嘟嘟 <99701759@qq.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Home\Controller;
use Home\Api\BbjmemberApi;
use Home\Api\FinAccountBalanceHisApi;
use Home\Api\FinBankaccountApi;
use Home\Model\FinAccountBalanceHisModel;
use Think\Controller;
use Think\Storage;
use Home\Api\HomePublicApi;
/*
 * 资金提现
 */
class SMMoneyController extends HomeController {
	
	/*
	 * 资金提现
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */
	 public function deposit(){
         $money=I('post.money','0.000');
         $money = floatval($money);
         $entity=array(
			'uid'=>$this->uid,
			'defray'=>$money,
			'income'=>'0.00',
			'create_time'=>time(),
			'notes'=>'用于提现',
			'dtree_type'=>FinAccountBalanceHisModel::TYPE_WITHDRAW,
			'status'=>2,
         );


         if($money <= 0){
             $this->error("提现金额不能必须大于0");
         }


         $left_coins = $this->userinfo['coins'] - $money;
         if($left_coins < 0){
             $this->error("提现金额不能大于账户余额");
         }

         $result = apiCall(FinBankaccountApi::GET_INFO,array(array('uid'=>$this->uid)));

         if(!$result['status'] || empty($result['info'])){
             $this->error("请先绑定银行账户!");
         }

         $extra = json_encode($result['info']);
         $entity['extra'] = $extra;


		 $result = apiCall(FinAccountBalanceHisApi::ADD,array($entity));
		 if($result['status']){

            $entity = array(
                'coins'=>$left_coins,
                'frozen_money'=>$this->userinfo['frozen_money'] + $money,
            );

            $return = apiCall(BbjmemberApi::SAVE_BY_ID,array($this->uid,$entity));

            if($return['status']){
                $this->reloadUserInfo();
                $this->success('你的提现请求已经提交，正在审核...',U('Home/Usersm/sm_bbqz'));
            }else{
                $this->error("提交失败!");
            }

		 }
	 }
	 
	
	 
}