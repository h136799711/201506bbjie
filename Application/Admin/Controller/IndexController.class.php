<?php
/**
 * (c) Copyright 2014 hebidu. All Rights Reserved. 
 */

namespace Admin\Controller;

use Admin\Api\AuthGroupAccessApi;
use Admin\Model\AuthGroupModel;
use Ucenter\Model\AuthGroupAccessModel;

class IndexController extends AdminController {

	//首页
    public function index(){

        $user = session("global_user");
        $uid = $user['id'];

        $result = apiCall(AuthGroupAccessApi::QUERY_GROUP_INFO,array($uid));

        if($result['status']){

            $group_info = $result['info'];
            dump($group_info);

            foreach($group_info as $vo){

                if($vo['group_id'] == AuthGroupModel::DISTRIBUTOR_GROUP_ID){

                    $this->redirect("Admin/Distributor/my");

                }

            }

        }

        $this->display();
    }
	
}