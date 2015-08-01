<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------

namespace Admin\Controller;
use Admin\Api\AdminPublicApi;
use Home\Api\HomePublicApi;

class OrdersController extends AdminController {
	/**
	 * 初始化
	 */
	protected function _initialize() {
		parent::_initialize();
	}
	
	/**
	 * 商家主动退回订单
	 */
	public function backOrder(){
		$id = I('post.orderid',0);
		$reason = I('post.reason','商家主动取消订单');
		
		$result = apiCall("Admin/Orders/getInfo", array(array('id'=>$id)));
		
		if(!$result['status']){
			$this->error($result['status']);
		}
		
		if(is_null($result)){
			$this->error("订单信息获取失败，请重试！");
		}
		$wxuserid = $result['info']['wxuser_id'];
		$orderid = $result['info']['orderid'];
		$cur_status = $result['info']['order_status'];

		//检测当前订单状态是否合法
		if($cur_status != 2){
			$this->error("当前订单状态无法变更！");
		}
		
		//
		$result = apiCall("Admin/Orders/backOrder", array($id,$reason,false,UID));
		
		if(!$result['status']){
			$this->error($result['info']);
		}
		$text = "您的订单:".$orderid." [被退回],原因:".$reason.". [查看详情]";
		$orderViewLink = "<a href=\"".C('SITE_URL').U('Shop/Orders/view')."?id=$id\">$text</a>";
		$this->sendTextTo($wxuserid,$orderViewLink);
		$this->success("退回成功!");
		
		
	}
	
	/**
	 * 统计
	 */
	public function statics(){
		
		$this->display();
	}

	/**
	 * 订单管理
	 */
	public function index() {
		$arr = getDataRange(3);
		$payStatus = I('paystatus', '');
		$orderStatus = I('orderstatus', '');
		$orderid = I('post.orderid', '');
		$userid = I('uid', 0);
		$startdatetime = urldecode($arr[0]);
		//I('startdatetime', , 'urldecode');
		$enddatetime = urldecode($arr[1]);
		//I('enddatetime',   , 'urldecode');

		//分页时带参数get参数
		$params = array('startdatetime' => $startdatetime, 'enddatetime' => ($enddatetime),'wxaccountid'=>getWxAccountID());

		$startdatetime = strtotime($startdatetime);
		$enddatetime = strtotime($enddatetime);

		if ($startdatetime === FALSE || $enddatetime === FALSE) {
			LogRecord('INFO:' . $result['info'], '[FILE] ' . __FILE__ . ' [LINE] ' . __LINE__);
			$this -> error(L('ERR_DATE_INVALID'));
		}

		$map = array();

		if (!empty($orderid)) {
			$map['tb_orderid'] = array('like', $orderid . '%');

		}
		
		if ($orderStatus != '') {
			$map['order_status'] = $orderStatus;
			$params['order_status'] = $orderStatus;
		}
		$map['create_time'] = array( array('EGT', $startdatetime), array('elt', $enddatetime), 'and');

		$page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
		$order = " create_time desc ";

		if ($userid > 0) {
			$map['uid'] = $userid;
		}
		//		$result = apiCall("Admin/Wxuser/queryNoPaging", array(array(),false,"id,nickname,avatar") );
		//		if($result['status']){
		//			$this->assign("users",$result['info']);
		//		}

		//
		$result = apiCall(HomePublicApi::Task_His_QueryAll, array($map, $page, $order, $params));
		//
		if ($result['status']) {
			$this -> assign('orderid', $orderid);
			$this -> assign('orderStatus', $orderStatus);
			$this -> assign('payStatus', $payStatus);
			$this -> assign('startdatetime', $startdatetime);
			$this -> assign('enddatetime', $enddatetime);
			$this -> assign('show', $result['info']['show']);
			$this -> assign('list', $result['info']['list']);
			$this -> display();
		} else {
			LogRecord('INFO:' . $result['info'], '[FILE] ' . __FILE__ . ' [LINE] ' . __LINE__);
			$this -> error($result['info']);
		}
	}

	/**
	 * 订单确认
	 */
	public function sure() {
		$orderid = I('orderid', '');
		$userid = I('uid', 0);
		$params = array();
		$map = array();
		$map['order_status'] = \Common\Model\OrdersModel::ORDER_TOBE_CONFIRMED;
		$page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
		$order = " create_time desc ";

		if (!empty($orderid)) {
			$map['orderid'] = array('like', $orderid . '%');
			$params['orderid'] = $orderid;
		}
		if ($userid > 0) {
			$map['uid'] = $userid;
			$params['uid'] = $userid;
		}
		$result = apiCall(HomePublicApi::Task_His_QueryAll, array($map, $page, $order, $params));
//		dump($map);dump($params);dump($order);
		//
		if ($result['status']) {
			$this -> assign('orderid', $orderid);
			$this -> assign('payStatus', $payStatus);
			$this -> assign('orderStatus', $orderStatus);
			$this -> assign('show', $result['info']['show']);
			$this -> assign('list', $result['info']['list']);
//			dump($result);
			$this -> display();
		} else {
			LogRecord('INFO:' . $result['info'], '[FILE] ' . __FILE__ . ' [LINE] ' . __LINE__);
			$this -> error($result['info']);
		}
	}

	/**
	 * 发货
	 */
	public function deliverGoods() {
		$orderStatus = I('orderstatus','3');
		$orderid = I('orderid', '');
		$userid = I('uid', 0);
		$params = array();

		$map = array();
		$params['wxaccountid'] = $map['wxaccountid'];
		if (!empty($orderid)) {
			$map['orderid'] = array('like', $orderid . '%');
			$params['orderid'] = $orderid;
		}
		//		if($payStatus != ''){
		//			$map['pay_status'] = $payStatus;
		//		}
		if($orderStatus != ''){
			$map['order_status'] = $orderStatus;
			$params['order_status'] = $orderStatus;
		}
//		$map['order_status'] = \Common\Model\OrdersModel::ORDER_TOBE_SHIPPED;
		//		$map['createtime'] = array( array('EGT', $startdatetime), array('elt', $enddatetime), 'and');
		
		$page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
		$order = " create_time desc ";

		if ($userid > 0) {
			$map['uid'] = $userid;
		}

		//
			$result = apiCall(HomePublicApi::Task_His_QueryAll, array($map, $page, $order, $params));

		//
		if ($result['status']) {
			$this -> assign('orderid', $orderid);
			$this -> assign('orderStatus', $orderStatus);
			$this -> assign('show', $result['info']['show']);
			$this -> assign('list', $result['info']['list']);
			$this -> display();
		} else {
			LogRecord('INFO:' . $result['info'], '[FILE] ' . __FILE__ . ' [LINE] ' . __LINE__);
			$this -> error($result['info']);
		}
	}

	/**
	 * 查看
	 */
	public function view() {
		
//	
		$id = I('get.orderid', 0);
		if($id !=0){
			$map = array('tb_orderid' => $id);
			$result = apiCall(HomePublicApi::Task_His_Query, array($map));
			$maps = array('orderid' => $id);
			$result1 = apiCall(HomePublicApi::ExchangeProduct_Query, array($maps));
			$this->assign('taskhis',$result['info'][0]);	
			$pid=array('id'=>$result1['info'][0]['p_id']);
			$result2 = apiCall(AdminPublicApi::Wxproduct_QueryNoPaging, array($pid));
			$this->assign('product',$result2['info']);
			
		}
		$this->display();
		
	}

	/**
	 * 单个发货操作
	 */
	public function deliver() {
		$expresslist = C("CFG_EXPRESS");
		//dump($expresslist);
		
		if (IS_GET) {
			$id = I('get.id',0);
			$map = array('id'=>$id);
			$result = apiCall(HomePublicApi::Task_His_Query, array($map));
			if($result['status']){
				$this->assign("order",$result['info'][0]);
			}else{
				$this->error("订单信息获取失败！");
			}
			
			$map = array('orderid'=>$result['info'][0]['tb_orderid']);
			$result1 = apiCall("Admin/OrdersExpress/getInfo", array($map));
			if($result1['status'] && is_array($result1['info'])){
				$this->assign("express",$result1['info']);
			}
//			dump($result1);
			$this->assign("expresslist",$expresslist);
			$this->display();
		} elseif (IS_POST) {
			$expresscode = I('post.expresscode','');
			$expressno = I('post.expressno','');
			$wxuserid = I('post.wxuserid',0);
			$orderid = I('post.orderid','');
			$orderOfid = I('post.orderOfid','');
			if(empty($expresscode) || !isset($expresslist[$expresscode])){
				$this->error("快递信息错误！");
			}
			if(empty($expressno)){
				$this->error("快递单号不能为空");
			}
			$id = I('post.id',0);
			$entity = array(
				  'expresscode'=>$expresscode,
				  'expressname'=>$expresslist[$expresscode],
				  'expressno'=>$expressno,
				  'note'=>I('post.note',''),
				  'orderid'=>$orderid,
				  'wxuserid'=>$wxuserid,orderOfid
			);
			
			if(empty($entity['orderid'])){
				$this->error("订单编号不能为空");
			}
//			dump($entity);
			if(empty($id) || $id <= 0){
				$result = apiCall("Admin/OrdersExpress/add", array($entity));
			}else{
				$result = apiCall("Admin/OrdersExpress/saveByID", array($id,$entity));
			}
			
			
			if($result['status']){
				$od=array('order_status'=>4,'do_status'=>7);
				$resulta = apiCall(HomePublicApi::Task_His_SaveByID, array($orderOfid,$od));
//				dump($od);
				if($resulta['status']){
					$this->success('发货信息添加完成',U('Admin/Orders/deliverGoods'));
				}
//				
			}else{
				$this->error($result['info']);
			} 
		}
	}
	

	/**
	 * 退货管理
	 */
	public function returned() {
//		if (IS_GET) {
			$orderid = I('orderid', '');
			$userid = I('uid', 0);
			$orderStatus = I('orderstatus',\Common\Model\OrdersModel::ORDER_RECEIPT_OF_GOODS);
			$params = array();
			$map = array();
			if (!empty($orderid)) {
				$map['orderid'] = array('like', $orderid . '%');
				$params['orderid'] = $orderid;
			}
			$map['wxaccountid'] = getWxAccountID();
			$map['order_status'] = $orderStatus;
			$map['pay_status'] = \Common\Model\OrdersModel::ORDER_PAID;
			
			$params['order_status'] = $orderStatus;
			$page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
			$order = " createtime desc ";

			if ($userid > 0) {
				$map['wxuser_id'] = $userid;
			}

			//
			$result = apiCall('Shop/OrdersInfoView/query', array($map, $page, $order, $params));

			//
			if ($result['status']) {
				$this -> assign('orderid', $orderid);
				$this -> assign('orderStatus', $orderStatus);
				$this -> assign('show', $result['info']['show']);
				$this -> assign('list', $result['info']['list']);
				$this -> display();
			} else {
				LogRecord('INFO:' . $result['info'], '[FILE] ' . __FILE__ . ' [LINE] ' . __LINE__);
				$this -> error($result['info']);
			}
//		} elseif (IS_POST) {
//			
//		}
	}
	
	/**
	 * 单个退货操作
	 */
	public function returnGoods(){
		if(IS_GET){
			$id = I('get.id',0);
			$this->assign("id",$id);
			$this->display();
		}elseif(IS_POST){
			$id = I('post.id',0,'intval');

//			$entity = array('order_status'=>\Common\Model\OrdersModel::ORDER_RETURNED,'status_note'=>'|[退货]'.I('post.note',''));
//			$result = apiCall("Admin/Orders/saveByID",array($id,$entity) );
			
			$result = serviceCall("Common/Order/returned", array($id,false,UID));
			
			if($result['status']){
				$this->success("操作成功！",U('Admin/Orders/returned'));
			}else{
				$this->error($result['info']);
			}
		}
	}

	
	/**
	 * 批量发货
	 * TODO:批量发货
	 */
	public function bulkDeliver(){
		$this->error("功能开发中...");
	}

	/**
	 * 批量确认订单
	 */
	public function bulkSure() {
		if (IS_POST) {

			$ids = I('post.ids', -1);
			if ($ids === -1) {
				$this -> error(L('ERR_PARAMETERS'));
			}
			
//			$ids = implode(',', $ids);
//			$map = array('id' => array('in', $ids));
//			$entity = array('order_status' => \Common\Model\OrdersModel::ORDER_TOBE_SHIPPED);
//			$result = apiCall("Admin/Orders/save", array($map, $entity));
			
			$map=array('order_status'=>3,'do_status'=>7);
			foreach($ids as $id){
				$result = apiCall(HomePublicApi::Task_His_SaveByID, array($id,$map));
				if (!$result['status']) {
					$this -> error($result['info']);
				}
			}
			
			
			if ($result['status']) {
				$this -> success(L('RESULT_SUCCESS'), U('Admin/Orders/sure'));
			} else {
				
				$this -> error($result['info']);
			}
		}
	}


	private function sendTextTo($wxuserid,$text){
		//
		$wxaccountid = getWxAccountID();
		$result = apiCall("Admin/Wxuser/getInfo",array(array("id"=>$wxuserid)));
		$wxaccount = apiCall("Admin/Wxaccount/getInfo", array(array("id"=>$wxaccountid)));
		$openid = "";
		if($result['status'] && is_array($result['info'])){
			$openid = $result['info']['openid'];
		}
		if($wxaccount['status'] && is_array($wxaccount['info'])){
			$appid =  $wxaccount['info']['appid'];
			$appsecret =  $wxaccount['info']['appsecret'];				
			$wxapi = new \Common\Api\WeixinApi($appid,$appsecret);
			$wxapi->sendTextToFans($openid, $text);
			$wxapi->sendTextToFans($openid, $text);//发2次
		}
	}

}
