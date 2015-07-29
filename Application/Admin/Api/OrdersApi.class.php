<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016 杭州博也网络科技, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------


  namespace Admin\Api;
  use Common\Api\Api;
  use Shop\Api\WxproductApi;
  use Common\Model\OrdersModel;
  use Common\Model\OrderStatusHistoryModel;
  class OrdersApi extends Api{
  	
  	protected function _init(){
  		$this->model = new OrdersModel();
  	}
	 /**
     * 订单确认
     */
    public function confirmOrder($id,$isauto,$uid){
        $orderStatusHistoryModel = new OrderStatusHistoryModel();
        $result = $this->model->where(array('id'=>$id))->find();

        if($result == false){
            return $this->returnErr($this->model->getDbError());
        }

        if(is_null($result)){
            return $this->returnErr("订单ID错误!");
        }

        if($result['order_status'] != OrdersModel::ORDER_TOBE_CONFIRMED){
            return $this->returnErr("当前订单状态无法变更！");
        }

        $entity = array(
            'reason'=>"订单确认操作!",
            'orders_id'=>$result['id'],
            'operator'=>$uid,
            'status_type'=>'ORDER',
            'isauto'=>0,
            'cur_status'=>$result['order_status'],
            'next_status'=> OrdersModel::ORDER_TOBE_SHIPPED,
        );

        $this->model->startTrans();
        $flag = true;
        $return = "";

        $result = $this->model->where(array('id'=>$id))->save(array('order_status'=> OrdersModel::ORDER_TOBE_SHIPPED));
        if($result === false){
            $flag = false;
            $return = $this->model->getDbError();
        }
        if($result == 0){
            $flag = false;
            $return = "订单ID有问题!";
        }

        if($orderStatusHistoryModel->create($entity,1)){
            $result = $orderStatusHistoryModel->add();
            if($result === false){
                $flag = false;
                $return = $orderStatusHistoryModel->getDbError();
            }
        }else{
            $flag = false;
            $return = $orderStatusHistoryModel->getError();
        }



        if($flag){
        	//订单确认库存减少
        	$map=array(
				'orders_id'=>$id,
			);
			$result=apiCall(OrdersItemApi::QUERY_NO_PAGING,array($map));
			//dump($result);
			$count=(int)$result['info'][0]['count'];
			//dump($count);
			
			$map=array(
				'id'=>$result['info'][0]['p_id'],
			);
			$result=apiCall(WxproductApi::SET_DEC,array($map,'quantity',$count));
			
			//dump($result);
			//quantity
			if(!$result['status']){
				$this->model->rollback();
            	return $this->returnErr($result[info]);
			}
            $this->model->commit();
            return $this->returnSuc($return);
        }else{
            $this->model->rollback();
            return $this->returnErr($return);
        }

    }
	/**
	 * 返回错误结构
	 * @return array('status'=>boolean,'info'=>Object)
	 */
	protected function returnErr($info) {
		return array('status' => false, 'info' => $info);
	}
	
	/**
	 * 返回成功结构
	 * @return array('status'=>boolean,'info'=>Object)
	 */
	protected function returnSuc($info) {
		return array('status' => true, 'info' => $info);
	}
	
		
 }
