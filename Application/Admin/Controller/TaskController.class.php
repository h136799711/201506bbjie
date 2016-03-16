<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 青<99701759@qq.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Admin\Controller;
use Admin\Api\AuthRuleApi;
use Home\Api\BbjmemberApi;
use Home\Api\FinFucoinHisApi;
use Home\Api\HomePublicApi;
use Admin\Api\AdminPublicApi;
use Home\Api\ProductExchangeApi;
use Home\Api\VProductExchangeInfoApi;
use Home\Model\FinFucoinHisModel;
use Home\Model\ProductExchangeModel;
use Home\Model\VProductExchangeInfoModel;

class TaskController extends AdminController{


	public function index(){


        $status = $this->_param('status',ProductExchangeModel::WAIT_CHECK);
        $params = array(
            'exchange_status'=>$status,
        );
        $map = array(
            'exchange_status'=>$status,
        );
        $page = array('curpage'=>I('get.p',0),'size'=>10);
        $order = "create_time desc";

        $result = apiCall(VProductExchangeInfoApi::QUERY,array($map,$page,$order,$params));

        if($result['status']){
            $this->assign('list',$result['info']['list']);
            $this->assign('show',$result['info']['show']);
        }
        $this->assign('status',$status);

		$this->display();
	}


    public function pass(){
        $id = $this->_param('id','','缺少ID');

        $result = apiCall(ProductExchangeApi::GET_INFO,array(array('id'=>$id)));

        if($result['status'] && is_array($result['info'])){
            $exchange = $result['info'];
            if($exchange['exchange_status'] == ProductExchangeModel::CHECK_SUCCESS){
                $this->error("已经通过!");
            }
        }

        $result = apiCall(ProductExchangeApi::SAVE_BY_ID,array($id,array('exchange_status'=>ProductExchangeModel::CHECK_SUCCESS)));
        if($result['status']){
            $this->success('操作成功!');
        }else{
            $this->success('操作失败!');
        }
    }

    public function reject(){
        $id = $this->_param('id','','缺少ID');
        $reason = $this->_param('reason','没有填原因');
        $result = apiCall(VProductExchangeInfoApi::GET_INFO,array(array('id'=>$id)));
        $exchange = array();
        if($result['status'] && is_array($result['info'])){
            $exchange = $result['info'];
        }

        if($exchange['exchange_status'] == ProductExchangeModel::CHECK_FAIL){
            $this->error("已经驳回!");
        }

        $entity = array(
            'reject_reason'=>$reason,
            'exchange_status'=>ProductExchangeModel::CHECK_FAIL,
        );

        $result = apiCall(ProductExchangeApi::SAVE_BY_ID,array($id,$entity));
        if($result['status']){
            $uid = $exchange['uid'];
            $result = apiCall(BbjmemberApi::GET_INFO,array(array('uid'=>$uid)));
            $left_fucoin = 0;
            if($result['status']){
                $member = $result['info'];
                $left_fucoin = $member['fucoin'];
            }
            $price = number_format($exchange['price']/100.0,0,".","");
            $api = new FinFucoinHisApi();
            $notes = "申请兑换了\"".$exchange['name'].'",被驳回，归还了 '.$price.VIRTUAL_CURRENCY;
            $api->plus($uid,$price,$left_fucoin+$price,FinFucoinHisModel::PLUS_EXCHANGE_REJECT,$notes);

            $this->success('操作成功!');
        }else{
            $this->success('操作失败!');
        }
    }

}