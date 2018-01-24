<?php
/**
 * 常用函数汇总
 * Created by PhpStorm.
 * User: NaobuOrg
 * Date: 2017/9/22
 * Time: 15:40
 */

/**
 * 输出App所需格式
 * @param int $status 状态码
 * @param string $msg 描述
 * @param array $data 数据
 * @param string $url 跳转URL
 * @param string $type 返回数据类型,默认json格式
 */
function appReturn($status = 0,$msg = '',$data = [],$url = '',$type = 'json'){
    $result = array(
        'status'    =>  $status,
        'msg'       =>  $msg,
        'data'      =>  $data,
        'url'       =>  $url
    );
    if ($type == 'json'){
        header('Content-Type:application/json;charset=utf-8');
        exit(json_encode($result,0));
    }
}

/**
 * 计算两组经纬度坐标 之间的距离
 * params ：lat1 纬度1； lng1 经度1； lat2 纬度2； lng2 经度2； len_type （1:m or 2:km);
 * return m or km
 */
function getDistance($lng1, $lat1, $lng2, $lat2, $len_type = 1, $decimal = 2) {
    $radLat1 = $lat1 * M_PI / 180.0;
    $radLat2 = $lat2 * M_PI / 180.0;
    $a       = $radLat1 - $radLat2;
    $b       = ($lng1 * M_PI / 180.0) - ($lng2 * M_PI / 180.0);
    $s       = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1) * cos($radLat2) * pow(sin($b/2),2)));
    $s       = $s * 6378.138;
    $s       = round($s * 1000);
    if ($len_type > 1)
    {
        $s /= 1000;
    }
    return round($s, $decimal);
}

/**
 * 根据经纬度和半径计算出范围[最大/最小经纬度]
 * @param string $lat 纬度
 * @param String $lng 经度
 * @param float $radius 半径
 * @return Array 范围数组
 */
function calcScope($lat, $lng, $radius) {
    $degree = (24901 * 1609) / 360.0;
    $dpmLat = 1 / $degree;
    $radiusLat = $dpmLat * $radius;
    $minLat = $lat - $radiusLat; // 最小纬度
    $maxLat = $lat + $radiusLat; // 最大纬度
    $mpdLng = $degree * cos($lat * (PI / 180));
    $dpmLng = 1 / $mpdLng;
    $radiusLng = $dpmLng * $radius;
    $minLng = $lng - $radiusLng; // 最小经度
    $maxLng = $lng + $radiusLng; // 最大经度
    /** 返回范围数组 */
    $scope = array(
        'minLat' => $minLat,
        'maxLat' => $maxLat,
        'minLng' => $minLng,
        'maxLng' => $maxLng
    );
    return $scope;
}

/**
 * 判断指定时间戳是否在营业时间
 * 支持营业时间隔天，0-48小时，如凌晨2点-隔天18点，数据库应存7200/64800
 * @param string $start_time 营业开始时间
 * @param string $end_time 营业结束时间
 * @param int $timestamp 时间戳
 * @return bool
 */
function in_open_time($start_time,$end_time,$timestamp = 0){
    if ($timestamp == 0){
        $now_time = time();//当前时间戳
    } else {
        $now_time = $timestamp;
    }
    $now_hour = date('H',$now_time);//当前小时
    $now_mintue = date('i',$now_time);//当前分钟
    $now_times = $now_hour*60*60+$now_mintue*60;//秒数
    // 判断是否隔夜
    if($end_time > 24*60*60){
        //隔夜
        if($now_times >= $start_time && $now_times <= $end_time){
            return true;
        } elseif($now_times <= $start_time && ($now_times + 24*60*60) <= $end_time){
            return true;
        } else {
            return false;
        }
    } else {
        //非隔夜
        if($now_times >= $start_time && $now_times <= $end_time){
            return true;
        } else {
            return false;
        }
    }
}

/**
 * 根据出生时间戳计算周岁
 * @param int $birth 生日时间戳
 * @return mixed
 */
function howOld($birth) {
    list($birthYear, $birthMonth, $birthDay) = explode('-', date('Y-m-d', $birth));
    list($currentYear, $currentMonth, $currentDay) = explode('-', date('Y-m-d'));
    $age = $currentYear - $birthYear - 1;
    if($currentMonth > $birthMonth || $currentMonth == $birthMonth && $currentDay >= $birthDay) {
        $age++;
    }
    return$age;
}

/**
 * 判断是否为合法的身份证号码
 * @param $id_card
 * @return bool
 */
function checkIdCard($id_card){
    $vCity = array('11','12','13','14','15','21','22','23','31','32','33','34','35','36','37','41','42','43','44','45','46','50','51','52','53','54','61','62','63','64','65','71','81','82','91');
    if (!preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $id_card)) return false;
    if (!in_array(substr($id_card, 0, 2), $vCity)) return false;
    $id_card = preg_replace('/[xX]$/i', 'a', $id_card);
    $vLength = strlen($id_card);
    if ($vLength == 18) {
        $vBirthday = substr($id_card, 6, 4) . '-' . substr($id_card, 10, 2) . '-' . substr($id_card, 12, 2);
    } else {
        $vBirthday = '19' . substr($id_card, 6, 2) . '-' . substr($id_card, 8, 2) . '-' . substr($id_card, 10, 2);
    }
    if (date('Y-m-d', strtotime($vBirthday)) != $vBirthday) return false;
    if ($vLength == 18) {
        $vSum = 0;
        for ($i = 17 ; $i >= 0 ; $i--) {
            $vSubStr = substr($id_card, 17 - $i, 1);
            $vSum += (pow(2, $i) % 11) * (($vSubStr == 'a') ? 10 : intval($vSubStr , 11));
        }
        if($vSum % 11 != 1) return false;
    }
    return true;
}

/**
 * 检测手机号
 * 前三位：
 * 移动：134-139 147 150-152 157-159 182 187 188
 * 联通：130-132 155-156 185 186
 * 电信：133 153 180 189
 * 支持虚拟号段：17*
 * 后八位：0-9
 * @param $mobile
 * @return bool
 */
function checkPhone($mobile){
    if (!$mobile){
        return false;
    }
    $rule = '/^((13[0-9])|147|(15[0-35-9])|(18[0-9])|(17[0-9]))[0-9]{8}$/';
    preg_match($rule,$mobile,$result);
    return $result ? !!($mobile === $result[0]) : false;
}

/**
 * 生成短信验证码
 * @param int $len 生成的长度
 * @return int
 */
function createCaptcha($len = 6){
    return rand(pow(10,($len-1)),pow(10,$len)-1);
}

// 生成随机验证码
function createNo(){
    $str = 'ABCDEFGHIJKLMKOPQRSTUVWXYZ';
    return $str{rand(0,25)}.time().rand(100,999);
}

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

/**
 * 判断工作日指定时间段
 * @param int $start_hour 24h制
 * @param int $end_hour 24h制
 * @return bool
 */
function checkWorkHour($start_hour = 0,$end_hour = 24){
    $now_time = time();
    // 获取星期几
    $day = date('w',$now_time);
    $hour = date('H',$now_time);
    if ($day > 5){
        return false;
    }
    if ($hour >= $start_hour && $hour <= $end_hour){
        return true;
    } else {
        return false;
    }
}

/**
 * 判断信用卡过期时间合法性
 * @param string $expire_date 1711 两位年+两位月
 * @return bool
 */
function checkCardExpireDate($expire_date = ''){
    if (!$expire_date){
        return false;
    }
    $len = strlen($expire_date);
    if ($len != 4){
        return false;
    }
    $year = substr($expire_date,0,2);
    $month = substr($expire_date,2);
    // 当前年份
    $now_year = date('Y');
    $year = substr($now_year,0,2).$year;
    if ($year < $now_year){
        // 已过期
        return false;
    }
    if (intval($year) == intval($now_year) && $month < date('m')){
        // 已过期
        return false;
    }
    if ($month < 1 || $month > 12){
        return false;
    }
    return true;
}

/**
 * 生成全局唯一码
 * @return string
 */
function getGuid() {
    $unique = strtoupper(md5(uniqid(mt_rand(), true)));
    $hyphen = chr(45);// "-"
    $uuid = substr($unique, 0, 8).$hyphen
        .substr($unique, 8, 4).$hyphen
        .substr($unique,12, 4).$hyphen
        .substr($unique,16, 4).$hyphen
        .substr($unique,20,12);
    return $uuid;
}

/**
 * 检测传入值是否符合PHP命名规范
 * 第一位非数字，后面可英文/数字/_
 * @param string $var_name
 * @return bool
 */
function check_php_var_name($var_name = ''){
    if (preg_match('/^[a-zA-Z_][a-zA-Z_0-9]*$/',$var_name)){
        return true;
    } else {
        return false;
    }
}

/**
 * 十进制数转换成62进制
 *
 * @param integer $num
 * @return string
 */
function from10_to62($num) {
    $to = 62;
    $dict = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $ret = '';
    do {
        $ret = $dict[bcmod($num, $to)] . $ret;
        $num = bcdiv($num, $to);
    } while ($num > 0);
    return $ret;
}

/**
 * 62进制数转换成十进制数
 *
 * @param string $num
 * @return string
 */
function from62_to10($num) {
    $from = 62;
    $num = strval($num);
    $dict = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $len = strlen($num);
    $dec = 0;
    for($i = 0; $i < $len; $i++) {
        $pos = strpos($dict, $num[$i]);
        if ($pos === false){
            return false;
            break;
        }
        $dec = bcadd(bcmul(bcpow($from, $len - $i - 1), $pos), $dec);
    }
    return $dec;
}

/**
 * XSS过滤，输出内容过滤 From InitPHP框架 thx
 * 1. 框架支持全局XSS过滤机制-全局开启将消耗PHP运行
 * 2. 手动添加XSS过滤函数，在模板页面中直接调用
 * 全局使用方法：InitPHP::output($string, $type = 'encode');
 * @param string $string  需要过滤的字符串
 * @param string $type    encode HTML处理 | decode 反处理
 * @return string
 */
function output($string, $type = 'encode') {
    $html = array("&", '"', "'", "<", ">", "%3C", "%3E");
    $html_code = array("&amp;", "&quot;", "&#039;", "&lt;", "&gt;", "&lt;", "&gt;");
    if ($type == 'encode') {
        if (function_exists('htmlspecialchars')) return htmlspecialchars($string);
        $str = str_replace($html, $html_code, $string);
    } else {
        if (function_exists('htmlspecialchars_decode')) return htmlspecialchars_decode($string);
        $str = str_replace($html_code, $html, $string);
    }
    return $str;
}

/**
 * 输出状态码header头
 * @param string $code
 */
function outputHeaderCode($code = '404'){
    switch ($code){
        case '404':
            header('HTTP/1.1 404 Not Found');
            header("status: 404 Not Found");
            break;
        case '405':
            header('HTTP/1.1 405 Method not allowed');
            header("status: 405 Method not allowed");
            break;
        case '500':
            header('HTTP/1.1 500 Internal Server Error');
            header("status: 500 Internal Server Error");
            break;
    }
}