<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Home\Api;

class HomePublicApi{
	const Bbjmember_QueryAll='Home/Bbjmember/query';
	const Bbjmember_Query='Home/Bbjmember/queryNoPaging';
	const Bbjmember_SaveByID='Home/Bbjmember/saveByIDd';
	const Bbjmember_Add='Home/Bbjmember/add';
	const Bbjmember_Seller_SaveByID='Home/BbjmemberSeller/saveByID';
	const Bbjmember_Seller_Add='Home/BbjmemberSeller/add';
	const Bbjmember_Seller_Query='Home/BbjmemberSeller/queryNoPaging';
	const User_Register='Uclient/User/register';
	const User_Query='Uclient/User/query';
	const User_Login='Uclient/User/login';
	const User_GetInfo='Uclient/User/getbyname';
	const Member_Add='Admin/Member/add';
	const Member_Query='Admin/Member/queryNoPaging';
	const Member_QueryAll='Admin/Member/query';
	const Member_SaveByID='Admin/Member/saveByIDd';
	const Group_Add='Admin/AuthGroupAccess/add';
	const Group_QueryNpPage='Admin/AuthGroupAccess/queryNoPaging';
	const Address_Add='Home/Address/add';
	const Address_Query='Home/Address/queryNoPaging';
}