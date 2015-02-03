<?php
/**
 * Created by IntelliJ IDEA.
 * User: liuzhr
 * Date: 1/31/15
 * Time: 1:02 AM
 */

class MobileInfo extends AppModel {

    public function get_province($mobile) {

//        http://tcc.taobao.com/cc/json/mobile_tel_segment.htm?tel=13345698569
//        __GetZoneResult_ = {
//            mts:'1334569',
//    province:'安徽',
//    catName:'中国电信',
//    telString:'13345698569',
//	areaVid:'30509',
//	ispVid:'138238560',
//	carrier:'安徽电信'
//}
        $curl = curl_init();
        $options = array(
            CURLOPT_URL => 'http://tcc.taobao.com/cc/json/mobile_tel_segment.htm?tel=' . $mobile,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30
        );
        curl_setopt_array($curl, ($options));
        $json = curl_exec($curl);
        curl_close($curl);

        $left_brace = mb_strpos($json, 'province:\'', null, 'CP936') + 10;
        $right_brace = mb_strpos($json, '\'', $left_brace , 'CP936');

        $str = mb_substr($json, $left_brace , $right_brace - $left_brace  + 1, 'CP936');
        if (!empty($str)) {
            return mb_convert_encoding($str, 'UTF-8', 'CP936');
        } else {
            return '';
        }
        //            __GetZoneResult_ = {
        //                mts:'1334569',
        //    province:'安徽',
        //    catName:'中国电信',
        //    telString:'13345698569',
        //	areaVid:'30509',
        //	ispVid:'138238560',
        //	carrier:'安徽电信'
        //}
    }
}