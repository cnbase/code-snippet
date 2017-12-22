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