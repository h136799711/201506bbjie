<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 15/12/16
 * Time: 11:44
 */

namespace Home\Widget;


use Cms\Api\PostApi;
use Think\Controller;

class PublicWidget extends Controller {


    public function notice(){
        $map = array(
            'post_category'=>array('in','31,41,44'),
        );
        $page = array('curpage'=>1,'size'=>10);
        $order = "post_modified desc";
        $result = apiCall(PostApi::QUERY,array($map,$page,$order));

        $this->assign("list",$result['info']['list']);


        echo $this->fetch("Widget/public_notice");
    }

    public function avatar_upload(){
        echo $this->fetch("Widget/avatar_upload");
    }

}