<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2015, http://www.gooraye.net. All Rights Reserved.
// |-----------------------------------------------------------------------------------

namespace Admin\Api;
use Common\Api\Api;
use Admin\Model\MenuModel;

class MenuApi extends Api{

    /**
     * 添加
     */
    const ADD = "Admin/Menu/add";
    /**
     * 保存
     */
    const SAVE = "Admin/Menu/save";
    /**
     * 保存根据ID主键
     */
    const SAVE_BY_ID = "Admin/Menu/saveByID";

    /**
     * 删除
     */
    const DELETE = "Admin/Menu/delete";

    /**
     * 查询
     */
    const QUERY = "Admin/Menu/query";
    /**
     * 查询一条数据
     */
    const GET_INFO = "Admin/Menu/getInfo";
    /**
     * 查询数据
     */
    const QUERY_SHOWING_MENU = "Admin/Menu/queryShowingMenu";

	protected function _init(){
		$this->model = new MenuModel();
	}
	/**
	 * query 不分页
	 * 查询显示状态下的菜单
	 */
	public function queryShowingMenu($map,$order = false){
		return $this->queryNoPaging(array_merge($map,array('hide'=>0)),$order);		
	}
	
	
	
}
