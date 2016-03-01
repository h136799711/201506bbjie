<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 15/11/30
 * Time: 11:12
 */

namespace Api\Controller;


use Api\Vendor\NetWarehourse\WHCallbackType;
use Api\Vendor\NetWarehourse\WHOrderStatus;
use Api\Vendor\NetWarehourse\WHRequestBase;
use Api\Vendor\NetWarehourse\WHUtils;
use Common\ConstVar\BoyeActionConstModel;
use Library\Api\NetwhUploadHisApi;
use Shop\Api\OrdersExpressApi;
use Shop\Api\OrderStatusApi;
use Shop\Api\ProductApi;
use Shop\Api\ProductAttrApi;
use Shop\Api\ProductSkuApi;
use Shop\Api\StockHisApi;
use Shop\Api\WMSOrderStatusApi;
use Think\Controller\RestController;
use Think\Hook;

class NetWHCallbackController extends RestController {

    private $cfg=array(
        'platform_id'=>730,
        'store_code'=>'BANMA',
        'seller_user_id'=>'BANMA',
        'wms_id'=>'BANMA',
        'key'=>'911446998872267',
    );


    private  $notify_type;
    private  $notify_id;
    private  $notify_time;
    private  $seller_user_id;
    private  $wms_id;
    private  $arr_data;

    /*
     * http://banma.itboye.com/index.php/Api/NetWHCallback/order_status
     */

    /**
     * 3.6 订单状态回传接口
     * 所有的配置
     */
    public function order_status(){

        addLog("NetWHCallback/order_status",$_GET,$_POST,"[调试]3.6 订单状态回传接口",true);

        $sign = I('param.sign','');
        $this->notify_type = I('param.notify_type','');
        $this->notify_id = I('param.notify_id','');
        $this->notify_time = I('param.notify_time','');
        $this->seller_user_id = I('param.seller_user_id','');
        $this->wms_id = I('param.wms_id','');
        $data = I('param.data','');

        addLog("NetWHCallback/order_status", WHUtils::sign($_POST['data'],$this->cfg['key']),'',"[调试]3.6 订单状态回传接口".$this->notify_type,true);

        $data = preg_replace("/\\\\\"/","\"",$data);

        addLog("NetWHCallback/order_status",$this->notify_type,WHUtils::sign($data,$this->cfg['key']),"[调试]3.6 订单状态回传接口".$this->notify_type,true);

        $this->arr_data = json_decode(html_entity_decode($data,ENT_COMPAT),true,512);

		if (empty($sign)) {
            $this->apiReturnErr("sign签名为空!","S1");
            return;
        }


        if(!WHUtils::isValid($sign,$_POST['data'],$this->cfg['key'])){
            $this->apiReturnErr("sign签名不正确!","S2");
            return;
        }

        if(!is_array($this->arr_data)){
            $this->apiReturnErr("data无法解析!","S1");
        }


        $this->addWHLog($this->arr_data['orderCode'],$data,$data,$this->notify_id,$this->notify_type);

        $this->process();

    }

    /**
     * 处理
     */
    protected function process(){

        switch(strtoupper($this->notify_type)){
            //
            case WHCallbackType::WMS_ORDER_STATUS_UPLOAD :

                $this->order_status_upload();

                break;
            //PURCHASE_ORDER_IN_STOCK_CONFIRM
            case WHCallbackType::PURCHASE_ORDER_IN_STOCK_CONFIRM:

                $this->stock_confirm();

                break;
            //
            case WHCallbackType::WMS_ORDER_SHIP_NOTICE:

                $this->ship_notice();

                break;
            default:
                break;
        }



    }

    /**
     * 3.6 订单回传接口
     */
    protected function order_status_upload(){
        addLog("NetWHCallback/order_status_upload",$_GET,$_POST,"[调试] 订单状态回传");

        $content = $this->arr_data['content'];
        $operateDate = $this->arr_data['operateDate'];
        $weight = $this->arr_data['weight'];
        $remark =  $this->arr_data['remark'];
        $status =  $this->arr_data['status'];
        $orderCode =  $this->arr_data['orderCode'];
        $operator =  $this->arr_data['operator'];
        $sellerUserId =  $this->arr_data['sellerUserId'];


        switch(strtoupper($status)){

            case WHOrderStatus::WMS_ACCEPT:
                $tmsOrderCode = $this->arr_data['tmsOrderCode'];
                $tmsServiceCode = $this->arr_data['tmsServiceCode'];
                addLog("NetWHCallback/order_status_upload",$tmsOrderCode,$tmsServiceCode,"[调试] 接单，获取物流信息");
                if(!empty($tmsOrderCode) && !empty($tmsServiceCode)){
                    $result  =apiCall(OrdersExpressApi::SAVE,array(array('order_code'=>$orderCode),array('note'=>'网仓发货，快递公司:'.$tmsServiceCode,'expressno'=>$tmsOrderCode)));
                    //网仓发货
                    $params = array(
                        'order_code'=>$orderCode,
                    );
                    Hook::listen(BoyeActionConstModel::BOYE_ORDER_DELIVER,$params);
                }
                addLog("NetWHCallback/order_status_upload",$_GET,"接单","[调试] 接单");
                $result = apiCall(WMSOrderStatusApi::WMS_ACCEPT,array($orderCode));
                break;
            case WHOrderStatus::WMS_CHECK:
                addLog("NetWHCallback/order_status_upload",$_GET,"接单","[调试] 接单");
                $result = apiCall(WMSOrderStatusApi::WMS_CHECK,array($orderCode));
                break;
            case WHOrderStatus::WMS_FAILED:
                addLog("NetWHCallback/order_status_upload",$_GET,"接单","[调试] 接单失败");
                $result = apiCall(WMSOrderStatusApi::WMS_FAILED,array($orderCode));
                break;
            case WHOrderStatus::WMS_PACKAGE:
                addLog("NetWHCallback/order_status_upload",$_GET,"接单","[调试] 拣单");
                $result = apiCall(WMSOrderStatusApi::WMS_PACKAGE,array($orderCode));
                break;
            case WHOrderStatus::WMS_PICK:
                addLog("NetWHCallback/order_status_upload",$_GET,"接单","[调试] pick");
                $result = apiCall(WMSOrderStatusApi::WMS_PICK,array($orderCode));
                break;
            case WHOrderStatus::WMS_PICK_LACK:
                addLog("NetWHCallback/order_status_upload",$_GET,"接单","[调试] WMS_PICK_LACK");
                $result = apiCall(WMSOrderStatusApi::WMS_PICK_LACK,array($orderCode));
                break;
            case WHOrderStatus::WMS_PRINT:
                addLog("NetWHCallback/order_status_upload",$_GET,"接单","[调试] WMS_PRINT");
                $result = apiCall(WMSOrderStatusApi::WMS_PRINT,array($orderCode));
                break;
            case WHOrderStatus::WMS_REJECT:
                addLog("NetWHCallback/order_status_upload",$_GET,"接单","[调试] WMS_REJECT");
                $result = apiCall(WMSOrderStatusApi::WMS_REJECT,array($orderCode));
                break;
            case WHOrderStatus::WMS_SHIPED:
                addLog("NetWHCallback/order_status_upload",$_GET,"接单","[调试] WMS_SHIPED");
                $result = apiCall(WMSOrderStatusApi::WMS_SHIPPED,array($orderCode));
                break;

            default:
                addLog("NetWHCallback/order_status_upload",$_GET,$status,"[调试] ".$status);

                break;
        }

        $this->apiReturnSuc();
    }

    /**
     * 3.8 入库订单确认
     */
    protected function stock_confirm(){

        $order_code = $this->arr_data['orderCode'];
        $orderItems = $this->arr_data['orderItems'];
        addLog("NetWHCallback/stock_confirm",$order_code,$orderItems,"[调试] 入库订单确认 1");

        foreach($orderItems as $key=>$vo) {

            if($vo['inventoryType'] == 1) {

                $pid = intval($vo['itemId']);
                $cnt = intval($vo['itemQty']);
                $sku_id = $vo['productNo'];
                $tmp =  str_replace($vo['productNo'],"",strval($pid));

                $no_sku =  empty($tmp);

                addLog("NetWHCallback/stock_confirm",$order_code,$sku_id,"[调试] 不是多规格". $no_sku);
                $attr_entity = array(
                    'weight'=>$vo['weight'],//单位克
                    'height'=>$vo['height'],//单位mm
                    'width'=>$vo['width'],//单位mm
                );//{"pid":35,"in_storage_code":"1534210080929303351"},{"real_putin_cnt":1}

//                addLog("NetWHCallback/stock_confirm",$order_code,$cnt,"[调试] 入库订单 入库 数量");
                if($no_sku) {
                    $map = array(array('pid' => $pid, 'in_storage_code' => $order_code), array('real_putin_cnt' => $cnt));
                    $result = apiCall(StockHisApi::SAVE, $map);
//                    addLog("NetWHCallback/stock_confirm",$map,$result,"[调试] 2.入库订单确认 ");
                }else{

                    $map = array(array('sku_id'=>$sku_id,'pid' => $pid, 'in_storage_code' => $order_code), array('real_putin_cnt' => $cnt));

                    $result = apiCall(StockHisApi::SAVE,$map);

                    addLog("NetWHCallback/stock_confirm",$map,$result,"[调试] 2.入库订单确认 ");
                }


                if(!$result['status']){
                    LogRecord($result['info'],__FILE__.__LINE__);
                }

                if($no_sku) {
                    $map = array(array('id'=>$pid), "quantity", $cnt);
                    $result = apiCall(ProductApi::SET_INC,$map);
                }else{
                    $result = apiCall(ProductSkuApi::SET_INC,array(array('product_id'=>$pid,'product_sku'=>$sku_id), "quantity", $cnt));
                }


                if(!$result['status']){
                    LogRecord($result['info'],__FILE__.__LINE__);
                }

                addLog("NetWHCallback/stock_confirm",$order_code,$result,"[调试] 2.入库订单确认 ");

                $result = apiCall(ProductAttrApi::SAVE,array(array('pid'=>$pid),$attr_entity));
                if(!$result['status']){
                    LogRecord($result['info'],__FILE__.__LINE__);
                }




            }
        }


        $this->apiReturnSuc();
    }

    /**
     * 3.7
     */
    protected function ship_notice(){
        addLog("NetWHCallback/ship_notice",$_GET,$_POST,"[调试]3.7 发货订单出库确认接口",true);


        $tmsServiceCode = $this->arr_data['tmsServiceCode'];
        $tmsOrderCode = $this->arr_data['tmsOrderCode'];
        $orderCode =  $this->arr_data['orderCode'];

        $result = apiCall(WMSOrderStatusApi::WMS_SHIPPED,array($orderCode));



        if($result['status']){
            $this->apiReturnSuc();
        }else{
            $this->apiReturnErr($result['info']);
        }

    }



    //******保护方法*****************

    protected function addWHLog($order_code,$upload_content,$return_result,$notify_id,$type){

        if(empty($upload_content)){
            $upload_content = "";
        }

        if(empty($return_result)){
            $return_result = "";
        }

        if(is_array($upload_content)){
            $upload_content = json_encode($upload_content);
        }

        if(is_array($return_result)){
            $return_result = json_encode($return_result);
        }

        $entity = array(
            'notify_id'=>$notify_id,
            'order_code'=>$order_code,
            'upload_time'=>time(),
            'result'=>$return_result,
            'upload_content'=>$upload_content,
            'type'=>$type,
        );

        $result = apiCall(NetwhUploadHisApi::ADD,array($entity));
        if(!$result['status']){
            LogRecord($result['info'],__CLASS__.__FILE__.__LINE__);
        }
    }

    protected function apiReturnSuc(){
        $data['success'] = "true";
        // 返回xml格式数据
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($data));
    }

    protected function apiReturnErr($errorMsg,$errorCode=-1){
        $data['success'] = "false";
        $data['errorCode'] = $errorCode;
        $data['errorMsg'] = $errorMsg;
        // 返回xml格式数据xml_encode
        header('Content-Type:application/json; charset=utf-8');

        addLog("NetWHCallback/order_status",$errorCode,$data,"[调试] 返回结果",true);

        exit(json_encode($data));
    }



}