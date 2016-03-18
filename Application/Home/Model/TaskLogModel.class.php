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

}