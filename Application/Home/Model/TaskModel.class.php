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
class TaskModel extends \Think\Model{
    /**
     * 平台发货
     */
    const DELIVERY_MODE_PLATFORM = 1;

    /**
     * 商家发货
     */
    const DELIVERY_MODE_SELLER = 2;


    /**
     * 启用
     */
    const STATUS_TYPE_OPEN = "1";

    /**
     * 暂停
     */
    const STATUS_TYPE_PAUSE = "2";

    /**
     * 结算
     */
    const STATUS_TYPE_OVER = "3";

}