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
 * @param float $radius 半径 单位米
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

/**
 * 根据经纬度获取之间的距离
 * @param string $ThisMap "lng,lat"
 * @param string $TargetMap "lng,lat"
 * @param int $precision
 * @return float
 */
function MapAway($ThisMap, $TargetMap,$precision = 1){
    //当前地址坐标
    $ThisMap = explode(',',$ThisMap);
    list($ThisMap['x'],$ThisMap['y']) = $ThisMap;

    //目标地址坐标
    $TargetMap = explode(',',$TargetMap);
    list($TargetMap['x'],$TargetMap['y']) = $TargetMap;

    $rad = 3.1415926535898 / 180.0;

    $EARTH_RADIUS = 6378.137;
    $radLat1 = $ThisMap['y']*$rad;
    $radLat2 = $TargetMap['y']*$rad;
    $a = $radLat1 - $radLat2;
    $b = $ThisMap['x']*$rad - $TargetMap['x']*$rad;
    $s = 2 * asin(sqrt(pow(sin($a/2),2) +
            cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)));
    $s = $s *$EARTH_RADIUS;
    $Km = round($s * 10000 / 10000,$precision);

    return $Km;
}
