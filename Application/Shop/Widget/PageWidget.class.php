<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2015, http://www.gooraye.net. All Rights Reserved.
// |-----------------------------------------------------------------------------------

namespace Shop\Widget;
use Think\Controller;


/*
 * 管理导航菜单
 * */
class PageWidget  extends Controller {
	
	/**
	 * 顶部
	 */
	public function head() {
		echo $this->fetch("partials:header");
	}

}
