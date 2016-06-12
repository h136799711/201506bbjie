<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2015, http://www.gooraye.net. All Rights Reserved.
// |-----------------------------------------------------------------------------------

namespace Home\Model;

/**
 * AuthGroupAccessModel 用户组与用户对应表
 */
class BbjmemberSellerModel extends \Think\Model{

    /**
     * 试民自主领取
     */
    const SELF_GET = 1;

    /**
     * 商家选择
     */
    const SELLER_SELECT =  2;

    /**
     * 非会员
     */
    const VIP_TYPE_NONE = 0;
    /**
     * 一般VIP等级
     */
    const VIP_TYPE_NORMAL = 1;
    /**
     * 超级VIP等级
     */
    const VIP_TYPE_SUPER = 2;

    /*
     * 已通过审核状态
     */
    const AUTH_PASS = 1;

    /**
     * 待审核中
     */
    const AUTH_WAIT = 0;

    /**
     * 审核拒绝状态
     */
    const AUTH_DENY = 2;

}