<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------

namespace Admin\Controller;

class OrganizationController extends AdminController{
	private $parent;
	private $preparent;
	
	protected function _initialize(){
		parent::_initialize();
		
		$this->parent = I('parent',0);
		
		$result = apiCall("Admin/Organization/getInfo",array(array('id'=>$this->parent)));
		if(!$result['status']){
			$this->error($result['info']);
		}
		
		if(is_array($result['info'])){
			$this->preparent = $result['info']['father'];
		}
		$this->assign('parent_orgname',$result['info']['orgname']);
		$this->assign('parent',$this->parent);
		$this->assign('preparent',$this->preparent);
	}
	
	
	
	public function index(){
		$name = I('name','');
		$map = array('father'=>$this->parent);
		
		$params = array('parent'=>$this->parent);
		
		if(!empty($name)){
			$map['orgname'] = array('like',"%$name%");
			$params['orgname'] = $name;
		}
		
		$page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
		
		$order = " sort desc ";
		//
		$result = apiCall("Admin/Organization/query",array($map,$page,$order,$params));
		
		//
		if($result['status']){
			$this->assign('name',$name);
			$this->assign('show',$result['info']['show']);
			$this->assign('list',$result['info']['list']);
			$this->display();
		}else{
			LogRecord('INFO:'.$result['info'],'[FILE] '.__FILE__.' [LINE] '.__LINE__);
			$this->error(L('UNKNOWN_ERR'));
		}
		
	}	

	
	public function add(){
		if(IS_GET){
			
						
			$this->display();
		}else{
			$result = apiCall("Admin/Organization/getInfo",array(array('id'=>$this->parent)));
			$level = 0;
			$parents = $this->parent.',';
			if($result['status'] && is_array($result['info'])){
				$level = intval($result['info']['level'])+1;
				$parents = $result['info']['path'].$parents;
			}
			$entity = array(
				'orgcode'=>'',
				'orgname'=>I('name',''),
				'notes'=>I('notes',''),
				'sort'=>I('sort',''),
				'level'=>$level,
				'path'=>$parents,
				'father'=>$this->parent,
			);
			
			$result = apiCall("Admin/Organization/add", array($entity));
			
			if(!$result['status']){
				$this->error($result['info']);
			}

			$this->success("操作成功！",U('Admin/Organization/index',array('parent'=>$this->parent)));
			
		}
	}
	
	public function delete(){
		$id = I('id',0);
		
		$result = apiCall("Admin/Organization/queryNoPaging", array(array('father'=>$id)));
		if(!$result['status']){
			$this->error($result['info']);
		}
		if(is_array($result['info']) && count($result['info']) > 0){
			$this->error("有子机构，请先删除所有子机构！");
		}
		
		$result = apiCall("Admin/OrgMember/query", array(array('organization_id'=>$id)));
		if(!$result['status']){
			$this->error($result['info']);
		}
		if(is_array($result['info']['list']) && count($result['info']['list']) > 0){
			$this->error("存在机构成员，请先移除所有机构的成员！");
		}
		
		$result = apiCall("Admin/Organization/delete", array(array('id'=>$id)));
		if(!$result['status']){
			$this->error($result['info']);
		}

		$this->success("操作成功！");
		
		
	}
	
	public function edit(){
		$id = I('get.id',0);
		if(IS_GET){
			$result = apiCall("Admin/Organization/getInfo",array(array('id'=>$id)));
			if($result['status']){
				$this->assign("entity",$result['info']);
			}
			
			$this->display();
		}else{
			
			$entity = array(
				'orgname'=>I('name',''),
				'notes'=>I('notes',''),
				'sort'=>I('sort',''),
				'code'=>I('code',''),
			);
			$result = apiCall("Admin/Organization/saveByID", array($id,$entity));
			
			if(!$result['status']){
				$this->error($result['info']);
			}

			$this->success("操作成功！",U('Admin/Organization/index',array('parent'=>$this->parent)));
			
		}
	}
	
	
	
	
}
