<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY
// |-----------------------------------------------------------------------------------
// | Author: 青 <99701759@qq.com>
// | Copyright (c) 2013-2015, http://www.gooraye.net. All Rights Reserved.
// |-----------------------------------------------------------------------------------

namespace Home\Model;

/**
 * TaskLogModel 任务操作日志
 */
class TaskLogModel extends \Think\Model{

    /*
     * 挂起
     */
    const TYPE_SUSPEND_TASK = "130";
    /**
     * 任务结算、还款
     */
    const TYPE_TASK_OVER = "117";

    /**
     * 领取任务
     */
    const TYPE_GET_TASK = "75";

    /**
     * 取消\放弃任务
     */
    const TYPE_CANCEL_TASK = "76";

    /**
     * 提交淘宝订单
     */
    const TYPE_SUBMIT_TB_ORDER = "77";

    /**
     * 商家确认订单
     */
    const TYPE_CONFIRM_ORDER = "114";
    /**
     * 商家驳回订单
     */
    const TYPE_REJECT_ORDER = "115";
    /**
     * 商家发货
     */
    const TYPE_SELLER_DELIVERY = "125";
    /**
     * 平台发货
     */
    const TYPE_PLATFORM_DELIVERY = "126";

}