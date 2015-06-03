<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Admin\Api;
use Common\Api\Api;
use Common\Model\CategoryPropModel;

class CategoryPropApi extends Api{
		
	protected function _init(){
		$this->model = new CategoryPropModel();	
	}
	
	
	public function queryPropTable($map){
				
		$result = $this->model->where($map)->select();	
		
		if($result === false){
			return $this->apiReturnErr($this->model->getError());
		}
		
		$propvalueApi = new CategoryPropvalueApi();
		$return = array();
		foreach($result as $prop){
			$one = array(
				'id'=>$prop['id'],
				'name'=>$prop['propname'],
				'property_value'=>array()
			);
			$map = array('prop_id'=>$prop['id']);
			$propvalue = $propvalueApi->queryNoPaging($map);
			if($propvalue['status']){
				$one['property_value'] = $propvalue['info'];
			}else{
				return $this->apiReturnErr($propvalue['info']);
			}
			array_push($return,$one);
		}
		
		return $this->apiReturnSuc($return);
		
	}
	
}


