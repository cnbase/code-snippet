<?php
/**
 * Created by PhpStorm.
 * User: baozhi
 * Date: 2017/11/7
 * Time: 21:28
 * copy by mdy
 */
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