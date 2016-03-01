<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 青 <99701759@qq.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Home\Controller;
use Admin\Api\DatatreeApi;
use Admin\Api\MsgboxApi;
use Admin\Model\MsgboxModel;
use Admin\Model\PictureModel;
use Cms\Api\PostApi;
use Cms\Api\VPostInfoApi;
use Cms\Model\PostModel;
use Common\Api\AccountApi;
use Home\Api\BbjmemberApi;
use Home\Api\TaskHisApi;
use Home\ConstVar\BoyeActionConstVar;
use Home\ConstVar\UserTypeConstVar;
use Think\Controller;
use Think\Storage;
use Home\Api\HomePublicApi;
use Admin\Api\AdminPublicApi;
use Uclient\Api\UserApi;

/*
 * 官网首页
 */
class IndexController extends HomeController {

    public function _initialize(){

        parent::_initialize();

        $this->getLastestNotice();
    }

	 /*
	  * 试民注册界面
	  * */
	public function register_sm(){
		$headtitle="宝贝街-试民注册";
		$this->assign('head_title',$headtitle);
		$this->display();
	}
	/*
	  * 商家注册界面
	  * */
	public function register_sj(){
		$headtitle="宝贝街-商家注册";
		$this->assign('head_title',$headtitle);
		$this->display();
	}

	/*
	  * 首页
	  * */
	public function index(){
		
		$this->redirect('Shop/Index/index');
	}

	/*
	  * 福品专场
	  * */
	public function flzc(){
		$order = " post_modified desc ";
		$result = apiCall(AdminPublicApi::Post_QueryNoPaging,array($map, $order));
		$this->assign('zxgg',$result['info'][0]);
		
		$headtitle="宝贝街-福品专场";
		$this->assign('head_title',$headtitle);
		$users=session('user');
		$this->assign('username',session('user')['info']['username']);
		$this->display();
	}
	/*
	  * 幸福一点
	  * */
	public function xfyd(){
		$order = " post_modified desc ";
		$result = apiCall(AdminPublicApi::Post_QueryNoPaging,array($map, $order));
		$this->assign('zxgg',$result['info'][0]);
		
		$headtitle="宝贝街-幸福一点";
		$this->assign('head_title',$headtitle);
		$users=session('user');
		$this->assign('username',session('user')['info']['username']);
		$this->display();
	}
	/*
	  * 试江湖
	  * */
	public function sjh(){
		$order = " post_modified desc ";
		$result = apiCall(AdminPublicApi::Post_QueryNoPaging,array($map, $order));
		$this->assign('zxgg',$result['info'][0]);
		
		
		$headtitle="宝贝街-试江湖";
		$this->assign('head_title',$headtitle);
		$users=session('user');
		$this->assign('username',session('user')['info']['username']);
		$this->display();
	}
	/*
	  * 茶话馆
	  * */
	public function chg(){
		$order = " post_modified desc ";
		$result = apiCall(AdminPublicApi::Post_QueryNoPaging,array($map, $order));
		$this->assign('zxgg',$result['info'][0]);
		
		
		$headtitle="宝贝街-茶话馆";
		$this->assign('head_title',$headtitle);
		$users=session('user');
		$this->assign('username',session('user')['info']['username']);
		$this->display();
	}
/*
	  * 帮助中心
	  * */
	public function bzzx(){
		$order = " post_modified desc ";
		$result = apiCall(AdminPublicApi::Post_QueryNoPaging,array($map, $order));
		$this->assign('zxgg',$result['info']);
		$headtitle="宝贝街-帮助中心";
		$this->assign('head_title',$headtitle);
		$users=session('user');
		$this->assign('username',session('user')['info']['username']);
		$this->display();
	}
	/*
	  * 商品详情
	  * */
	public function spxq(){
		//查询最新通知
		$order = " post_modified desc ";
		$result = apiCall(AdminPublicApi::Post_QueryNoPaging,array($map, $order));
		$this->assign('zxgg',$result['info'][0]);
		
		
		$headtitle="宝贝街-商品详情";
		$this->assign('head_title',$headtitle);
		$users=session('user');
		$this->assign('username',session('user')['info']['username']);
		$this->display();
	}
	/*
	  * 官方公告
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	  * */
	public function gfgg(){

		//查询公告标题

        $map['parentid'] = 21;

        $result = apiCall(DatatreeApi::QUERY_NO_PAGING,array($map));
        $this->assign('ggTitle',$result['info']);

		$post_category = I('get.post_category','');

		$map = array();

		$map['post_category'] = $post_category;

		$page = array('curpage' => I('get.p', 0), 'size' => 2);

		$result=apiCall(VPostInfoApi::QUERY,array($map,$page));

		$this->assign('list',$result['info']['list']);
		$this->assign('show',$result['info']['show']);

		$this->assign('head_title',"宝贝街-官方公告");
		$this->display();
	}
	/*
	  * 官方公告信息
	  * */
	public function gfggxx(){


		$this->assign('head_title',"宝贝街-官方公告信息");

        $map['parentid'] = 21;

        $result = apiCall(DatatreeApi::QUERY_NO_PAGING,array($map));
        $this->assign('ggTitle',$result['info']);

		//查询公告标题
		$map = array();
		//根据id查询公告文章
		$map = array();

        $id = I('get.id',0);
        if(!empty($id)){
		    $map['id'] = $id;
        }

		$result = apiCall(VPostInfoApi::GET_INFO,array($map));
		
		$this->assign('gg',$result['info']);
		$this->display();
	}
	/*
	  * 用户协议
	  * */
	public function xieyi(){
		$this->display();
	}
	/*
	  * 试民注册界面
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	  * */
	public function smzc(){

		$username=I('post.user_name');
		$password=I('post.password');
		$mobile=I('post.phone_tel');
		$email=$username."@qq.com";
		$yqr=I('post.yaoqingren','');

        $map = array('username'=>$yqr);
        $result = apiCall(UserApi::FIND,array($map));
        $invite_id = 0;

        if($result['status'] && is_array($result['info'])){
            $invite_id = $result['info']['id'];
        }

        $entity = array(
            'username'=>$username,
            'password'=>$password,
            'invite_id'=>$invite_id,
            'invite_username' => $yqr,
            'daily_task_money'=>1000,
            'user_type'=>UserTypeConstVar::BBJ_MEMBER_GROUP,
            'mobile'=>$mobile,
            'email'=>$email,
            'from'=>'99',
            'aliwawa'=>'',
        );

        $result = apiCall(AccountApi::Register, array($entity));
        if($result['status']){
            $this->success("注册成功",U("Home/Index/login"));
        }else{
            $this->error($result['info']);
        }


	}
	/*
	 * 删除站内信息
	 * */
	public function del_msg(){

		$id  = I('get.id',0);

		$map = array('msg_status'=> MsgboxModel::DELETE);

		$result=apiCall(MsgboxApi::SAVE_BY_ID, array($id,$map));

		if($result['status']){
            $this->success('操作成功！',U('Home/Usersj/sj_znxx'));
		}

	}

    /*
	 * 商家注册基本信息
	 *
     *
     * */
	public function sjzc(){
		$username=I('post.user_name');
		$password=I('post.password');
		$mobile=I('post.phone_tel');
		$email=$username."@qq.com";
		$yqr=I('post.yaoqingren','');
        $map = array(
            'username'=>$yqr,
        );

        $result = apiCall(UserApi::FIND,array($map));
        $invite_id = 0;

        if($result['status'] && is_array($result['info'])){
            $invite_id = $result['info']['id'];
        }

        $entity = array(
            'username'=>$username,
            'password'=>$password,
            'invite_id'=>$invite_id,
            'invite_username' => $yqr,
            'daily_task_money'=>1000,
            'user_type'=>UserTypeConstVar::BBJ_SHOP_GROUP,
            'mobile'=>$mobile,
            'email'=>$email,
            'from'=>'99',
        );

        session('seller_info',$entity);

        $this->redirect("Home/Index/register_sj_kz");
	}
    /*
	  * 商家注册详细信息
	  * */
	public function sjzc_kz(){

        if(IS_GET){
            $this->assign('head_title',"宝贝街-商家注册");
            $this->display();
        }else{

            $entity = session('seller_info');

            $entity['store_name'] = I('post.dpname','');
            $entity['aliwawa'] = I('post.alww','');
            $entity['qq'] = I('post.qq','');
            $entity['linkman'] = I('post.lxr','');
            $entity['address'] = I('post.jydz','');
            $result = apiCall(AccountApi::Register, array($entity));

            session('seller_info',null);
            if ($result['status']) {
                $this->success("注册成功",U("Home/Index/login"));
            }else{
                $this->error("请重新注册",U("Home/Index/register_sj"));
            }
        }
	}

	/**
	 * 登录地址
	 */
	public function login(){

		if(IS_GET){
			$this->assign('head_title',"宝贝街-登录");
			$this->display();
		}else{
			//检测用户
			$username = I('post.username', '', 'trim');
			$password = I('post.password', '', 'trim');

			$result = apiCall(UserApi::LOGIN, array('username' => $username, 'password' => $password));

			if ($result['status']) {

				$uid = $result['info'];
				$result = apiCall(AccountApi::GET_INFO, array($uid));

                if(!$result['status']){
                    $this->error("登录失败!".$result['info']);
                }

                $userinfo = $result['info'];

                session("user",$userinfo);

                if($userinfo['user_type'] == UserTypeConstVar::BBJ_MEMBER_GROUP){
                    $this->success('登录成功!',U('Home/Index/sm_manager'));
                }elseif($userinfo['user_type'] == UserTypeConstVar::BBJ_SHOP_GROUP){
                    $this->success('登录成功!',U('Home/Usersj/index'));
                }else{
                    $this->error("非法用户!");
                }
				
			} else{

                $this->error($result['info']);
				
			}
		}
	}

	/*
	  * 退出当前账号
	  * */
	public function exits(){
		session('[destroy]'); // 删除session
		$this->display('login');
	}

	/*
	 * 验证用户名是否存在
	 * */
	 public function checkname(){
	 	$username = I("name",'');

		if(isset($username)){ 
			$results = apiCall(UserApi::CHECK_USER_NAME,array($username));
		}

		$this->ajaxReturn($results['info'],'json');
	 }

	 /*
	  * 上传头像
	  * */
	public function uploadPicture(){
		if(IS_POST){
	        /* 返回标准数据 */
	        $return  = array('status' => 1, 'info' => '上传成功', 'data' => '');
	
	        /* 调用文件上传组件上传文件 */
	        $Picture = new PictureModel();
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
	/*
	  * 试民首页
	 * @author 老胖子-何必都 <hebiduhebi@126.com>
	  * */
	public function sm_manager(){

        $this->checkLogin();
        $this->checklevel();
        $this->getNoticeForSm();

		$this->display();
	}

    /**
     *
     */
	public function checklevel(){

        $exp = $this->userinfo['exp'];
        if($exp<100){
            $this->assign('level',1);
            $this->assign('exp',$exp-0);
        }else if($exp>=100 && $exp<200){
            $this->assign('level',2);
            $this->assign('exp',$exp-100);
        }else if($exp>=200 && $exp<300){
            $this->assign('level',3);
            $this->assign('exp',$exp-200);
        }else if($exp>=300 && $exp<400){
            $this->assign('level',4);
            $this->assign('exp',$exp-300);
        }else if($exp>=400 && $exp<500){
            $this->assign('level',5);
            $this->assign('exp',$exp-400);
        }else if($exp>=500 && $exp<600){
            $this->assign('level',6);
            $this->assign('exp',$exp-500);
        }else if($exp>=600 && $exp<700){
            $this->assign('level',7);
            $this->assign('exp',$exp-600);
        }

	}


    /*
	 * 获取统计数据
     * @author 老胖子-何必都 <hebiduhebi@126.com>
	 * */

	public function getcount(){


	}

    /**
     * 获取试民的文件信息
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     */
    private function getNoticeForSm(){

        $map = array();

        $map['post_category'] = array('in',array(PostModel::TYPE_NOTICE_FOR_NORMAL_MEMBER,PostModel::TYPE_NOTICE_FOR_ALL));

        $order = " post_modified desc ";

        $page = array('curpage'=>0,'size'=>6);

        $result = apiCall(VPostInfoApi::QUERY,array($map , $page, $order));


        $this->assign('sm_post_list',$result['info']['list']);

    }

    /**
     * 获取最新公告信息
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     */
    private function getLastestNotice(){
        $map = array();
        $order = " post_modified desc ";

        $result = apiCall(PostApi::GET_INFO,array($map, $order));
        $this->assign('zxgg',$result['info']);
    }
}

