<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 青 <99701759@qq.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Home\Controller;
use Think\Controller;
use Think\Storage;
use Home\Api\HomePublicApi;
/*
 * 资金提现
 */
class SJActivityController extends HomeController {

	/*
	 * 淘宝活动
	 * */
	public function sj_tbhd() {
		$headtitle = "宝贝街-活动";
		$this -> assign('head_title', $headtitle);
		$user = session('user');
		$this -> assign('username', $user['info']['username']);
		$this -> display();
	}

	/*
	 * 任务书
	 * */
	public function rws() {
		$headtitle = "宝贝街-任务书";
		$this -> assign('head_title', $headtitle);
		$user = session('user');
		$this -> assign('username', $user['info']['username']);
		$this -> display();
	}

	/*
	 * 创建任务第一步
	 * */
	public function activity_1() {
		$user = session('user');
		$headtitle = "宝贝街-创建任务";
		$this -> assign('head_title', $headtitle);
		$this -> assign('username', $user['info']['username']);
		$map = array('uid' => $user['info']['id'], 'status' => 1);
		$pro = apiCall(HomePublicApi::Product_Query, array($map));
		$this -> assign('pros', $pro['info']);
		//		dump($pro);
		$this -> display();
	}
	public function yongjin(){
		/*
		 * 佣金表数组
		 * */
		 $user=session('user');
		 $map = array('uid' => $user['info']['id']);
		 $sj = apiCall(HomePublicApi::Bbjmember_Seller_Query, array($map));
		 $pros=session('al');
		 
		 $geshu=count($pros['id']);
		 $vip=$sj['info'][0]['vip_level'];
		 $count = 0;
		 $sum=0;
		 foreach ($pros as $key => $value) {
			if ($count < $geshu) {
				$money=$pros['price'][$count];
				 $zk=1;
				 $yongjin=4;
				 for ($a=0 ; $a<=2000 ;$a+=50) {
					if($money<$a){
						if($geshu==1 && $vip==0){
							$yongjin=$yongjin*$zk;
						}else if($geshu==1 && $vip==1){
							$zk=0.05;
							$yongjin=$yongjin-($yongjin*$zk);
						}else if($geshu==1 && $vip==2){
							$zk=0.1;
							$yongjin=$yongjin-($yongjin*$zk);
						}else if($geshu==2 && $vip==1){
							$zk=0.35;
							$yongjin=$yongjin-($yongjin*$zk);
						}else if($geshu==2 && $vip==2){
							$zk=0.4;
							$yongjin=$yongjin-($yongjin*$zk);
						}else if($geshu==3 && $vip==1){
							$zk=0.5;
							$yongjin=$yongjin-($yongjin*$zk);
						}else if($geshu==3 && $vip==2){
							$zk=0.55;
							$yongjin=$yongjin-($yongjin*$zk);
						}
						$sum=$sum+$yongjin;
						break;
					}
					$yongjin=$yongjin+1;
				}
			} 
			$count=$count+1;
		 }
		$this->assign('yongjin',$sum);
//		$this->display('activity_2');
	}

	/*
	 * 保存商品信息
	 * */
	public function a1() {
		$al = array('id' => I('id', ''), 'title' => I('title', ''), 'num' => I('num', 1), 'price' => I('price'), 'position' => I('guige', '无'), );
		session('al', $al);
		$user = session('user');
		$count = 0;
		foreach ($al as $key => $value) {
			if ($count < count($al['id'])) {
				$map = array('uid' => $user['info']['id']);
				$sj = apiCall(HomePublicApi::Bbjmember_Seller_Query, array($map));
				if ($al['id'] != '' && $al['id1'] == '' && $al['id2'] == '') {
					$entity = array('uid' => $user['info']['id'], 'aliwawa' => $sj['info'][0]['aliwawa'], 'delivery_mode' => '', 'create_time' => time(), 'task_gold' => '0.00', 'task_brokerage' => '0.00', 'task_postage' => '0.00', 'update_time' => time(), 'dtree_preferential' => '', 'task_name' => '', 'notes' => '', 'chat_argot' => '', 'task_do_type' => '', 'task_status' => 1, );
					$result = apiCall(HomePublicApi::Task_Add, array($entity));
					if ($result['status']) {
						$task_has = array('task_id' => $result['info'], 'pid' => $al['id'][$count], 'num' => $al['num'][$count], 'sku' => $al['position'][$count], 'pname' => $al['title'][$count], 'create_time' => time(), );
						$ret = apiCall(HomePublicApi::TaskHasProduct_Add, array($task_has));
					}
				}
				$count = $count + 1;
			}
		}
		$this->yongjin();
		$this -> display('activity_2');
	}

	/*
	 * 创建任务第二步
	 * */
	public function activity_2() {
		$headtitle = "宝贝街-创建任务";
		$this -> assign('head_title', $headtitle);
		$user = session('user');

		$this -> assign('username', $user['info']['username']);
		$this -> display();
	}

	/*
	 * 创建任务第三步
	 * */
	public function activity_3() {
		$headtitle = "宝贝街-创建任务";
		$this -> assign('head_title', $headtitle);
		$user = session('user');
		$this -> assign('username', $user['info']['username']);
		$this -> display();
	}

	/*
	 * 商品管理
	 * */
	public function productmanager() {
		$headtitle = "宝贝街-商品管理";
		$this -> assign('head_title', $headtitle);
		$user = session('user');
		$map = array('uid' => $user['info']['id'], 'is_on_sale' => 1);
		$mwe = array('uid' => $user['info']['id'], 'is_on_sale' => 0, );
		$sj = apiCall(HomePublicApi::Bbjmember_Seller_Query, array($map));
		$pro = apiCall(HomePublicApi::Product_QueryAll, array($map));
		$prduct = apiCall(HomePublicApi::Product_QueryAll, array($mwe));

		$this -> assign('id', 0);
		$this -> assign('downpro', $prduct['info']['list']);
		$this -> assign('showdown', $product['show']);
		$this -> assign('showall', $pro['show']);
		$this -> assign('products', $pro['info']['list']);
		$this -> assign('aliwawa', $sj['info'][0]['aliwawa']);
		$this -> assign('username', $user['info']['username']);
		$this -> display();
	}

	/*
	 * 商品下架
	 * */
	public function downpro() {
		$id = I('id', 0);
		$map = array('is_on_sale' => 0, );
		$result = apiCall(HomePublicApi::Product_SaveByID, array($id, $map));
		if ($result['status']) {
			$this -> success('更新成功', U('Home/SJActivity/productmanager'));
		} else {
			$this -> error($result['info']);
		}
	}

	/*
	 * 更新商品
	 * */
	public function productedit() {
		$user = session('user');
		$id = I('id', '');
		$mapa = array('id' => $id, );
		if (IS_GET) {
			$pro = apiCall(HomePublicApi::Product_Query, array($mapa));
			$this -> assign('id', $pro['info'][0]['id']);
			$this -> assign('product', $pro['info'][0]);
			$map = array('uid' => $user['info']['id'], 'is_on_sale' => 1);
			$mwe = array('uid' => $user['info']['id'], 'is_on_sale' => 0, );
			$sj = apiCall(HomePublicApi::Bbjmember_Seller_Query, array($map));
			$pro = apiCall(HomePublicApi::Product_QueryAll, array($map));
			$prduct = apiCall(HomePublicApi::Product_QueryAll, array($mwe));
			$this -> assign('downpro', $prduct['info']['list']);
			$this -> assign('showdown', $product['show']);
			$this -> assign('showall', $pro['show']);
			$this -> assign('products', $pro['info']['list']);
			$this -> assign('aliwawa', $sj['info'][0]['aliwawa']);
			$this -> assign('username', $user['info']['username']);
			//				dump($pro['info'][0]);
			$this -> display('productmanager');
		} else {
			$entity = array('price' => I('price', ''), 'position' => trim(I('weizhi', '')), 'update_time' => time(), );

			//				dump($id);
			//dump($entity);
			$result = apiCall(HomePublicApi::Product_SaveByID, array($id, $entity));
			if ($result['status']) {
				$this -> success('更新成功', U('Home/SJActivity/productmanager'));
			} else {
				$this -> error($result['info']);
			}
		}

	}

	/*
	 * 商品搜索管理
	 * TODO:新增搜索
	 * */
	public function productsele() {
		$headtitle = "宝贝街-商品搜索管理";
		$this -> assign('head_title', $headtitle);
		$user = session('user');
		$map = array('uid' => $user['info']['id'], 'status' => 1);
		$mapp = array('uid' => $user['info']['id']);
		$mwe = array('uid' => $user['info']['id'], 'status' => 0);
		//dump($user['info']['id']);
		$product = apiCall(HomePublicApi::Product_QueryAll, array($map));
		$pro = apiCall(HomePublicApi::Product_QueryAll, array($mapp));
		$prduct = apiCall(HomePublicApi::Product_QueryAll, array($mwe));
		$this -> assign('prduct', $prduct['info']['list']);
		$this -> assign('prshow', $prduct['show']);
		$this -> assign('product', $product['info']['list']);
		$this -> assign('prooshow', $product['show']);
		$this -> assign('proshow', $pro['show']);
		$this -> assign('pro', $pro['info']['list']);
		$this -> assign('username', $user['info']['username']);
		//		dump($prduct);
		$this -> display();
	}

	/*
	 * 商品上架
	 * */
	public function uppro() {
		$id = I('id', 0);
		$map = array('is_on_sale' => 1, );
		$result = apiCall(HomePublicApi::Product_SaveByID, array($id, $map));
		if ($result['status']) {
			$this -> success('更新成功', U('Home/SJActivity/productmanager'));
		} else {
			$this -> error($result['info']);
		}
	}

	/*
	 * 商品删除
	 * */
	public function delpro() {
		$id = I('id', 0);
		$map = array('id' => $id, );
		$result = apiCall(HomePublicApi::Product_Del, array($map));
		if ($result['status']) {
			$this -> success('删除成功', U('Home/SJActivity/productmanager'));
		} else {
			$this -> error($result['info']);
		}
	}

	/*
	 * 获取商品信息
	 * */
	public function addproduct() {
		$user = session('user');
		$gaoji = array('xinghao1' => I('xinghao1', ''), 'price1' => I('price1', ''), 'xinghao2' => I('xinghao2', ''), 'price2' => I('price2', ''), 'xinghao3' => I('xinghao3', ''), 'price3' => I('price3', ''), );
		$a = serialize($gaoji);
		if ($gaoji['xinghao1'] == '' && $gaoji['xinghao2'] == '' && $gaoji['xinghao3'] == '') {
			$entity = array('uid' => $user['info']['id'], 'link' => I('post.url', ''), 'price' => I('post.price', ''), 'has_model_num' => 0, 'position' => trim(I('post.weizhi', '')), 'title' => I('title', ''), 'main_img' => I('img', ''), 'wangwang' => I('alww', ''), 'create_time' => time(), 'update_time' => time(), 'status' => 1, 'model_num_cfg' => '', 'is_on_sale' => 1, );
			$result = apiCall(HomePublicApi::Product_Add, array($entity));
			if ($result['status']) {
				$this -> success('商品添加成功', U('Home/SJActivity/productmanager'));
			} else {
				$this -> error($result['info']);
			}
		} else {
			$entity = array('uid' => $user['info']['id'], 'link' => I('post.url', ''), 'price' => I('post.price', ''), 'has_model_num' => 1, 'position' => trim(I('post.weizhi', '')), 'title' => I('title', ''), 'main_img' => I('img', ''), 'wangwang' => I('alww', ''), 'create_time' => time(), 'update_time' => time(), 'status' => 1, 'model_num_cfg' => $a, 'is_on_sale' => 1, );
			$result = apiCall(HomePublicApi::Product_Add, array($entity));
			if ($result['status']) {
				$this -> success('商品添加成功', U('Home/SJActivity/productmanager'));
			} else {
				$this -> error($result['info']);
			}
		}

		//		dump($entity);
	}

	/**
	 * 读取商品页面信息
	 */
	public function read() {
		if (IS_POST) {
			$url = I('post.url', '', 'urldecode');
			$which = $this -> whichUrl($url);
			switch($which) {
				case 1 :
					$return_info = $this -> getTaobao($url);
					break;
				case 2 :
					$return_info = $this -> getTmall($url);
					break;
				default :
					$this -> error("请输入正确的淘宝商品详情页地址!");
					break;
			}
			if (empty($return_info['title']) || empty($return_info['main_img']) || empty($return_info['wangwang'])) {
				$this -> error("无法识别此链接!");
			}

			$this -> success($return_info);

		} else {
			$this -> display();
		}
	}

	/*
	 * */

	/**
	 * 判断是什么链接，淘宝？天猫？
	 * 检测规则
	 * 1. 域名 是否taobao.com|tmall.com
	 * 2. ...
	 * 3. ...
	 */
	private function whichUrl($url) {
		//TODO: 是否为合法的淘宝商品详情页链接
		if (!(strpos($url, "tmall.com") === false)) {
			return 2;
		}
		if (!(strpos($url, "taobao.com") === false)) {
			return 1;
		}

		//
		return 0;
	}

	private function getTmall($url) {
		$html = file_get_contents($url);
		$html = iconv("gb2312", "utf-8//IGNORE", $html);
		$match = array();
		//			dump($html);
		$return_info = array('title' => '', 'main_img' => '', 'wangwang' => '', );

		$wangwang_pattern = '/<li class="shopkeeper"(.*?)>(.*?)<a(.*?)>(.*?)<\/a>?/is';
		preg_match($wangwang_pattern, $html, $match);

		//			var_dump($match);
		$return_info['wangwang'] = $match[4];

		$title_pattern = '/<meta name="keywords" content="(.*?)"(.*?)\/>/is';
		//			dump($html);
		preg_match($title_pattern, $html, $match);
		//			if($match){
		$return_info['title'] = $match[1];

		$mainimg_pattern = '/<img id="J_ImgBooth"(.*?)src="(.*?)"(.*?)>/is';

		preg_match($mainimg_pattern, $html, $match);
		$return_info['main_img'] = $match[2];

		return $return_info;

	}

	private function getTaobao($url) {

		$html = file_get_contents($url);
		$html = iconv("gb2312", "utf-8//IGNORE", $html);
		//			var_dump($html);
		$match = array();

		$return_info = array('title' => '', 'main_img' => '', 'wangwang' => '', );
		$wangwang_pattern = '/<a class="tb-seller-name" (.*?)>(.*?)<\/a>/is';
		//echo $html;
		preg_match($wangwang_pattern, $html, $match);

		//var_dump($match[2]);
		$return_info['wangwang'] = $match[2];
		$mainimg_pattern = '/<img id="J_ImgBooth"(.*?)data-src="(.*?)"(.*?)>/is';

		preg_match($mainimg_pattern, $html, $match);
		$return_info['main_img'] = $match[2];

		//			$title_pattern = '/<h3 class="tb-main-title" data-title="(.*?)"(.*?)>/is';
		$title_pattern = '/<meta name="keywords" content="(.*?)"(.*?)\/>/is';
		//			dump($html);
		preg_match($title_pattern, $html, $match);

		$return_info['title'] = $match[1];

		return $return_info;
	}

	//	public function () {
	//		$money = I('money', '0.000');
	//		$skzh = I('skzh', '');
	//		$jy_num = I('jy_num', '');
	//		$jypz = I('jypz', '');
	//		$user = session('user');
	//		$entity = array('uid' => $user['info']['id'], 'income' => '000', 'defray' => $money . '.000', 'create_time' => time(), 'notes' => '用于提现', 'dtree_type' => 3, 'status' => 2, );
	//		$result = apiCall(HomePublicApi::FinAccountBalanceHis_Add, array($entity));
	//		if ($result['status']) {
	//			$map = array('uid' => $user['info']['id'], );
	//			$id = $user['info']['id'];
	//			$rets = apiCall(HomePublicApi::Bbjmember_Seller_Query, array($map));
	//			$ap = array('coins' => $rets['info'][0]['coins'] - $money, );
	//			$return = apiCall(HomePublicApi::Bbjmember_Seller_SaveByID, array($id, $ap));
	//			if ($return['status']) {
	//				$this -> success('你的充值请求已经提交，正在审核...', U('Home/Usersj/sj_zjgl'));
	//			}
	//			//
	//		}
	//	}

	//创建搜索
	public function createsearch() {
		$headtitle = "宝贝街-创建搜索";
		$this -> assign('head_title', $headtitle);
		$user = session('user');
		/*	$product=apiCall(HomePublicApi::Product_QueryAll, array($map));
		 $this->assign('prduct',$prduct['info']['list']);*/
		$this -> assign('username', $user['info']['username']);

		$this -> display();
	}

	/**
	 * 创建搜索中的下拉框信息
	 */
	public function select() {
		$user = session('user');
		$map = array('uid' => $user['info']['id'], 'status' => 1, 'title' => array('like', "%" . I('q', '', 'trim') . "%"),
		//'link'=>array('like', "%" . I('q', '', 'trim') . "%"),
		//'_logic' =>'OR',
		);
		$result = apiCall(HomePublicApi::Product_QueryAll, array($map));
		$this -> success($result['info']['list']);

		/*
		 $map['nickname'] = array('like', "%" . I('q', '', 'trim') . "%");
		 $map['id'] = I('q', -1);
		 $map['_logic'] = 'OR';
		 $page = array('curpage' => 0, 'size' => 20);
		 $order = " subscribe_time desc ";

		 $result = apiCall("Admin/Wxuser/query", array($map, $page, $order, false, 'id,nickname,avatar,openid'));
		 */
		/*if ($result['status']) {
		 $list = $result['info']['list'];
		 $this -> success($list);
		 } else {
		 LogRecord($result['info'], __LINE__);
		 }*/
	}
<<<<<<< HEAD

=======
	
	
	public function save(){
		/*$entity=array(
			'store_name'=>I('post.dpname',''),
			'aliwawa'=>I('alww',''),
			'linkman_qq'=>I('post.qq'),
			'linkman'=>I('post.lxr'),
			'address'=>I('post.jydz'),
		);
		$result1 = apiCall(HomePublicApi::Bbjmember_Seller_SaveByID, array($id,$entity));
		if ($result1['status']) {
			$headtitle="宝贝街-登录";
			$this->assign('head_title',$headtitle);
			$this->display('login');
		}*/
		I('pid');
		I('search_url');
		I('search_q');
	}
	
>>>>>>> branch 'master' of https://github.com/h136799711/201506bbjie.git
}
