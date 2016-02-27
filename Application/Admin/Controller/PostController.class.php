<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------

namespace Admin\Controller;

use Cms\Api\PostApi;
use Cms\Api\VPostInfoApi;

class PostController extends  AdminController{
	
	public function index(){
        $title = $this->_param('title','');
        $post_status = $this->_param('post_status','draft');

		$map = array();
        $params = array();

        $params['post_status'] = $post_status;
        $map['post_status'] = $post_status;
        if(!empty($title)){
            $map['title'] = $title;
            $params['title'] = $title;
        }

		$page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
		$order = " post_modified desc ";

		$result = apiCall(VPostInfoApi::QUERY,array($map,$page,$order,$params));

		if($result['status']){

            $this->assign('post_status',$post_status);
            $this->assign('title',$title);
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
			$post_category = I('post.post_category',20);

			$entity = array(
				'main_img'=>I('post.main_img',''),
				'post_category'=>$post_category,
				'post_content'=>I('post_content',''),
				'post_excerpt'=>I('post_excerpt',''),
				'post_title'=>I('post_title',''),
				'post_author'=>UID,
				'post_status'=>I('post_status','draft'),
				'comment_status'=>I('commen_status','closed'),
				'post_parent'=>0,
				'post_type'=>'post_type',
				'comment_count'=>0,
                'post_date'=>time(),
                'post_modified'=>time(),
			);

			$result = apiCall(PostApi::ADD, array($entity));
			
			if(!$result['status']){
				$this->error($result['info']);
			}
			
			$this->success("操作成功！",U('Admin/Post/index'));
			
		}
	}
	
	public function edit(){
		$id = I('id',0);
		if(IS_GET){
			$result = apiCall(PostApi::GET_INFO, array(array("id"=>$id)));
			if(!$result['info']){
				$this->error($result['info']);
			}
			$this->assign("vo",$result['info']);
			$this->display();
		}else{
			$post_category = I('post.post_category',20);
			
			$entity = array(
				'main_img'=>I('post.main_img',''),
				'post_category'=>$post_category,
				'post_content'=>I('post_content',''),
				'post_excerpt'=>I('post_excerpt',''),
				'post_title'=>I('post_title',''),
				'post_status'=>I('post_status','draft'),
				'comment_status'=>I('commen_status','closed'),
			);

			$result = apiCall(PostApi::SAVE_BY_ID, array($id,$entity));

			if(!$result['status']){
				$this->error($result['info']);
			}
			
			$this->success("保存成功！",U('Admin/Post/index'));
			
		}
	}
	
	public function delete(){
		$id = I('id',0);
		
		$result = apiCall(PostApi::DELETE, array(array("id"=>$id)));
		
		if(!$result['status']){
			$this->error($result['info']);
		}
		
		$this->success("删除成功！");
	
	}
	
}
