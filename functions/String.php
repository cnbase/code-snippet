<?php
/**
 * Created by PhpStorm.
 * User: baozhi
 * Date: 2017/11/20
 * Time: 17:20
 */
/**
 * 使用指定符号替换字符串
 * @param string $str 原字符串
 * @param int $start 前面要显示的字符长度
 * @param int $end 后面要显示的字符长度
 * @param string $stars 替换后的字符
 * @return string
 */
function replaceStars($str,$start,$end,$stars = '*'){
    $length = mb_strlen($str);
    $repeat = $length - $start - $end;
    if ($repeat > 0){
        return mb_substr($str,0,$start).str_repeat($stars,$repeat).mb_substr($str,$length-$end);
    } else {
        return $str;
    }
}

// 字符串转十六进制
function String2Hex($string){
    $hex='';
    for ($i=0; $i < strlen($string); $i++){
        $hex .= dechex(ord($string[$i]));
    }
    return $hex;
}

// 十六进制转字符串
function Hex2String($hex){
    $string='';
    for ($i=0; $i < strlen($hex)-1; $i+=2){
        $string .= chr(hexdec($hex[$i].$hex[$i+1]));
    }
    return $string;
}

// example:
$hex = String2Hex("test sentence...");
// $hex contains 746573742073656e74656e63652e2e2e
print Hex2String($hex);
// outputs: test sentence...
