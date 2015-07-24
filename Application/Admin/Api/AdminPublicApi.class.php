<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2015, http://www.gooraye.net. All Rights Reserved.
// |-----------------------------------------------------------------------------------

namespace Admin\Api;



class AdminPublicApi {
	const WxshopPicture_Query='Admin/WxshopPicture/query';
	
	/**
	 * 公告文章
	 */
	const Post_QueryNoPaging="Admin/Post/queryNoPaging"; 
	const Post_Query="Admin/Post/query";
	
	/**
	 * 公告
	 */
	const Datatree_QueryNoPaging="Admin/Datatree/queryNoPaging";
	const Datatree_Query="Admin/Datatree/query";
	
	
	const Category_QueryNoPaging="Admin/Category/queryNoPaging";
	const Category_Query="Admin/Category/query";
	
	/*
	 * 商品
	 * */
	const Wxproduct_QueryNoPaging="Admin/Wxproduct/queryNoPaging";
	
	/*
	 * 地址管理
	 * */
	const Address_Add="Admin/OrdersInfoView/add";
	const Address_Query="Admin/OrdersInfoView/queryNoPaging";
	const Address_saveByID="Admin/OrdersInfoView/saveByID";
	const Address_Del="Admin/OrdersInfoView/delete";
	/*
	 * 订单
	 * */
	const Orders_Add="Admin/Orders/add";
}