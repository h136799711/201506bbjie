<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------


namespace Admin\Model;

use Think\Model\ViewModel;

class OrgMemberViewModel extends ViewModel{
	
	public $viewFields = array(
     	'OrgMember'=>array('_table'=>'common_org_member','_type'=>"LEFT"),
		'Member'=>array('_table'=>'common_member','uid'=>'member_id','nickname'=>'member_nickname','_on'=>'Member.uid=OrgMember.member_uid'),
     	'Organization'=>array('_table'=>'common_organization','path'=>'org_path','id'=>'orgid','orgname'=>'orgname', '_on'=>'Organization.id=OrgMember.organization_id'),
	);
	
}
