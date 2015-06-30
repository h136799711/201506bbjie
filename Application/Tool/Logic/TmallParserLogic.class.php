<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------

namespace Tool\Logic;
/**
 * 天猫信息读取实现
 * @date   2015-06-27
 * @author hbd  <hebiduhebi@163.com>
 */
class TmallParserLogic implements IParserLogic {

    private $url;

    function __construct($url) {
        $this -> url = ($url);
    }

    /**
     * @see IParseLogic
     */
    public function read_detail() {
        $html = file_get_contents($this -> url);
        $html = iconv("gb2312", "utf-8//IGNORE", $html);

        $match = array();
        $return_info = array('title' => '', 'main_img' => '', 'wangwang' => '', );

        $wangwang_pattern = '/<li class="shopkeeper"(.*?)>(.*?)<a(.*?)>(.*?)<\/a>?/is';
        preg_match($wangwang_pattern, $html, $match);

        $return_info['wangwang'] = $match[4];

        $title_pattern = '/<meta name="keywords" content="(.*?)"(.*?)\/>/is';
        preg_match($title_pattern, $html, $match);
        $return_info['title'] = $match[1];

        $mainimg_pattern = '/<img id="J_ImgBooth"(.*?)src="(.*?)"(.*?)>/is';
        preg_match($mainimg_pattern, $html, $match);
        $return_info['main_img'] = $match[2];

        return $return_info;
    }



    public function read_search() {
        $url = iconv("gb2312", "utf-8//IGNORE", $this -> url);
        $url_parse = parse_url($url);

        if (strpos($url_parse['host'], "tmall.com") === false) {
            return null;
        }
//		dump($url_parse);
        $output = array();
        //解析查询参数
        parse_str($url_parse['query'], $output);

        $query = "";

        foreach($output as $key=>$vo){
            $query .= '&'.$key.'='.urlencode($vo);
        }

        $html = $this -> curl_get_contents($url_parse['scheme'].'://'.$url_parse['host'].$url_parse['path'].'?'.ltrim($query,"&"));

        $filter = $this -> getFilter($output);
        $location = $this -> getLocation($output);
        $order = $this -> getOrder($output);
        $price = $this -> getPrice($output);

        $propSels = $this -> getPropSels($output, $html);
        $return_info = array(
            'type' => 'tmall',
            'url' => 'http://www.tmall.com',
            'key' => (iconv("gb2312", "utf-8//IGNORE", $output['q'])), //关键词
            'sameStyle' => false,
            'sameSeller' => false,
            'location' => $location,
            'filter' => $filter, //商品筛选字段
            "tab" => "",
            'order' => $order, //商品排序
            'price' => $price,
            'attr' => $propSels, //商品属性

            'pager' => array(),
            'items' => array(),
            'has_find' => false, //是否找到要刷的商品
        );

        return $return_info;
    }

    private function curl_get_contents($url) {
//		var_dump(($url));
        $cookieFile = "";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_COOKIESESSION, true);
        //必须
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
        curl_setopt($ch, CURLOPT_URL, ($url));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_REFERER, "taobao.com");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        //		curl_setopt($ch, CURLOPT_MAXREDIRS,20);
        if (!empty($ip)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:' . $ip, 'CLIENT-IP:' . $ip));
            //构造IP
        }
        $result = curl_exec($ch);

        $curl_errno = curl_errno($ch);
        $curl_error = curl_error($ch);
        curl_close($ch);

        if ($curl_errno > 0) {
            return "";
            //          echo "cURL Error ($curl_errno): $curl_error\n";
        } else {
            $result = iconv("gbk", "utf-8//IGNORE", $result);
            //			dump($result);
            return $result;
            //          echo "Data received\n";
        }

    }

    private function getPropSels($output, $html) {
//		var_dump($html);
        $result = array();
        $match = array();
        $crumbs = '/<ul(.*)id="J_CrumbSlideCon"(.*?)>(.*?)<\/ul>/is';

        preg_match($crumbs, $html, $match);
        if(count($match) < 3){
            return $result;
        }
        $crumbs_catch = $match[3];

        $brand_pattern = '/<li(.*)class="crumbAttr"(.*)data-tag="brand"(.*?)title="(.*?)">/is';

        $matchCnt = preg_match($brand_pattern, $crumbs_catch, $match);
//		$result[0]= $match[4];
        if($matchCnt > 0){
            array_push($result,$match[4]);
        }
//		var_dump($crumbs_catch);

        $prop_pattern = '/<li(.*?)data-tag="prop"(.*?)title="(.*?)">/is';
        preg_match_all($prop_pattern, $crumbs_catch, $match);

        for($i=0;$i<count($match[3]);$i++){
            array_push($result,$match[3][$i]);
        }

        return $result;
    }

    private function getPrice($output) {
        $price = array();
        if (isset($output['start_price'])) {
            $price[0] = number_format($output['start_price'], 2);
        }
        if (isset($output['end_price'])) {
            $price[1] = number_format($output['end_price'], 2);
        }
        return $price;
    }

    private function getOrder($output) {
        $order = "";
        switch($output['sort']) {

            case "new" :
                $order = "新品";
                break;
            case "d" :
                $order = "销量";
                break;
            case "p" :
                $order = "价格";
                break;
            case "rq" :
                $order = "人气";
                break;
            case "s" :
                $order = "综合";
                break;
            default :
                $order = "综合";
                break;
        }
        return $order;
    }

    private function getLocation($output) {
        if (isset($output['sarea_code'])) {

            $code = $output['sarea_code'];
            $map['cityID'] = $code;
            $loc = D('City') -> where($map) -> find();
            if (empty($city)) {
                $map['provinceID'] = $code;
                $loc = D('Province') -> where($map) -> find();
                if (is_array($loc)) {
                    return $loc['province'];
                }
            } else {
                return $loc['city'];
            }

        } else {
            //TODO：从页面读取默认收货地
        }

        return "";
    }

    private function getFilter($output) {
        $filter = "";
        if ($output['wwonline']) {
            $filter = "旺旺在线,";
        }
        if ($output['post_fee'] == -1) {
            $filter .= "包邮,";
        }
        if ($output['miaosha'] == 1) {
            $filter .= "折扣,";
        }
        if ($output['support_cod'] == 1) {
            $filter .= "货到付款,";
        }
        if ($output['filter_mj'] == 1) {
            $filter .= "满就减,";
        }
        if ($output['combo'] == 1) {
            $filter .= "搭配减价,";
        }

        return $filter;
    }

}
