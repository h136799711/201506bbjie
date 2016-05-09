<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY
// |-----------------------------------------------------------------------------------
// | Author: 青 <99701759@qq.com>
// | Copyright (c) 2013-2015, http://www.gooraye.net. All Rights Reserved.
// |-----------------------------------------------------------------------------------

namespace Home\Model;

/**
 * 兑换商品表
 */
class ProductExchangeModel extends \Think\Model{

    // 等待审核
    const WAIT_CHECK = 0;
    // 审核通过
    const CHECK_SUCCESS = 1;
    // 审核失败
    const CHECK_FAIL = 2;
    // 已发货
    const DELIVERY_GOODS = 3;
    // 已分配任务
    const ALLOC_TASK = 4;


}