<?php

define('QINIU_ACCESS_ID','');
define('QINIU_ACCESS_KEY','MuBP--PuoGFeJspwjV-a7fH0DeRaOY2MwH1');
define('QINIU_TIMEOUT',90);
define('QINIU_BUCKET','');
define('CLOUD_IMG_HOST', ''); //<pushaowei@sporte.cn>


date_default_timezone_set("PRC");

//根据图片ID拼接出图片路径
function getRelPath($hashStr)
{
	$relativePath = FALSE;
	if(strlen($hashStr) == 32) {
		$relativePath = substr($hashStr, 0, 2) . "/" . substr($hashStr, 2, 2) . "/" . substr($hashStr, 4, 2) . "/" . substr($hashStr, 6);
	}
	return $relativePath;
}

//返回给客户端错误信息
function sendResult($code,$error){
	
	$return = array(
		'code'=>$code,
		'error'=>$error,
	);
	echo json_encode($return);
	exit;
}