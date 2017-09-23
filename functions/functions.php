<?php
/**
 * 常用函数汇总
 * Created by PhpStorm.
 * User: NaobuOrg
 * Date: 2017/9/22
 * Time: 15:40
 */

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