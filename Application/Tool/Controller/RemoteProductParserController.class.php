<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------


namespace Tool\Controller;

use Think\Controller;

class RemoteProductParserController extends Controller{

    public function read_search(){
        if(IS_POST){
            $url = I('post.url','','urldecode');
			//dump($url);
            $parser = null;
            $which = $this->whichUrl($url);
            switch($which){

                case 1:
                    $parser = new \Tool\Logic\TaobaoParserLogic($url);
                    break;
                case 2:
                    $parser = new \Tool\Logic\TmallParserLogic($url);
                    break;
                default:
                    $this->error("请输入正确的商品详情页地址!");
                    break;
            }

            $return_info = $parser->read_search();

            if(is_null($return_info)){
                $this->error("无法识别此链接!");
            }

            $return_info['ori_url'] = $url;
           	//dump($return_info);
            if(IS_AJAX){
//                var_dump($return_info);
                $this->success($return_info);
            }else{
                $this->assign("return_info",$return_info);
                $this->display();
            }

        }else{
            $this->display();
        }

    }




    /**
     * 读取商品页面信息
     */
    public function read(){
        if(IS_POST){
            $url = I('post.url','','urldecode');
			
            $url_parse = parse_url($url);
//			dump(htmlspecialchars_decode($url_parse['query']));
            $output = array();
            parse_str(($url_parse['query']),$output);
//			dump($output);
            $recombineUrl = $url_parse['scheme'].'://'.$url_parse['host'].$url_parse['path'];

            if(is_array($output) && $output['id']){
                $recombineUrl .= "?id=".$output['id'];
            }
            $parser = null;
            $which = $this->whichUrl($url);

            switch($which){

                case 1:
                    $parser = new \Tool\Logic\TaobaoParserLogic($recombineUrl);
                    break;
                case 2:
                    $parser = new \Tool\Logic\TmallParserLogic($recombineUrl);
                    break;
                default:
                    $this->error("请输入正确的商品详情页地址!");
                    break;
            }

            $return_info = $parser->read_detail();
			

            if(is_null($return_info) || empty($return_info['title']) || empty($return_info['main_img']) || empty($return_info['wangwang']) )	{
                $this->error("无法识别此链接!");
            }

            $return_info['url'] = $recombineUrl;

            $this->success($return_info);


        }else{
            $this->display();
        }
    }

    /**
     * 判断是什么链接，淘宝？天猫？
     * 检测规则
     * 1. 域名 是否taobao.com|tmall.com
     * 2. ...
     * 3. ...
     */
    private function whichUrl($url){
        //TODO: 是否为合法的淘宝商品详情页链接
        if(!(strpos($url, "tmall.com") === false)){
            return 2;
        }
        if(!(strpos($url, "taobao.com") === false)){
            return 1;
        }

        return 0;
    }

}
