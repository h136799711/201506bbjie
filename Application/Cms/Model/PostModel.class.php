<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2015, http://www.gooraye.net. All Rights Reserved.
// |-----------------------------------------------------------------------------------

namespace Cms\Model;
use Think\Model;

class PostModel extends Model{

    /**
     * 文章类型
     * @var array
     */
    const TYPE_NOTICE_FOR_SHOP = "41";

    const TYPE_NOTICE_FOR_NORMAL_MEMBER = "42";

    const TYPE_NOTICE_FOR_ALL = "31";

	protected $_validate = array(
		
   	);
}
