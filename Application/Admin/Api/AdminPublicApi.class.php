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
	
	const OrderExpress_Query='Admin/OrdersExpress/queryNoPaging';
	const OrderExpress_QueryAll='Admin/OrdersExpress/query';
	/**
	 * 公告文章
	 */
	const Post_QueryNoPaging="Admin/Post/queryNoPaging"; 
	const Post_Query="Admin/Post/query";
	const Post_Delete="Admin/Post/delete";
	
	/**
	 * 公告
	 */
	const Datatree_QueryNoPaging="Admin/Datatree/queryNoPaging";
	const Datatree_Query="Admin/Datatree/query";
	
	/*
	 * 站内消息
	 * */
	const Message_Add="Admin/Message/add";
	const Message_QueryAll="Admin/Message/query";
	const Msgbox_Add="Admin/Msgbox/add";
	const Msgbox_SavebyId="Admin/Msgbox/saveByID";
	const Msgbox_QueryAll="Admin/Msgbox/query";
	
	
	const Category_QueryNoPaging="Admin/Category/queryNoPaging";
	const Category_Query="Admin/Category/query";
	
	/*
	 * 商品
	 * */
	const Wxproduct_QueryNoPaging="Admin/Wxproduct/queryNoPaging";
	
	/*
	 * 地址管理
	 * */
	const Order_Address_Add="Admin/OrdersContactinfo/add";
	const Order_Address_Query="Admin/OrdersContactinfo/queryNoPaging";
	const Order_Address_saveByID="Admin/OrdersContactinfo/saveByID";
	const Order_Address_Del="Admin/OrdersContactinfo/delete";
	/*
	 * 订单
	 * */
	const Orders_Add="Admin/Orders/add";
	const Orders_SaveByID="Admin/Orders/saveByID";
	const Orders_Query="Admin/Orders/queryNoPaging";
}