<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY
// |-----------------------------------------------------------------------------------
// | Author: 青 <99701759@qq.com>
// | Copyright (c) 2013-2015, http://www.gooraye.net. All Rights Reserved.
// |-----------------------------------------------------------------------------------

namespace Home\Model;
use Think\Model;

/**
 * AuthGroupAccessModel 用户组与用户对应表
 */
class TaskHisModel extends Model{
//任务状态 ( 2:已完成 3: 待审核订单 4: 确认还款 5: 挂起中 6.待发货 7. 试民收货 8. 已驳回 9.未开始，0: 放弃｜取消)

    /**
     * 挂起Suspend
     */
    const DO_STATUS_SUSPEND = 129;

    /**
     * 已结算
     */
    const DO_STATUS_RETURNED_MONEY = 116;

    /**
     * 等待返款
     */
    const DO_STATUS_WAIT_RETURN = 97;

    /**
     * 已提交订单
     */
    const DO_STATUS_SUBMIT_ORDER = 91;


    /**
     * 订单审核通过
     */
    const DO_STATUS_PASS = 92;

    /**
     * 订单审核失败
     */
    const DO_STATUS_REJECT = 93;

    /**
     * 未开始、刚领取任务
     */
    const DO_STATUS_NOT_START = 90;

    /**
     * 放弃 | 取消
     */
    const DO_STATUS_CANCEL = 96;

    /**
     * 已发货
     */
    const DO_STATUS_DELIVERY_GOODS = 98;

    /**
     * 已收货
     */
    const DO_STATUS_RECEIVED_GOODS = 99;


    //合法支付
    const PAY_TYPE_LEGAL = 101;
    //信用卡
    const PAY_TYPE_CREIDT_CARD = 102;
    //花呗支付
    const PAY_TYPE_HUABEI = 103;


    //订单来源*****************

    /**
     * 主动申请
     */
    const COME_FROM_USER_APPLY = 0;

    /**
     * 商家分配
     */
    const COME_FROM_SELLER_GIVEN = 1;


    protected $_auto = array(
        array('update_time', NOW_TIME, self::MODEL_BOTH),
    );
}