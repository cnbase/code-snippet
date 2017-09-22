<?php
/**
 * 手机号相关
 * Created by PhpStorm.
 * User: NaobuOrg
 * Date: 2017/9/22
 * Time: 15:29
 */

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