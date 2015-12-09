<?php

/**
 * Created by PhpStorm.
 * User: shichaopeng
 * Date: 12/8/15
 * Time: 14:41
 * 第三方 物流组件
 */
class LogisticsComponent extends Component {


    public $components = array('ThirdPartyExpress');

    /**
     * @param $data
     * @return array
     * 根据人人信息
     * 生成物流订单
     * 不是拼单的订单
     */
    public function create_logistics_order_from_rr($data) {
        $logisticsOrderM = ClassRegistry::init('LogisticsOrder');
        $logisticsOrderItemM = ClassRegistry::init('LogisticsOrderItem');
        $date_time = date('Y-m-d H:i:s');
        $logistics_order = array(
            'creator' => $data['creator'],
            'status' => LOGISTICS_ORDER_WAIT_PAY_STATUS,
            'order_id' => $data['order_id'],
            'created' => $date_time,
            'update' => $date_time,
            'starting_phone' => $data['startingPhone'],
            'starting_province' => $data['startingProvince'],
            'starting_city' => $data['startingCity'],
            'starting_address' => $data['startingAddress'],
            'total_price' => $data['total_price'],
            'type' => RR_SINGLE_LOGISTICS_ORDER_TYPE
        );
        $logistics_order = $logisticsOrderM->save($logistics_order);
        if ($logistics_order && !empty($logistics_order)) {
            $logistics_order_item = array(
                'logistics_order_id' => $logistics_order['LogisticsOrder']['id'],
                'goods_name' => $data['goodsName'],
                'goods_weight' => $data['goodsWeight'],
                'good_worth' => $data['goodsWorth'],
                'consignee_name' => $data['consigneeName'],
                'consignee_phone' => $data['consigneePhone'],
                'consignee_province' => $data['consigneeProvince'],
                'consignee_city' => $data['consigneeCity'],
                'consignee_address' => $data['consigneeAddress'],
                'remark' => $data['remark'],
                'total_price' => $data['total_price'],
            );
            $logistics_order_item = $logisticsOrderItemM->save($logistics_order_item);
            if ($logistics_order_item && !empty($logistics_order_item)) {
                return array('success' => true, 'logistics_order' => $logistics_order);
            }
        }
        return array('success' => false);
    }

    public function trigger_confirm_rr_order($logistics_order_id) {
//$params ["userName"];
//$params ["businessNo"]; 订单号
//$params ["goodsName"];
//$params ["goodsWeight"];
//$params ["goodsWorth"];
//$params ["mapFrom"]; op
//$params ["startingName"];
//$params ["startingPhone"];
//$params ["startingProvince"]; op 仅直辖市和特别行政区可以为空
//$params ["startingCity"];
//$params ["startingAddress"];
//$params ["startingLng"]; op
//$params ["startingLat"]; op
//$params ["consigneeName"];
//$params ["consigneePhone"];
//$params ["consigneeProvince"]; op 仅直辖市和特别行政区可以为空
//$params ["consigneeCity"];
//$params ["consigneeAddress"];
//$params ["consigneeLng"];
//$params ["consigneeLat"];
//$params ["serviceFees"];
//$params ["pickupTime"]; op
//$params ["remark"]; op
//$params ["callbackUrl"];
//$params ["sign"]; strtolower ( MD5 ( $appKey . strtolower ( MD5 ( $userName . $startingAddress . $consigneeAddress ) ) ) );

//$result ["isSuccess"];
//$result ["errMsg"];
//$result ["warn"];
//$result ["price"];
//$result ["orderNo"];
//$result ["businessNo"];

        $logisticsOrderM = ClassRegistry::init('LogisticsOrder');
        $logisticsOrderItemM = ClassRegistry::init('LogisticsOrderItem');
        $logistics_order = $logisticsOrderM->find('first', array(
            'conditions' => array(
                'id' => $logistics_order_id
            )
        ));
        $logistics_order_item = $logisticsOrderItemM->find('first', array(
            'conditions' => array(
                'logistics_order_id' => $logistics_order_id
            )
        ));
        $sign_keyword = RR_LOGISTICS_USERNAME . $logistics_order['LogisticsOrder']['starting_address'] . $logistics_order_item['LogisticsOrderItem']['consignee_address'];
        $sign = $this->get_sign($sign_keyword);
        $post_params = array(
            'userName' => RR_LOGISTICS_USERNAME,
            'businessNo' => '',
            'goodsName' => $logistics_order_item['LogisticsOrderItem']['goods_name'],
            'goodsWeight' => $logistics_order_item['LogisticsOrderItem']['goods_weight'],
            'goodsWorth' => $logistics_order_item['LogisticsOrderItem']['goods_worth'],
            'startingName' => $logistics_order['LogisticsOrder']['starting_name'],
            'startingPhone' => $logistics_order['LogisticsOrder']['starting_phone'],
            'startingCity' => $logistics_order['LogisticsOrder']['starting_city'],
            'startingAddress' => $logistics_order['LogisticsOrder']['starting_address'],
            'consigneeName' => $logistics_order_item['LogisticsOrderItem']['consignee_name'],
            'consigneePhone' => $logistics_order_item['LogisticsOrderItem']['consignee_phone'],
            'consigneeCity' => $logistics_order_item['LogisticsOrderItem']['consignee_city'],
            'consigneeAddress' => $logistics_order_item['LogisticsOrderItem']['consignee_address'],
            'serviceFees' => 0,
            'remark' => $logistics_order_item['LogisticsOrderItem']['remark'],
            'callbackUrl' => RR_LOGISTICS_CALLBACK,
            'sign' => $sign
        );

        $result = $this->ThirdPartyExpress->confirm_rr_order($post_params);
        return $result;
    }

    /**
     * @param $keyword
     * @return string
     * 获取签名
     */
    public function get_sign($keyword) {
        return strtolower(MD5(RR_LOGISTICS_APP_KEY .MD5(date('YmdH:i:s')). strtolower(MD5($keyword))));
    }

    /**
     * @param $logistics_order_id
     * @return string
     * 生成人人快递的订单号
     */
    public function get_rr_business_no($logistics_order_id) {
        return 'rr-' . $logistics_order_id . '-' . mktime() . '-' . rand(0, 100);
    }

    /**
     * @param $status
     * @param $business_no
     * @param $business_order_id
     * 更新物流订单状态
     */
    public function update_logistics_order_status($status, $business_no, $business_order_id) {
        $logisticsOrderM = ClassRegistry::init('LogisticsOrder');
        $logisticsOrderM->update(array('status' => $status), array('business_no' => $business_no, 'business_order_id' => $business_order_id));
    }
}