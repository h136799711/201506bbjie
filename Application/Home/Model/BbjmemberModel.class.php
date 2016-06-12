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
class BbjmemberModel extends \Think\Model{

    /*
     * 任务开启状态
     */
    const TASK_OPEN = 1;

    /**
     * 任务关闭状态
     */
    const TASK_CLOSE = 0;

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