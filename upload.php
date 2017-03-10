<?php
error_reporting(E_ERROR);
header("Content-Type: text/html; charset=utf-8");
$action = $_GET['action'];
switch ($action) {
    case 'config':
        $result = require_once( realpath(dirname(__FILE__).'/conf/').'/ueditor.config.php');
        break;
    /* 上传图片 */
    case 'uploadimage':
            $fileFieldName = $_POST['fileFieldName'] ? : 'file';
            //3M的大小
            $response = UploadModule($_FILES[$fileFieldName]);
            if (is_array($response) && $response['code'] == 200) {
                $result = [
                    'state' => 'SUCCESS',
                    'url' => $response['key'],
                    'title' => "",
                    'original' => '',
                    'type' => '',
                    'size' => ''
                ];
            } else {
                $result = [
                    'state' => 'ERROR' . is_array($response) ? $response['code'] : 0,
                    'url' => 'https://sfault-avatar.b0.upaiyun.com/425/703/4257032878-58becc632c231_huge256',
                    'title' => "",
                    'original' => '',
                    'type' => '',
                    'size' => ''
                ];
            }
            break;
    /* 上传涂鸦 */
    case 'uploadscrawl':
    /* 上传视频 */
    case 'uploadvideo':
    /* 上传文件 */
    case 'uploadfile':
        $result = include("action_upload.php");
        break;

    /* 列出图片 */
    case 'listimage':
        $result = include("action_list.php");
        break;
    /* 列出文件 */
    case 'listfile':
        $result = include("action_list.php");
        break;

    /* 抓取远程文件 */
    case 'catchimage':
        $result = include("action_crawler.php");
        break;

    default:
        $result = [
            'code' => '99999',
            'err_msg' => '请求地址错误'
        ];
        break;
}
echo json_encode($result);die;
/**
 * [UploadModule 上传组件]
 * @author         Shaowei Pu <542684913@qq.cn>
 * @CreateTime    2017-03-10T16:25:54+0800
 * @param                               array $request [description]
 */
function UploadModule( array $request )
{
    require_once( realpath(dirname(__FILE__).'/conf/').'/ueditor.config.php');
    require_once( realpath(dirname(__FILE__).'/conf/').'/auth.config.php');
    require_once( realpath(dirname(__FILE__).'/vendor/').'/autoload.php');
    require_once( realpath(dirname(__FILE__).'/').'/server.php');

    $relpath = getRelPath(md5(time().'SHAO_WEI_PU'));
    $relpath = $relpath.'.jpg';
    $config = array(
        'access_id'  => QINIU_ACCESS_ID,
        'access_key' => QINIU_ACCESS_KEY,
        'bucket'     => QINIU_BUCKET,  //空间名称
        'timeout'    => QINIU_TIMEOUT, //超时时间
    );
    try{
        $file_bin = file_get_contents($request['tmp_name']);
        $qiniu    = new \server($config);
        $res      = $qiniu->savebin($relpath,$file_bin);
    }catch (\Exception $exception) {
      return ['code' => '100' ,'key' => $exception];
    }
    return ['code' => '200' ,'key' => CLOUD_IMG_HOST.'/'.$res['key']]
}