<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 16/3/15
 * Time: 17:19
 */

namespace Home\Model;


use Think\Model;

class FinFucoinHisModel extends Model {

    /**
     * 减少-兑换商品
     */
    const MINUS_EXCHANGE = 111;

    /**
     * 增加-完成任务
     */
    const PLUS_COMPLETE_TASK = 112;

    /**
     * 增加-兑换商品驳回
     */
    const PLUS_EXCHANGE_REJECT = 113;

    /**
     * 增加-兑换商品未取走
     */
    const PLUS_EXCHANGE_RETURN = 131;

}