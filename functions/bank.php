<?php
/**
 * Created by PhpStorm.
 * User: baozhi
 * Date: 2017/12/21
 * Time: 17:49
 */

/**
 * 根据卡号获取信用卡发行标识
 * @param string $card_no
 * @return int|string
 */
function getIIN($card_no = ''){
    $iinRange = array(
        'visa' =>  array('4'),
        'mastercard' =>  array('51','52','53'),
        'americanexpress' =>  array('34','37'),
        'unionpay' =>  array('62'),
        'maestro' =>  array('5018','5020','5038','6304','6759','6761','6762','6763'),
        'jcb' =>  array('3528-3589'),
        'dankort' =>  array('5019'),
        'discover' =>  array('65','6011','644-649'),
        'dinersclub' =>  array('36','54','55','2014','2049','3095','300-305','38-39'),
    );
    foreach ($iinRange as $iin_name => $iin_arr){
        foreach ($iin_arr as $iin){
            $arr = explode('-',$iin);
            if (count($arr) > 1){
                $card_no_prefix = substr($card_no,0,strlen($arr[0]));
                if ($card_no_prefix >= $arr[0] && $card_no_prefix <= $arr[1]){
                    return $iin_name;
                }
            } else {
                $card_no_prefix = substr($card_no,0,strlen($iin));
                if ($iin == $card_no_prefix){
                    return $iin_name;
                }
            }
        }
    }
    return '';
}

/**
 * 基于luhn算法校验信用卡号合法性
 * 该校验的过程：
 * 1、从卡号最后一位数字开始，逆向将奇数位(1、3、5等等)相加。
 * 2、从卡号最后一位数字开始，逆向将偶数位数字，先乘以2（如果乘积为两位数，则将其减去9），再求和。
 * 3、将奇数位总和加上偶数位总和，结果应该可以被10整除。
 * @param string $card_no
 * @return bool
 */
function checkCardNo($card_no = ''){
    $card_no = str_split(strrev($card_no));
    $sum = 0;
    foreach ($card_no as $k => $item){
        if ($k%2){
            // 奇数，卡号的偶数位
            if ($item*2 > 9){
                $sum += $item*2-9;
            } else {
                $sum += $item*2;
            }
        } else {
            // 偶数，卡号的奇数位
            $sum += $item;
        }
    }
    if ($sum%10){
        return false;
    } else {
        return true;
    }
}

/**
 * 奶神思路
 * @param string $card_no
 * @return bool
 */
function checkCardNo2($card_no = ''){
    $card_no = str_split(strrev($card_no));
    $sum = 0;
    foreach ($card_no as $k => $item){
        $item *= (1+$k%2);//偶数位*2
        $sum += ($item > 9 ? $item -9 : $item);
    }
    return $sum%10 == 0;
}

/**
 * 大师思路
 * @param string $card_no
 * @return bool
 */
function checkCardNo3($card_no = ''){
    $len = strlen($card_no);
    $i = 0;
    $code = array(
        array(0,1,2,3,4,5,6,7,8,9),
        array(0,2,4,6,8,1,3,5,7,9)
    );
    $sum = 0;
    while ($len--){
        $sum += $code[$i][$card_no{$len}];
        $i = !$i;
    }
    return $sum % 10 == 0;
}