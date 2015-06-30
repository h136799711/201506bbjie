<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------

namespace Tool\Logic;
/**
 * 远程读取信息接口
 */
interface IParserLogic {
    /**
     * 读取商品详情页信息
     * @return array(
    'title'=>'',
    'main_img'=>'',
    'wangwang'=>'',
    );
     */
    public function read_detail();

    /**
     * 读取搜索页信息
     * @return array(
    'type' => 'tmall',
    'url' => 'http://www.tmall.com',
    'key' => urldecode($output['q']), //关键词
    'sameStyle' => false,
    'sameSeller' => false,
    'location' => $location, //发货地，收货地
    'filter' => $filter, //商品筛选字段
    "tab" => "",
    'order' => $order, //商品排序
    'price' => $price,
    'attr' => $propSels, //商品属性
    'pager' => array(), 'items' => array(),
    'has_find' => $this -> has_find, //是否找到要刷的商品
    );
     */
    public function read_search();
}
