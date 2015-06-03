<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2015, http://www.gooraye.net. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Admin\Controller;

class MemberController extends AdminController {

	public function index() {
		$params = array();
		
		
		$where['nickname'] = array('like', "%" . I('nickname', '', 'trim') . "%");		
		$where['uid'] = I('nickname',-1);
		$where['_logic'] = 'OR';
		
		$map['_complex'] = $where;
		$map['status'] = array('neq',-1);
		
		$page = array('curpage' => I('get.p'), 'size' => C('LIST_ROW'));
		$order = " last_login_time desc ";
		$params['nickname'] = I('nickname','','trim');
		
		$result = apiCall("Admin/Member/query", array($map, $page, $order));
		
		if ($result['status']) {
			
			$this -> assign("show", $result['info']['show']);
			$this -> assign("list", $result['info']['list']);
			$this -> display();
		} else {
			$this -> error($result['info']);
		}
	}
	
	public function view(){
		$id = I('get.id',0);
		
		$result = apiCall("Admin/Member/getInfo", array(array("uid"=>$id)));
		
		if(!$result['status']){
			$this->error($result['info']);
		}
		$this->assign("userinfo",$result['info']);
		
		$result = apiCall("Uclient/User/getInfo", array($id));
		if(!$result['status']){
			$this->error($result['info']);
		}
		$this->assign("useraccount",$result['info']);
		
		$result = apiCall("Admin/AuthGroupAccess/queryGroupInfo", array($id));
		if(!$result['status']){
			$this->error($result['info']);
		}
		$this->assign("userroles",$result['info']);
		
		
		$this->display();
	}
	
	/**
	 * 删除用户
	 * 假删除
	 */
	public function delete(){
		if(is_administrator(I('uid',0))){
			$this->error("禁止对超级管理员进行删除操作！");
		}
		
		$result = apiCall("Admin/Member/pretendDelete", array(array('uid'=>I('uid',0))));
		if(!$result['status']){
			LogRecord($result['info'], __FILE__.__LINE__);
		}
		
		$result = apiCall("Uclient/User/delete", array(I('uid',0)));
		if(!$result['status']){
			LogRecord($result['info'], __FILE__.__LINE__);
		}

		$this->success("删除成功～！");
	}
	/**
	 * 启用
	 */
	public function enable(){
		parent::enable("uid");
	}
	/**
	 * 禁用
	 */
	public function disable(){
		if(is_administrator(I('uid',0))){
			$this->error("禁止对超级管理员进行禁用操作！");
		}
		parent::disable("uid");
	}
	
	/**
	 * add 
	 */
    public function add($username = '', $password = '', $repassword = '', $email = ''){
		if(IS_POST){
			
			if($password != $repassword){
				$this->error("密码和重复密码不一致！");
			}
			
			$orgids = array();
			
			/* 调用注册接口注册用户 */			
			$result = apiCall("Uclient/User/register", array($username, $password, $email));

            if($result['status']){ //注册成功
            		$entity = array(
					'uid'=>$result['info'],
					'nickname'=>$username,
					'realname'=>'',
					'idnumber'=>'',
				);
				$result = apiCall("Admin/Member/add", array($entity));
                if(!$result['status']){
                    $this->error('用户添加失败！');
                } else {
                    $this->success('用户添加成功！',U('Member/index'));
                }
            } else { //注册失败，显示错误信息
                $this->error($result['info']);
            }
			
//			$entity = array(
//				'username'=>I('username','','trim'),
//				'password'=>$password,
//				'email'=>I('email','','trim'),
//			);
//			parent::add($entity);
		}else{
			$this->display();
		}
	}
	
	/**
	 * 检测用户名是否已存在
	 */
	public function check_username($username){
		$result = apiCall("Uclient/User/checkUsername",array($username));
		if($result['status']){
			echo "true";
		}else{
			echo "false";
		}
	}
	
	 /**
	 * 检测用户名是否已存在
	 */
	public function check_email(){
			$result = apiCall("Uclient/User/checkEmail",array($email));
		if($result['status']){
			echo "true";
		}else{
			echo "false";
		}
	}
	
	/**
	 * 
	 */
	public function select(){
		
		$map['nickname'] = array('like', "%" . I('q', '', 'trim') . "%");		
		$map['uid'] = I('q',-1);
		$map['_logic'] = 'OR';
		$page = array('curpage'=>0,'size'=>20);
		$order = " last_login_time desc ";
		
		$result = apiCall("Admin/Member/query", array($map,$page, $order,false,'uid,nickname'));
		
		if($result['status']){
			$list = $result['info']['list'];
			
			foreach($list as $key=>$g){
				
				if(is_administrator($list[$key]['uid'])){
					unset($list[$key]);
				}else{
					$list[$key]['id']=$list[$key]['uid'];
				}
			}
			
			$this->success($list);
		}	
	
	}
	
	
	
}
