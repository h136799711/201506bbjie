<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------

namespace Admin\Api;
use \Common\Model\BannersModel;

class BannersApi extends \Common\Api\Api{
	
	protected function _init(){
		$this->model = new BannersModel();
	}	
	
	public function queryWithPosition($map = null, $page = array('curpage'=>0,'size'=>10), $order = false, $params = false){
		
		$field = 'dt.name as position_name,banner.url,banner.noticetime,banner.endtime,banner.starttime,banner.title,banner.createtime,banner.storeid,banner.id,banner.img,banner.position,banner.notes,banner.uid,banner.bgcolor';
		
		$query = $this->model->alias(" as banner ")->field($field)->join('LEFT JOIN common_datatree as dt ON dt.id = banner.position');
		if(!is_null($map)){
			$query = $query->where($map);
		}
		if(!($order === false)){
			$query = $query->order($order);
		}
		
		$list = $query -> page($page['curpage'] . ',' . $page['size']) -> select();
		

		if ($list === false) {
			$error = $this -> model -> getDbError();
			return $this -> apiReturnErr($error);
		}

		$count = $this -> model -> where($map) -> count();
		// 查询满足要求的总记录数
		$Page = new \Think\Page($count, $page['size']);

		//分页跳转的时候保证查询条件
		if ($params !== false) {
			foreach ($params as $key => $val) {
				$Page -> parameter[$key] = urlencode($val);
			}
		}

		// 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page -> show();

		return $this -> apiReturnSuc(array("show" => $show, "list" => $list));
	}
	
}
