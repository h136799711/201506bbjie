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
use Home\Api\LevelAuthApi;

class SjController extends HomeController {

    protected $level_auth ;

    protected function _initialize(){
        parent::_initialize();
        $this->checkLogin();
        $this->checklevel();
        $this->not_read_msg_count();
        $this->getLevelAuth();

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

    private function getLevelAuth(){
        $level  = $this->userinfo['level'];
        $result = apiCall(LevelAuthApi::GET_INFO,array(array('level'=>$level)));
        if($result['status']){
            $this->level_auth = $result['info'];
        }
    }

    /**
     * 是否有多链接权限
     * true 有
     */
    protected function hasMulLink(){
        return $this->level_auth['mul_link'] == 1;
    }

    /**
     * 是否有自定义搜索的权限
     * true 有
     */
    protected function hasCustomSearch(){
        return $this->level_auth['custom_search'] == 1;
    }


    /**
     * 是否有商家发件的权限
     * true 有
     */
    protected function hasSellerDeliver(){
        return $this->level_auth['seller_deliver'] == 1;
    }

    /**
     * 宝贝可以拥有的数量
     *
     */
    protected function getProductNumber(){
        return $this->level_auth['product_num'];
    }

    /**
     * 宝贝可以拥有的数量
     *
     */
    protected function getSearchNumber(){
        return $this->level_auth['search_num'];
    }

    /**
     * 任务每天发放的数量
     *
     */
    protected function getTaskNumber(){
        return $this->level_auth['task_num'];
    }
}