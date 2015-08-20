<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------


namespace Tool\Logic;
/**
 * 淘宝信息读取实现
 * @date   2015-06-27
 * @author hbd  <hebiduhebi@163.com>
 */
class TaobaoParserLogic implements IParserLogic{

    private $url;

    private $has_find;

    function __construct($url){
        $this->url = $url;
        $this->has_find = 0;
    }

    /**
     * @see IParseLogic
     * 继承
     */
    public function read_search(){
        if(empty($this->url)){
            return null;
        }

        //需要查找的商品
        $findProduct = array();
        $findProduct['title'] = I("post.title","");
        $findProduct['main_img'] = I("post.main_img","");
        $findProduct['wangwang'] = I("post.wangwang","");
        //前3位信息
//		$findProduct['shortWW'] = mbsubstr($findProduct['wangwang'], 0,3);

        //解析链接
        $url_parse = parse_url($this->url);
        if(strpos($url_parse['host'],"taobao.com") === false){
            return null;
        }

        $output = array();
        //解析查询参数
        parse_str($url_parse['query'],$output);

        $this->url = html_entity_decode(urldecode($this->url));
        $this->url = str_replace(" ","+",$this->url);

        $html = file_get_contents( htmlspecialchars_decode($this->url));
//		$html = iconv("gb2312", "utf-8//IGNORE",$html);
        $match = array();

        $g_page_config_pattern = '/g_page_config\s=\s(.*?)};/is';

        preg_match($g_page_config_pattern, $html,$match);

//		dump($match);

        $g_page_config = json_decode($match[1].'}',TRUE);
        //解析失败
        if(empty($g_page_config)){
            return null;
        }

//		unset($g_page_config['pageName']);
//		unset($g_page_config['map']);
//		unset($g_page_config['feature']);

        $mainInfo = $g_page_config['mainInfo'];
        $mods = $g_page_config['mods'];
        $items = $mods['itemlist'];//商品集合
        $sortbar = $mods['sortbar'];
        $nav = $mods['nav'];
        $tab = $mods['tab'];
        $breadcrumbs = $nav['data']['breadcrumbs'];

        $propSels = $this->getPropsSelect($breadcrumbs);

//		unset($mods['sortbar']);
//		unset($mods['nav']);
//		unset($mods['itemlist']);
//		
//		unset($mods['navtablink']);
//		unset($mods['shopstar']);
//		unset($mods['shopcombotip']);
//		unset($mods['personalbar']);
//		unset($mods['vbaby']);
//		unset($mods['tab']);
//		unset($mods['choosecar']);
//		unset($mods['phonenav']);
//		unset($mods['related']);
//		unset($mods['feedback']);
//		unset($mods['supertab']);
//		unset($mods['spuseries']);
//		unset($mods['p4p']);
//		unset($mods['debugbar']);
//		unset($mods['noresult']);
//		unset($mods['spucombo']);
//		unset($mods['tbcode']);
//		unset($mods['apasstips']);
//		unset($mods['sc']);
//		unset($mods['header']);
//		unset($mods['shopcombo']);

        $filter = $this->getFilter($sortbar['data']['filter']);
//		unset($sortbar['data']['filter']);
//		unset($sortbar['data']['pager']);

        $sameStyle = $sortbar['data']['sameStyle']['isActive'];
        $sameSeller = $sortbar['data']['sameSeller']['isActive'];
        $location = $this->getLocation($sortbar['data']['location']);
        $order = $this->getOrder($sortbar['data']['sortList']);
        $price = rtrim(str_replace("reserve_price[","",$output['filter']),"]");
        $price = explode(",", $price);

        $format_item = $this->getFormatItems($items['data']['auctions'],$findProduct);


        $return_info = array(
            'type'=>'taobao',
            'url'=>'http://www.taobao.com',
            'key'=> $output['q'],//关键词
            'attr'=>$propSels,//商品属性
            'filter'=>$filter,//商品筛选字段
            'order'=>$order,//商品排序
            "tab"=>$this->getTabs($tab['data']['tabs']),
            'pager'=>$mods['pager']['data'],
            'sameStyle'=>$sameStyle,
            'sameSeller'=>$sameSeller,
            'location'=>$location,
            'price'=>$price,
            'items'=>$format_item,
            'has_find'=>$this->has_find,//是否找到要刷的商品
        );

        return $return_info;
    }

    private function getFormatItems($items,$findProduct){
        $format = array();
        foreach($items as $vo){
            if($vo['nick'] == $findProduct){
                $this->has_find = 1;
            }
//			dump($vo);
            array_push($format,array(
                'title'=>$vo['raw_title'],
                'pic_url'=>"http://".ltrim($vo['pic_url'],"\/\/"),
                'nick'=>$vo['nick'],
                'view_price'=>ltrim($vo['view_price'],"\/\/"),
                'shopLink'=>"http://".ltrim($vo['shopLink'],"\/\/"),
                'detail_url'=>"http://".ltrim($vo['detail_url'],"\/\/"),
            ));
        }

        return $format;

    }

    private function getPropsSelect($props){
        $format_prop = array();
        foreach($props['propSelected'] as $vo){
            $vals = array();
            foreach($vo['sub'] as $sub){
                array_push($vals,$sub['text']);
            }

            array_push($format_prop,$vo['text'].':'.implode(",", $vals));
//			array_push($format_prop,array(
//				'name'=>$vo['text'],
//				'_vals'=>$vals,
//			));
        }

        return $format_prop;
    }

    private function getLocation($location){
//		dump($location);
        return $location['active'];
    }

    /**
     * 获取价格区间
     */
//	private function getPrice($sortlist){
//		if(isset($sortlist['end']) ){
//			$start = $sortlist['start'];
//			if(empty($start)){
//				$start = 0;
//			}
//			return array("start"=>$start,"end"=>$sortlist['end']);
//		}
//		
//		return array();
//	}

    /**
     * 排序
     */
    private function getOrder($order){

        foreach($order as $vo){
            if($vo['isActive']){
                return $vo['tip'];
            }
        }
    }

    /**
     * 获取选中的过滤条件
     */
    private function getFilter($filter){
        $title = "";
        foreach($filter as $vo){
            if($vo['isActive']){
                if(!empty($title)){
                    $title .= ",";
                }
                $title .= $vo['title'];
            }
        }
        return $title;
    }


    /**
     * 获取选中的标签页
     */
    private function getTabs($tabs){
        foreach($tabs as $vo){
            if($vo['isActive']){
                return $vo['text'];
            }
        }
    }


    /**
     * @see IParseLogic
     * 继承
     */
    public function read_detail(){

        if(empty($this->url)){
            return null;
        }

        $html = file_get_contents($this->url);
        $html = iconv("gb2312", "utf-8//IGNORE",$html);
        $match = array();
        $return_info = array(
            'title'=>'',
            'main_img'=>'',
            'wangwang'=>'',
        );
        //旺旺读取正则
        $wangwang_pattern = '/<a class="tb-seller-name" (.*?)>(.*?)<\/a>/is';
        preg_match($wangwang_pattern, $html,$match);

        $return_info['wangwang'] = $match[2];
        //主图读取正则
        $mainimg_pattern = '/<img id="J_ImgBooth"(.*?)data-src="(.*?)"(.*?)>/is';

        preg_match($mainimg_pattern, $html,$match);
        $return_info['main_img'] = $match[2];
        //商品标题读取正则
        $title_pattern = '/<meta name="keywords" content="(.*?)"(.*?)\/>/is';
        preg_match($title_pattern, $html,$match);

        $return_info['title'] = $match[1];

        return $return_info;
    }


}
