<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Shop\Api;

use Common\Api\Api;
use Common\Model\WxproductModel;

class WxproductApi extends Api{

	const SET_DEC='Admin/Wxproduct/setDec';

	protected function _init(){
		$this->model = new WxproductModel();
	}
	
	/**
	 * 查询数据 包含店铺信息
	 */
	public function queryWithWxstore($name,$type,$page,$order,$params){
		
		
		$query = $this->model;//->field("")->alias(" pd ")->join("LEFT JOIN __WXSTORE__ as st on st.id = pd.storeid ");
			
		$sql = "select  pd.name as name ,pd.id,pd.main_img,pd.buy_limit,pd.attrext_ispostfree,pd.attrext_ishasreceipt,pd.attrext_issupportreplace,pd.loc_country,pd.loc_province,pd.loc_city,pd.loc_address,pd.has_sku,pd.ori_price,pd.price,pd.quantity,pd.product_code,pd.cate_id,
		pd.createtime,pd.updatetime,pd.onshelf,pd.status,pd.storeid,pd.properties,pd.sku_info,pd.detail,st.uid,st.name as storename,st.desc,st.isopen,st.logo,st.banner,st.wxno,st.exp";
		$sql .= " from __WXPRODUCT__ as pd LEFT JOIN __WXSTORE__ as st on st.id = pd.storeid  ";
		if($type == '1'){
			$whereName = " pd.name ";
		}else{
			$whereName = "st.name";
		}
		$sql .= " where pd.onshelf = ".\Common\Model\WxproductModel::STATUS_ONSHELF;
		if(!empty($name)){
			$sql .= " and  $whereName like '%".$name."%' ";
		}
		$sql .= ' order by '. $order;
//		if(!($order === false)){
//			$query = $query->order($order);
//		}
		$sql .= " LIMIT ".(intval($page['curpage'])*$page['size']) . ',' . $page['size'];
		
		$list = $query->query($sql);
		
		
		if ($list === false) {
			$error = $this -> model -> getDbError();
			return $this -> apiReturnErr($error);
		}
		$countSql = " select count(*) as cnt from __WXPRODUCT__ as pd LEFT JOIN __WXSTORE__ as st on st.id = pd.storeid   ";
		if(!empty($name)){
			$countSql .= " where $whereName like '%".$name."%'";
		}
		
		$count = $query->query($countSql);
		$count = $count[0]['cnt'];
		// 查询满足要求的总记录数
		$Page = new \Think\Page($count, $page['size']);
		
		// 分页跳转的时候保证查询条件
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

