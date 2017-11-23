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