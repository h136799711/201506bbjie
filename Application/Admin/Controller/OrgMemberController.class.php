<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------

namespace Admin\Controller;

class OrgMemberController extends  AdminController{
	private $orgid;
	protected function _initialize(){
		parent::_initialize();
		$this->orgid = I('get.orgid',0);
		$this->orgparent = I('get.orgparent',0);
		$this->assign("orgparent",$this->orgparent);
		$this->assign("orgid",$this->orgid);
	}
	
	public function index(){
		
//		$result = apiCall("Admin/Organization/getInfo", array(array('id'=>$this->orgid)));
//		if(!$result['status']){
//			$this->error($result['info']);
//		}
//		
//		$this->assign("orginfo",$result['info']);
		
		$map = array();
		$map['orgid'] = $this->orgid;
		$result = apiCall("Admin/OrgMemberView/query", array($map,$page));
//		dump($result);
		if(!$result['status']){
			$this->error($result['info']);
		}
		
		$this->assign("list",$result['info']['list']);
		$this->assign("show",$result['info']['show']);
		$this->display();
	}
	
	/**
	 * 添加
	 */
	public function add(){
		if(IS_GET){
			$result = apiCall("Admin/Organization/getInfo", array(array('id'=>$this->orgid)));
			if(!$result['status']){
				$this->error($result['info']);
			}
			
			$this->assign("orginfo",$result['info']);
			
			$this->display();
		}else{
			$memberids = I('post.memberids','');
			$memberids = rtrim($memberids,",");
			$memberid_arr = explode(",",$memberids);
			if(empty($this->orgid)){
				$this->error("组织机构ID错误！");
			}
			$entity_arr = array();
			foreach($memberid_arr as $vo){
				$entity = array(
					'member_uid'=>$vo,
					'organization_id'=>$this->orgid,
				);
				$result = apiCall("Admin/OrgMember/getInfo", array($entity));
				
				if(!	$result['status']){
					$this->error($result['info']);
				}
				
				if($result['status'] && is_null($result['info'])){
					array_push($entity_arr,$entity);
				}
				
			}
			if(count($entity_arr) > 0){
				$result = apiCall("Admin/OrgMember/addAll", array($entity_arr));
				
				
				if(!	$result['status']){
					$this->error($result['info']);
				}
			}
			
			$this->success("保存成功！",U("Admin/OrgMember/index",array("orgid"=>$this->orgid,'orgparent'=>$this->orgparent)));
		}
	}
	
	public function delete(){
		$m_id = I('get.mid',0);
		$map = array(
			'member_uid'=>$m_id,
			'organization_id'=>$this->orgid,
		);
		
		$result = apiCall("Admin/OrgMember/delete", array($map));
		
		if(!	$result['status']){
			$this->error($result['info']);
		}
		$this->success("删除成功！");
	}
	
	
	
}
