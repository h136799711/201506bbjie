<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016 杭州博也网络科技, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------

namespace Home\Controller;
use Common\Api\AccountApi;
use Think\Controller;

class HomeController extends  Controller {
	
	//主题
	protected $themeType = "default";
	protected $userinfo;
    protected $uid;
	protected function _initialize() {

		// 获取配置
		$this -> getConfig();
		
		if (!defined('APP_VERSION')) {
			//定义版本
			if (defined("APP_DEBUG") && APP_DEBUG) {
				define("APP_VERSION", time());
			} else {
				C('SHOW_PAGE_TRACE', false);//设置不显示trace
				define("APP_VERSION", C('APP_VERSION'));
			}
		}

        $this->userinfo = session('user');
        $this->uid = $this->userinfo['id'];
        $this->assign("user",$this->userinfo);
        $this->assign("username",$this->userinfo['username']);
		
	}

    protected function boye_display(){
        $this->theme($this->themeType)->display();
    }

    protected function checkLogin(){

        if(empty($this->userinfo)){
            $this->error("请登录后操作!",U('Home/Index/login'));
        }else{
            if(!defined("UID") || UID != $this->userinfo['id']){
                define("UID",$this->userinfo['id']);
            }
        }

    }

    protected function reloadUserInfo(){
        $this->checkLogin();
        $result = apiCall(AccountApi::GET_INFO,array(UID));
        if($result['status']){
            $this->userinfo = $result['info'];
            session('user',$this->userinfo);
        }else{
            $this->error($result['info']);
        }
    }

    protected function updateAvatar($img_url){
        $this->userinfo['head'] = $img_url;
        session('user',$this->userinfo);
    }

    protected function updateTaskGetType($status){
        $this->userinfo['task_gettype'] = $status;
        session('user',$this->userinfo);
    }


    public function _param($key,$default='',$empty_msg=''){
        $value = I('get.'.$key,$default);

        if(strlen($value) == 0 || $value == $default){
            $value = I('post.'.$key,$default);
        }

        if(strlen($value) == 0 || $value == $default){
            if(!empty($empty_msg)){
                $this->error($empty_msg);
            }
        }

        return $value;
    }



    /**
	 * 从数据库中取得配置信息
	 */
	protected function getConfig() {
		$config = S('config_' . session_id() . '_' . session("uid"));

		if ($config === false) {
			$map = array();
			$fields = 'type,name,value';
			$result = apiCall('Admin/Config/queryNoPaging', array($map, false, $fields));
			if ($result['status']) {
				$config = array();
				if (is_array($result['info'])) {
					foreach ($result['info'] as $value) {
						$config[$value['name']] = $this -> parse($value['type'], $value['value']);
					}
				}
				//缓存配置300秒
				S("config_" . session_id() . '_' . session("uid"), $config, 300);
			} else {
				LogRecord('INFO:' . $result['info'], '[FILE] ' . __FILE__ . ' [LINE] ' . __LINE__);
				$this -> error($result['info']);
			}
		}
		C($config);
	}

    /**
     * 根据配置类型解析配置
     * @param  integer $type 配置类型
     * @param  string $value 配置值
     * @return array|string
     */
	private static function parse($type, $value) {
		switch ($type) {
			case 3 :
				//解析数组
				$array = preg_split('/[,;\r\n]+/', trim($value, ",;\r\n"));
				if (strpos($value, ':')) {
					$value = array();
					foreach ($array as $val) {
						list($k, $v) = explode(':', $val);
						$value[$k] = $v;
					}
				} else {
					$value = $array;
				}
				break;
		}
		return $value;
	}

}
