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