<?php


namespace Admin\Controller;
use Org\File;

/**
 * 在线更新
 * @author	hebidu <hebiduhebi@126.com>
 */
class UpdateController extends AdminController{
	
	/**
	 * 初始化页面
	 * @author	hebidu <hebiduhebi@126.com>
	 */
	public function index(){
		if(IS_POST){
			
		}else{
//			import('Org/PclZip');
//			$zipPath = RUNTIME_PATH."Update/20150530083751/update.zip";
//			if(is_file($zipPath)){
//				dump("file");
//			}else{
//				dump($zipPath);
//			}
//			$zip = new \PclZip($zipPath);
//			$res = $zip->extract(PCLZIP_OPT_PATH,"./");
//			dump($res);
//			if($res === 0){
//				dump($zip->errorInfo(true));
//			}
			$this->checkVersion();
			$this->display();
		}
	}
	
	
	/**
	 * 检查新版本
	 * @author	hebidu <hebiduhebi@126.com>
	 */
	private function checkVersion(){
		if(extension_loaded('curl')){
			
			$version = C('UPGRADE_APP_VERSION');

			$auth_key = urlencode(C('UPGRADE_AUTH_KEY'));
			$domain = C('UPGRADE_AUTH_DOMAIN');
			$data = array("auth_key"=>$auth_key,"version"=>$version);//$auth_key/version/$version
			$header = array(
				"Accept-Charset:utf-8",
				"Referer:".$domain,
			);
			$result = $this->curlPost(C("UPGRADE_URL"),$data,$header);
			
//			dump($result);
//			$result = json_decode($result);
			if($result['status'] === false){
				$this->assign("msg",$result['info']);
				return ;
			}else{
				$result = json_decode($result['info']);
			}
			
			if($result->errcode === 1){
				if(is_null($result->info)){
					$result->info = array();
				}
				$this->assign("pkglist",$result->info);
			}
			
			if($result->errcode > 0){
				$this->assign("msg",$result->info);
				return ;
			}
			
			
		}else{
			$this->error('请配置支持curl');
		}
	}
	
	public function view(){
		if(IS_GET){
			$version = I('get.version',0);
			$pkg_url = I('get.pkg_url','');
			
			$this->assign("version",$version);
			$this->assign("pkg_url",$pkg_url);
			$this->display();
		}else{
			$version = I('get.version',0);
			$pkg_url = I('get.pkg_url','');
			
			$this->assign("version",$version);
			$this->assign("pkg_url",$pkg_url);
			$this->display();
			$this->update($version,urldecode($pkg_url));
		}
	}

	/**
	 * 在线更新
	 * @author	hebidu <hebiduhebi@126.com>
	 */
	private function update($version,$updatedUrl){
		//PclZip类库不支持命名空间
		import('Org/PclZip');
//		
		$date  = date('YmdHis');
//		$backupFile = I('post.backupfile');
//		$backupDatabase = I('post.backupdatabase');
		
		sleep(1);
//		
		$this->showMsg('系统原始版本:'.C('UPGRADE_APP_VERSION'));
		$this->showMsg('系统在线更新日志：');
		$this->showMsg('更新开始时间:'.date('Y-m-d H:i:s'));
		sleep(1);
//		/* 建立更新文件夹 */
		$folder = $this->getUpdateFolder();
		$result = File::mk_dir($folder.'/');
		if($result === false){
			$this->showMsg('更新文件夹创建失败，请检测'.dirname($folder).'目录是否有写权限！');
			exit();
		}
		
		$folder = $folder.'/'.$date;
		$result = File::mk_dir($folder.'/');
		
		if($result === false){
			$this->showMsg('更新文件夹创建失败，请检测'.dirname($folder).'目录是否有写权限！');
			exit();
		}
//
//		//备份重要文件
//		if($backupFile){
		$this->showMsg('开始备份重要程序文件...');
		G('start1');
		$backupallPath = $folder.'/backupall.zip';
		$zip = new \PclZip($backupallPath);
		$zip->create('Application,Core,index.php');
		$this->showMsg('成功完成重要程序备份,备份文件路径:<a href=\''.__ROOT__.$backupallPath.'\'>'.$backupallPath.'</a>, 耗时:'.G('start1','stop1').'s','success');
//		}
//
//		/* 获取更新包 */
//		
//		//下载并保存
		$this->showMsg('远程更新包地址: '.$updatedUrl);
		$this->showMsg('开始获取远程更新包...');
		sleep(1);
		$zipPath = $folder.'/update.zip';
		$downZip = $this->getRemoteUrl($updatedUrl);
//		dump($downZip);
		if(empty($downZip)){
			$this->showMsg('下载更新包出错，请重试！', 'error');
			exit;
		}
		
		File::write_file($zipPath, $downZip);
		$this->showMsg('获取远程更新包成功,更新包路径：<a href=\''.__ROOT__.ltrim($zipPath,'.').'\'>'.$zipPath.'</a>', 'success');
		sleep(1);
//		
		/* 解压缩更新包 */ //TODO: 检查权限
		$this->showMsg('更新包解压缩...');
		sleep(1);
		$zip = new \PclZip($zipPath);
		$res = $zip->extract(PCLZIP_OPT_PATH,"./");
//		$this->showMsg('更新包解压缩成功'.$res, 'success');
		if($res === 0){
			$this->showMsg('解压缩失败：'.$zip->errorInfo(true).'------更新终止', 'error');
			exit;
		}
		$this->showMsg('更新包解压缩成功', 'success');
		sleep(1);

		/* 更新数据库 */
		$updatesql = './update.sql';
		if(is_file($updatesql))
		{
			$this->showMsg('更新数据库开始...');
			if(file_exists($updatesql))
			{
				$Model = M();
				$sql = File::read_file($updatesql);
				$sql = str_replace("\r\n", "\n", $sql);
				foreach(explode(";\n", trim($sql)) as $query)
				{
					$Model->query(trim($query));
				}
			}
			unlink($updatesql);
			$this->showMsg('更新数据库完毕', 'success');
		}
		
		/* 系统版本号更新 */		
		$Model = M('Config',"common_");
		$res = $Model->where(array("name"=>'UPGRADE_APP_VERSION'))->data(array("value"=>$version))->save();
		
		
		if($res === false){
			$this->showMsg('更新系统版本号失败', 'error');
		}else{
			$this->showMsg('更新系统版本号成功', 'success');
		}
		sleep(1);
//		
		$this->showMsg('##################################################################');
		$this->showMsg('在线更新全部完成，如有备份，请及时将备份文件移动至非web目录下！', 'success');
		S("config_" . session_id() . '_' . session("uid"), null);
	}

	/**
	 * 获取远程数据
	 */
	private function getRemoteUrl($url = '', $method = '', $param = ''){
		$opts = array(
			CURLOPT_TIMEOUT        => 20,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_URL            => $url,
			CURLOPT_USERAGENT      => $_SERVER['HTTP_USER_AGENT'],
		);
		if($method === 'post'){
			$opts[CURLOPT_POST] = 1;
			$opts[CURLOPT_POSTFIELDS] = $param;
		}

		/* 初始化并执行curl请求 */
		$ch = curl_init();
		curl_setopt_array($ch, $opts);
		$data  = curl_exec($ch);
		$error = curl_error($ch);
		curl_close($ch);
		return $data;
	}
	

	/**
	 * 实时显示提示信息
	 * @param  string $msg 提示信息
	 * @param  string $class 输出样式（success:成功，error:失败）
	 */
	private function showMsg($msg, $class = ''){
		echo "<script type=\"text/javascript\">showmsg(\"{$msg}\",\"{$class}\")</script>";
		flush();
		ob_flush();
	}

	/**
	 * 生成更新文件夹名
	 */
	private function getUpdateFolder(){
		return RUNTIME_PATH.'Update';
	}
	
	/**
	 * 
	 */
	private function curlPost($url, $data,$header=null) {
		
		$ch = curl_init();
		if(is_null($header)){
			$header = array('Accept-Charset'=>"utf-8");
		}
		
		$header = array_merge(array('Accept-Charset'=>"utf-8"),$header);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, ($data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$tmpInfo = curl_exec($ch);
		$errorno = curl_errno($ch);
		if ($errorno) {
			return array('status' => false, 'info' => $errorno);
		} else {
			return array('status' => true, 'info' => $tmpInfo);
		}
	}
	
}