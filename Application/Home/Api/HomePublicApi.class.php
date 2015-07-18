<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 青<99701759@qq.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Home\Api;

class HomePublicApi{
	
	
	/**
	 * 用户
	 */
	const Bbjmember_QueryAll='Home/Bbjmember/query';
	const Bbjmember_Query='Home/Bbjmember/queryNoPaging';
	const Bbjmember_SaveByID='Home/Bbjmember/saveByIDd';
	const Bbjmember_Add='Home/Bbjmember/add';
	const Bbjmember_Del='Home/Bbjmember/delete';
	
	/*
	 * 兑换商品ExchangeProduct
	 * */
	const ExchangeProduct_QueryAll='Home/ExchangeProduct/query';
	const ExchangeProduct_Query='Home/ExchangeProduct/queryNoPaging';
	const ExchangeProduct_SaveByID='Home/ExchangeProduct/saveByID';
	const ExchangeProduct_Add='Home/ExchangeProduct/add';
	const ExchangeProduct_Del='Home/ExchangeProduct/delete';
	/**
	 * 商家
	 */
	const Bbjmember_Seller_SaveByID='Home/BbjmemberSeller/saveByID';
	const Bbjmember_Seller_SaveByUID='Home/BbjmemberSeller/saveByIDd';
	const Bbjmember_Seller_Add='Home/BbjmemberSeller/add';
	const Bbjmember_Seller_Query='Home/BbjmemberSeller/queryNoPaging';
	const Bbjmember_Seller_QueryAll='Home/BbjmemberSeller/query';
	const Bbjmember_Seller_GetInfo='Home/BbjmemberSeller/getInfo';
	const Bbjmember_Seller_Del='Home/BbjmemberSeller/delete';
	/**
	 * 提现账号
	 */
	const FinBankaccount_Query='Home/FinBankaccount/queryNoPaging';
	const FinBankaccount_Add='Home/FinBankaccount/add';
	const FinBankaccount_SaveByID='Home/FinBankaccount/saveByID';
	
	/*
	 * 交易记录
	 * */
	const FinAccountBalanceHis_QueryAll='Home/FinAccountBalanceHis/query';
	const FinAccountBalanceHis_Query='Home/FinAccountBalanceHis/queryNoPaging';
	const FinAccountBalanceHis_Add='Home/FinAccountBalanceHis/add';
	const FinAccountBalanceHis_SaveByID='Home/FinAccountBalanceHis/saveByID';
	
	/**
	 * 登录账号
	 */
	
	const User_Register='Uclient/User/register';
	const User_GetbyID='Uclient/User/getbyid';
	const User_Login='Uclient/User/login';
	const User_GetInfo='Uclient/User/getbyname';
	const User_GetUser='Uclient/User/getInfo';
	const User_EditPwd='Uclient/User/updateInfo';
	const User_SaveByID='Home/UcenterMember/saveByID';
	const User_CheckUserName='Uclient/User/checkUsername';
	/**
	 * 
	 * 
	 */
	 
	const UcenterUser_Query='Home/UcenterMember/queryNoPaging';
	const UcenterUser_Del='Home/UcenterMember/delete';
	const Member_Add='Admin/Member/add';
	const Member_Query='Admin/Member/queryNoPaging';
	const Member_QueryAll='Admin/Member/query';
	const Member_Del='Admin/Member/delete';
	const Member_SaveByID='Admin/Member/saveByIDd';
	const Group_Add='Admin/AuthGroupAccess/add';
	const Group_QueryNpPage='Admin/AuthGroupAccess/queryNoPaging';
	const Address_Add='Home/Address/add';
	const Address_Del='Home/Address/delete';
	const Address_SaveByID='Home/Address/saveByID';
	const Address_Query='Home/Address/queryNoPaging';
	
	/*
	 * 商品
	 * */
    const Product_QueryAll='Home/TaskProduct/query';
	const Product_Query='Home/TaskProduct/queryNoPaging';
	const Product_SaveByID='Home/TaskProduct/saveByID';
	const Product_Add='Home/TaskProduct/add';
	const Product_Del='Home/TaskProduct/delete';
	
	/*
	 * 任务信息
	 * */
	const Task_QueryAll='Home/Task/query';
	const Task_Query='Home/Task/queryNoPaging';
	const Task_SaveByID='Home/Task/saveByID';
	const Task_Save='Home/Task/save';
	const Task_Add='Home/Task/add';
	const Task_Del='Home/Task/delete';
	/*
	 * 接受任务信息
	 * */
	const Task_His_QueryAll='Home/TaskHis/query';
	const Task_His_Query='Home/TaskHis/queryNoPaging';
	const Task_His_SaveByID='Home/TaskHis/saveByID';
	const Task_His_Add='Home/TaskHis/add';
	const Task_His_Del='Home/TaskHis/delete';
	/*
	 * 任务关联表
	 * */
	const TaskHasProduct_QueryAll='Home/TaskHasProduct/query';
	const TaskHasProduct_Query='Home/TaskHasProduct/queryNoPaging';
	const TaskHasProduct_SaveByID='Home/TaskHasProduct/saveByID';
	const TaskHasProduct_Add='Home/TaskHasProduct/add';
	const TaskHasProduct_Del='Home/TaskHasProduct/delete';
	
	/*
	 * 任务计划
	 * */
	const TaskPlan_QueryAll='Home/TaskPlan/query';
	const TaskPlan_Query='Home/TaskPlan/queryNoPaging';
	const TaskPlan_SaveByID='Home/TaskPlan/saveByID';
	const TaskPlan_Add='Home/TaskPlan/add';
	const TaskPlan_Del='Home/TaskPlan/delete';
	
	/*
	 * 搜索管理
	 * */
	const ProductSearchWay_QueryAll='Home/ProductSearchWay/query';
	const ProductSearchWay_Query='Home/ProductSearchWay/queryNoPaging';
	const ProductSearchWay_SaveByID='Home/ProductSearchWay/saveByID';
	const ProductSearchWay_Add='Home/ProductSearchWay/add';
	const ProductSearchWay_Del='Home/ProductSearchWay/delete';
}


