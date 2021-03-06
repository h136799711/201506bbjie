<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 嘟嘟 <99701759@qq.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------


namespace Home\Model;

/**
 * FinBankaccountModel 用户组与用户对应表
 */
class FinAccountBalanceHisModel extends \Think\Model{

    /**
     * 商家-分成所得
     */
    const TYPE_DISTRIBUTOR_GET = 141;

    /**
     * 商家-任务结束时结算的金额
     */
    const TYPE_TASK_OVER_CLEAR = 140;

    /**
     * 购买会员
     */
    const TYPE_BUY_MEMBER = 128;

    /**
     * 单个任务完成时，扣除商家的冻结资金
     */
    const TYPE_TASK_OVER_MINUS_MONEY = 119;

    /**
     * 退还金额给用户
     */
    const TYPE_TASK_OVER_RETURN_TO_USER = 118;

    /**
     * 提现
     */
    const TYPE_WITHDRAW = 87;

    /**
     * 经销商提现
     */
    const TYPE_WITHDRAW_OF_DISTRIBUTOR = 142;

    /**
     * 充值
     */
    const TYPE_RECHARGE = 88;

    /**
     * 创建任务计划冻结资金
     */
    const TYPE_FREEZE_CREATE_TASK_PLAN = "83";

    /**
     * 取消任务计划冻结资金
     */
    const TYPE_UNFREEZE_DELETE_TASK_PLAN = "84";


    /**
     * 审核一个做任务的人 冻结一次资金
     */
    const TYPE_FREEZE_WHEN_PASS_ONE = "85";
    /**
     * 商家-任务结算时返还多余资金
     */
    const TYPE_UNFREEZE_WHEN_TASK_OVER = "86";

    /**
     * 等待审核
     */
    const STATUS_WAIT_CHECK = "2";
    /**
     * 审核驳回
     */
    const STATUS_DENY = "3";

    /**
     * 审核成功
     */
    const STATUS_PASSED = "1";

}