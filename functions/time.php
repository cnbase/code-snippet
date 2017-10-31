<?php
/**
 * 时间相关函数
 * Created by PhpStorm.
 * User: baozhi
 * Date: 2017/10/31
 * Time: 16:20
 */

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