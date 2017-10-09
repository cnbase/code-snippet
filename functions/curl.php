<?php
/**
 * CURL相关
 * Created by PhpStorm.
 * User: NaobuOrg
 * Date: 2017/9/23
 * Time: 15:02
 */

/**
 * CURL post提交
 * @param mixed $data 提交数据，数组或url_encode
 * @param string $url 提交地址
 * @param string $cookie_file cookie文件路径，是否存储和携带cookie访问 $cookie_file = './cookie.txt';
 * @param bool $useCert ssl证书
 * @param int $second 超时时间
 * @return array ['status':1,'code':200,'content':内容]
 */
function postCurl($data, $url ,$cookie_file = '',$useCert = false, $second = 30){
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
    if ($cookie_file){
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
    $result = array(
        'status'    =>  0,
        'code'      =>  0,
        'content'   =>  ''
    );
    if($content === false){
        $result['code'] = curl_errno($ch);
        $result['content'] = curl_error($ch);
        curl_close($ch);
        return $result;
    } else {
        curl_close($ch);
        $result['status'] = 1;
        $result['code'] = $httpCode;
        $result['content'] = $content;
        return $result;
    }
}