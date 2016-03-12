<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------

namespace Admin\Model;
use Think\Model;

class DatatreeModel extends Model{

    /**
     * 银行列表
     */
    const BANK_LIST = "51";

	
	protected $tablePrefix = 'common_';
	 
	 protected $_auto =array(
	 	array('createtime',NOW_TIME,self::MODEL_INSERT),
	 	array('updatetime','time',self::MODEL_BOTH,"function"),
	 );
	
}
