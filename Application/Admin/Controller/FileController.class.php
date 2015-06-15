<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016 杭州博也网络科技, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------

namespace Admin\Controller;
use Admin\Api\AdminPublicApi;

class FileController extends AdminController{
	
	protected function _initialize(){
		parent::_initialize();
	}
	
	public function test(){
		$this->display();
	}
	
	public function uploadPicture(){
		if(IS_POST){
			
	        /* 返回标准数据 */
	        $return  = array('status' => 1, 'info' => '上传成功', 'data' => '');
	
	        /* 调用文件上传组件上传文件 */
	        $Picture = D('Picture');
	        $pic_driver = C('PICTURE_UPLOAD_DRIVER');
	        $info = $Picture->upload(
	            $_FILES,
	            C('PICTURE_UPLOAD'),
	            C('PICTURE_UPLOAD_DRIVER'),
	            C("UPLOAD_{$pic_driver}_CONFIG")
	        ); //TODO:上传到远程服务器
	
	        /* 记录图片信息 */
	        if($info){
	            $return['status'] = 1;
	            $return = array_merge($info['download'], $return);
	        } else {
	            $return['status'] = 0;
	            $return['info']   = $Picture->getError();
	        }
	
	        /* 返回JSON数据 */
	        $this->ajaxReturn($return);
		}
		
	}

	public function picturelist(){
		if(IS_AJAX){
			$cur = I('get.p',0);
			$q = I('post.q','');
			$size = I('post.size',10);
			$map = array('uid'=>UID);
			
			if(!empty($q)){
				$map['ori_name'] = array('like','%'.$q.'%');
			}
			
			$page = array('curpage'=>$cur,'size'=>$size);
			$order = 'createtime desc';
			$params = array(
				'p'=>$cur,
				'size'=>$size,
			);
			$fields = 'id,createtime,status,path,url,md5,imgurl,ori_name,savename,size';
//			query($map = null, $page = array('curpage'=>0,'size'=>10), $order = false, $params = false, $fields = false)
	        $result = apiCall('Admin/WxshopPicture/query',array($map,$page,$order,$params,$fields));
			if($result['status']){
				$this->success($result['info']);
			}else{
				$this->error($result['info']);
			}
		}
	}
	
	
	/**
	 * 上传图片接口
	 */
	public function uploadWxshopPicture(){
		if(IS_POST){
			
			if(!isset($_FILES['wxshop'])){
				$this->error("文件对象必须为wxshop");
			}
			
			
			
//			$wxshopapi = new \Common\Api\WxShopApi($this->appid,$this->appsecret);
			$tmp_name = $_FILES['wxshop']['tmp_name'];
			
			//1.上传到微信
//			$result = $wxshopapi->uploadImg(time().".jpg",$tmp_name);
//			
//			if(!$result['status']){
//				$this->error($result['info']);
//			}

			$result['info'] = "";
			//2.再上传到自己的服务器，
			//TODO:也可以上传到QINIU上
	        /* 返回标准数据 */
	        $return  = array('status' => 1, 'info' => '上传成功', 'data' => '');
			
	        /* 调用文件上传组件上传文件 */
	        $Picture = D('WxshopPicture');
			$extInfo = array('uid' => UID,'imgurl' => $result['info']);
	        $info = $Picture->upload(
	            $_FILES,
	            C('WXSHOP_PICTURE_UPLOAD')
	            ,$extInfo
			); 
			
	        /* 记录图片信息 */
	        if($info){
	            $return['status'] = 1;
	            $return = array_merge($info['wxshop'], $return);
	        } else {
	            $return['status'] = 0;
	            $return['info']   = $Picture->getError();
	        }
	
	        /* 返回JSON数据 */
	        $this->ajaxReturn($return);
		}
		
	}
}
