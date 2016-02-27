<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 16/2/19
 * Time: 17:17
 */

namespace Home\Controller;


use Admin\Api\MsgboxApi;
use Admin\Model\MsgboxModel;

class SjController extends HomeController {


    protected function _initialize(){
        parent::_initialize();
        $this->checkLogin();
        $this->checklevel();
        $this->not_read_msg_count();

        $this->assign('auth_status',$this->userinfo['auth_status']);
    }

    /*
     * 获得未读消息
     * */
    private function not_read_msg_count(){
        $cnt = S('c_msg_not_read_cnt');
        if(is_null($cnt) || $cnt === false){
            $result = apiCall(MsgboxApi::COUNT_BY_UID_AND_MSG_STATUS,array(UID,MsgboxModel::NOT_READ));

            if($result['status']){
                $cnt = ($result['info']);
            }else{
                $cnt = 0;
            }
            S('c_msg_not_read_cnt',$cnt,300);
        }
        $this->assign("not_read_msg_cnt",$cnt);
    }

    /*
     * 检测用户vip等级
     * @author 老胖子-何必都 <hebiduhebi@126.com>
     * @date 2015 12 22日
     * */
    private function checklevel(){

        $exp  = $this->userinfo['exp'];

        if($exp < 100){
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

}