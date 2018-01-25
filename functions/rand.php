<?php
/**
 * 随机码相关
 * Created by PhpStorm.
 * User: NaobuOrg
 * Date: 2017/9/22
 * Time: 15:38
 */

// 生成随机验证码
function createNo(){
    $str = 'ABCDEFGHIJKLMKOPQRSTUVWXYZ';
    return $str{rand(0,25)}.time().rand(100,999);
}

/**
 * 生成短信验证码
 * @param int $len 生成的长度
 * @return int
 */
function createCaptcha($len = 6){
    return rand(pow(10,($len-1)),pow(10,$len)-1);
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
 * 生成总和固定且指定个数的随机数
 * @param float $sum 固定总和
 * @param int $num 指定随机次数
 * @return array 返回的随机数组
 */
function randSplit($sum,$num){
    $avg = $sum/$num;
    $result = array();
    $temp2 = 0;
    $max = 1;
    for ($i = 1;$i < $num;$i++){
        if ($i % 2){
            $temp = round($avg * (1 - mt_rand()/mt_getrandmax() * $max),2);
            // 防止生成0的随机数
            while (!$temp){
                $temp = round($avg * (1 - mt_rand()/mt_getrandmax() * $max),2);
            }
            $temp2 += $temp;
            $result[] = $temp;
            $max = 1 - $max;
        } else {
            $temp = round($avg * (1 + mt_rand()/mt_getrandmax() * $max),2);
            // 防止生成0的随机数
            while (!$temp){
                $temp = round($avg * (1 - mt_rand()/mt_getrandmax() * $max),2);
            }
            $temp2 += $temp;
            $result[] = $temp;
            $max = 1 - $max;
        }
    }
    $result[] = ($sum - $temp2);
    shuffle($result);
    return $result;
}

/**
 * 生成指定范围之间随机浮点数
 * @param int $min 最小值，默认0
 * @param int $max 最大值，默认1
 * @return float|int 返回随机浮点数
 */
function randFloat($min=0, $max=1){
    return $min + mt_rand()/mt_getrandmax() * ($max-$min);
}