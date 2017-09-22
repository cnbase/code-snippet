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