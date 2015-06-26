<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
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
	
	
	
	/**
	 * 商家
	 */
	const Bbjmember_Seller_SaveByID='Home/BbjmemberSeller/saveByIDd';
	const Bbjmember_Seller_Add='Home/BbjmemberSeller/add';
	const Bbjmember_Seller_Query='Home/BbjmemberSeller/queryNoPaging';
	const Bbjmember_Seller_GetInfo='Home/BbjmemberSeller/getInfo';
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
	 //
	const UcenterUser_Query='Home/UcenterMember/queryNoPaging';
	const Member_Add='Admin/Member/add';
	const Member_Query='Admin/Member/queryNoPaging';
	const Member_QueryAll='Admin/Member/query';
	const Member_SaveByID='Admin/Member/saveByIDd';
	const Group_Add='Admin/AuthGroupAccess/add';
	const Group_QueryNpPage='Admin/AuthGroupAccess/queryNoPaging';
	const Address_Add='Home/Address/add';
	const Address_Del='Home/Address/delete';
	const Address_SaveByID='Home/Address/saveByID';
	const Address_Query='Home/Address/queryNoPaging';
	
}

