<?php
namespace Common\Model;
use Think\Model;

class ProductSearchWayModel extends Model{

    /**
     * 关键词搜索
     */
    const SEARCH_TYPE_KEYWORD = "80";

    /**
     * 自定义搜索
     */
    const SEARCH_TYPE_USER_DEFINED = "81";
    /**
     * 手机搜索
     */
    const SEARCH_TYPE_MOBILE = "79";

}
