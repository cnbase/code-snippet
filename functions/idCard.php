<?php
/**
 * 身份证相关
 * Created by PhpStorm.
 * User: NaobuOrg
 * Date: 2017/9/22
 * Time: 15:33
 */

/**
 * 判断是否为合法的身份证号码
 * @param $id_card
 * @return bool
 */
function checkIdCard($id_card){
    $vCity = array('11','12','13','14','15','21','22','23','31','32','33','34','35','36','37','41','42','43','44','45','46','50','51','52','53','54','61','62','63','64','65','71','81','82','91');
    if (!preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $id_card)) return false;
    if (!in_array(substr($id_card, 0, 2), $vCity)) return false;
    $id_card = preg_replace('/[xX]$/i', 'a', $id_card);
    $vLength = strlen($id_card);
    if ($vLength == 18) {
        $vBirthday = substr($id_card, 6, 4) . '-' . substr($id_card, 10, 2) . '-' . substr($id_card, 12, 2);
    } else {
        $vBirthday = '19' . substr($id_card, 6, 2) . '-' . substr($id_card, 8, 2) . '-' . substr($id_card, 10, 2);
    }
    if (date('Y-m-d', strtotime($vBirthday)) != $vBirthday) return false;
    if ($vLength == 18) {
        $vSum = 0;
        for ($i = 17 ; $i >= 0 ; $i--) {
            $vSubStr = substr($id_card, 17 - $i, 1);
            $vSum += (pow(2, $i) % 11) * (($vSubStr == 'a') ? 10 : intval($vSubStr , 11));
        }
        if($vSum % 11 != 1) return false;
    }
    return true;
}