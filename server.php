<?php
// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;
class server {
	private $config = array(
        'access_id'  => QINIU_ACCESS_ID,
        'access_key' => QINIU_ACCESS_KEY,
        'timeout'    => QINIU_TIMEOUT, //超时时间
    );

	private $auth = '';

	/**
     * 构造函数，用于设置上传根路径
     * @param array  $config FTP配置
     */
    public function __construct($config) {
    	
        /* 默认FTP配置 */
        $this->config = array_merge($this->config, $config);

		// 构建鉴权对象
		$this->auth = new Auth($this->config['access_id'], $this->config['access_key']);
    }

	 /**
     * 检测上传目录(阿里云上传时支持自动创建目录，直接返回)
     * @param  string $savepath 上传目录
     * @return boolean          检测结果，true-通过，false-失败
     */
    public function checkSavePath($savepath) {
        return true;
    }

	 /**
     * 检测上传根目录(阿里云上传时支持自动创建目录，直接返回)
     * @param string $rootpath   根目录
     * @return boolean true-检测通过，false-检测失败
     */
    public function checkRootPath($rootpath) {
        /* 设置根目录 */
        $this->rootPath = trim($rootpath, './') . '/';
        return true;
    }
    
   public function save($key='',$filePath=''){
		// 生成上传 Token
		$token = $this->auth->uploadToken($this->config['bucket']);
		
		// 要上传文件的本地路径
		#$filePath = './php-logo.png';

		// 上传到七牛后保存的文件名
		#$key = 'my-php-logo.png';

		// 初始化 UploadManager 对象并进行文件的上传。
		$uploadMgr = new UploadManager();

		list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
		if ($err !== null) {
			return $err;
		} else {
			return $ret;
		}
   }

   public function savebin($key,$data,$params = null,$mime = 'application/octet-stream',$checkCrc = false){
		// 生成上传 Token
		
		$token = $this->auth->uploadToken($this->config['bucket']);
		// 初始化 UploadManager 对象并进行文件的上传。
		$uploadMgr = new UploadManager();
		list($ret, $err) = $uploadMgr->put($token,$key,$data,$params,$mime,$checkCrc);
		if ($err !== null) {
			return $err;
		} else {
			return $ret;
		}
   }
   
}

?>