<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------

namespace Tool\Controller;
use Think\Controller;

/**
 * 验证码
 */
class VerifyController extends Controller{
	
	public function test(){
		
		if(IS_GET){
			$this->assign("id",time());
			$this->display();
		}else{
			$code = I("post.code",'');
			$id = I("post.id",1);
			if($this->check($code,$id)){
				exit('通过');
			}else{
				dump($code);
				dump($id);
			}
		}
	}
	
	/**
	 * 校验验证码是否正确
	 * @return Boolean
	 */
	public function checkCode($code, $id = 1) {

		$config = array('fontSize' => 22, // 验证码字体大小
		'length' => 4, // 验证码位数
		'useNoise' => false, // 关闭验证码杂点
		);
		$Verify = new \Think\Verify($config);
		return $Verify -> check($code, $id);
	}
	
	/**
	 * 获取验证码
	 */
	public function getCode($id) {
		
		$config = array('fontSize' => 22, // 验证码字体大小
		'length' => 4, // 验证码位数
		'useNoise' => false, // 关闭验证码杂点
		'imageW' => '240', 'imageH' => '40');
		$Verify = new \Think\Verify($config);
		$Verify -> entry($id);
	}
}
