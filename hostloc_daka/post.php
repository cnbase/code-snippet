<?php
/**
 * Created by PhpStorm.
 * User: NaobuOrg
 * Date: 2017/9/23
 * Time: 11:06
 */

// 登陆
$url = "http://www.hostloc.com/member.php?mod=logging&action=login&loginsubmit=yes&infloat=yes&lssubmit=yes";
$data = array(
    'referer'   =>  'http://www.hostloc.com/forum.php',
    'fastloginfield'    =>  'username',
    'username'  =>  '你的用户名',
    'password'  =>  '账号密码',
    'quickforward'  =>  'yes',
    'handlekey'     =>  'ls',
);
$result = postCurl($data,$url,true);
if ($result === false){
    file_put_contents('./debug.log',"[".date('Y-m-d H:i:s')."] CURL ERROR\r\n",FILE_APPEND);
    die('curl 出错');
}
$err_str = '登录失败，您还可以尝试';
if (isset($result['content']) && strpos($result['content'],$err_str)){
    file_put_contents('./debug.log',"[".date('Y-m-d H:i:s')."] 登陆失败\r\n",FILE_APPEND);
    die('登陆失败');
}
// 登陆成功 访问5个用户空间
file_put_contents('./debug.log',"[".date('Y-m-d H:i:s')."] 登陆成功\r\n",FILE_APPEND);
$flag = true;//true 固定；false 随机
if ($flag){
    // 固定uid
    $uid = array(12368,18794,23175,23027,4016,25815);
    for ($i = 0;$i <= 5;$i++){
        $url = "http://www.hostloc.com/home.php?mod=space&uid={$uid[$i]}&do=index";
        $result = postCurl('',$url,true);
        file_put_contents('./debug.log',"[".date('Y-m-d H:i:s')."] 访问：{$url}\r\n",FILE_APPEND);
        sleep(2);
    }
    file_put_contents('./debug.log',"\r\n",FILE_APPEND);
} else {
    // 随机uid
    for ($i = 0;$i <= 5;$i++){
        $uid = rand(12000,25000);
        $url = "http://www.hostloc.com/home.php?mod=space&uid={$uid}&do=index";
        $result = postCurl('',$url,true);
        file_put_contents('./debug.log',"[".date('Y-m-d H:i:s')."] 访问：{$url}\r\n",FILE_APPEND);
        sleep(2);
    }
    file_put_contents('./debug.log',"\r\n",FILE_APPEND);
}
exit;

/* 通过curl获取formhash 后期可自动发帖用 */
$url = 'http://www.hostloc.com/';
$contents = file_get_contents($url);
preg_match('/<input\s*type="hidden"\s*name="formhash"\s*value="(.*?)"\s*\/>/i', $contents, $matches);
if(!empty($matches)) {
    $formhash = $matches[1];
} else {
    die('Not found the forumhash.');
}

/**
 * CURL post提交
 * @param mixed $data 提交数据，数组或url_encode
 * @param string $url 提交地址
 * @param bool $saveCookie 是否存储和携带cookie访问
 * @param bool $useCert ssl证书
 * @param int $second 超时时间
 * @return array|bool false:curl出错;['code':200,'content':内容]
 */
function postCurl($data, $url ,$saveCookie = false,$useCert = false, $second = 30){
    $cookie_file = './cookie.txt';
    $ch = curl_init();
    //设置超时
    curl_setopt($ch, CURLOPT_TIMEOUT, $second);
    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
    curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);
    //设置header
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    //要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    if ($saveCookie){
        curl_setopt($ch, CURLOPT_COOKIEJAR,  $cookie_file); //存储cookies
        curl_setopt ($ch, CURLOPT_COOKIEFILE, $cookie_file);//携带cookies
    }
    if($useCert == true){
        //设置证书
        //使用证书：cert 与 key 分别属于两个.pem文件
        curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
        //curl_setopt($ch,CURLOPT_SSLCERT, WxPayConfig::SSLCERT_PATH);
        curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
        //curl_setopt($ch,CURLOPT_SSLKEY, WxPayConfig::SSLKEY_PATH);
    }
    //post提交方式
    curl_setopt($ch, CURLOPT_POST, TRUE);
    if ($data){
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }
    //运行curl
    $content = curl_exec($ch);
    $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
    //返回结果
    if($content === false){
        $error = curl_errno($ch);
        curl_close($ch);
        return false;
    } else {
        curl_close($ch);
        $result = array(
            'code'  =>  $httpCode,
            'content'  =>  $content
        );
        return $result;
    }
}