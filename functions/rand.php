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

// 生成随机邀请码，排除0oO
function getCode($len = 6){
    $letters = '123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ';
    $ret = '';
    for ($i = 0;$i < $len; ++$i)
        $ret .= $letters{mt_rand(0,58)};
    return $ret;
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
 * 生成指定范围之间随机浮点数
 * @param int $min 最小值，默认0
 * @param int $max 最大值，默认1
 * @return float|int 返回随机浮点数
 */
function randFloat($min=0, $max=1){
    return $min + mt_rand()/mt_getrandmax() * ($max-$min);
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
 * 生成总和固定/指定个数/指定浮动范围的随机数
 * 依赖randSplit()随机函数
 * @param float $sum 固定总和
 * @param int $num 指定随机次数
 * @param float $ratio 每个均值的上下浮动范围，取值范围0-1之间
 * @return array 返回的随机数组
 */
function randSplitForRatio($sum,$num,$ratio = 1.0){
    $avg = $sum/$num;
    $float = $avg * $ratio * 2;//$ratio为每个均值的上下浮动范围
    $float_arr = randSplit($float,$num);//随机数组
    $add_float = array();//随机增加数组
    $reduce_float = array();//随机减少数组
    // 对半拆分随机数组为一增一减数组
    foreach ($float_arr as $k => $item){
        $temp1 = round($item/2,2);
        $temp2 = round($item - $temp1,2);
        $add_float[] = $temp1;
        $reduce_float[] = $temp2;
    }
    shuffle($reduce_float);//打乱其一数组顺序
    $new_amount = 0;
    $plan = array();
    for ($i = 0; $i < $num; $i++){
        if ($i !== ($num - 1)){
            $temp1 = round($avg + $add_float[$i],2);
            $temp2 = round($temp1 - $reduce_float[$i],2);
            $plan[] = $temp2;
            $new_amount += $temp2;
        } else {
            $plan[] = round($sum - $new_amount,2);
        }
    }
    return $plan;
}
