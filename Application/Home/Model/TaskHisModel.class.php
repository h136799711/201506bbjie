<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY
// |-----------------------------------------------------------------------------------
// | Author: 青 <99701759@qq.com>
// | Copyright (c) 2013-2015, http://www.gooraye.net. All Rights Reserved.
// |-----------------------------------------------------------------------------------

namespace Home\Model;

/**
 * AuthGroupAccessModel 用户组与用户对应表
 */
class TaskHisModel extends \Think\Model{
//任务状态 (1:任务进行中,2:已完成 3: 待审核订单 4: 确认还款 5: 挂起中 6.待发货 7. 试民收获 8. 已驳回 9.未开始，0: 放弃｜取消)
    const DO_STATUS_DOING = 1;
    const DO_STATUS_DONE = 2;
    const DO_STATUS_WAIT_CHECK = 3;
    const DO_STATUS_RETURN_MONEY = 4;

    const DO_STATUS_RESUME = 5;

    /**
     * 待发货
     */
    const DO_STATUS_WAIT_DELIVER = 6;
    /**
     * 收货
     */
    const DO_STATUS_RECEIVED = 7;
    /**
     * 驳回
     */
    const DO_STATUS_REJECT = 8;
    /**
     * 未开始
     */
    const DO_STATUS_NOT_START = 9;
    /**
     * 放弃 | 取消
     */
    const DO_STATUS_CANCEL = 0;
}