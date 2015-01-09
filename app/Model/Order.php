<?php
/**
 * Created by IntelliJ IDEA.
 * User: liuzhr
 * Date: 11/10/14
 * Time: 5:04 PM
 */

class Order extends AppModel {

    public function createOrFindGrouponOrder($memberId, $uid, $fee, $product_id, $type = ORDER_TYPE_GROUP, $area='', $address='', $mobile='', $name='') {
        if ($type != ORDER_TYPE_GROUP && $type != ORDER_TYPE_GROUP_FILL) {
            throw new CakeException("error order type:".$type);
        }

        $existsOrder = $this->find('first', array(
            'conditions' => array('member_id' => $memberId,
                'creator' => $uid,
                'type' => $type,
            )
        ));

        if (empty($existsOrder)) {

            $product = ClassRegistry::init('Product')->find('first', array(
                'conditions' => array('id' => $product_id),
                'fields' => 'brand_id',
            ));
            $brand_id = empty($product)? 0 : $product['Product']['brand_id'];

            $arr = array(
                'creator' => $uid,
                'total_all_price' => $fee,
                'type' => $type,
                'brand_id' => $brand_id,
                'member_id' => $memberId,
                'consignee_area' => $area,
                'consignee_name' => $name,
                'consignee_address' => $address,
                'consignee_mobilephone' => $mobile,
            );
            $order = $this->save($arr);
            if (!empty($order)) {
                $cartM = ClassRegistry::init('Cart');
                $inserted = $cartM->add_to_cart($product_id, 1, 0, CART_ITEM_TYPE_GROUPON_PROM, 0, $uid);
                $cartM->updateAll(array('order_id' => $order['Order']['id'], 'status' => CART_ITEM_STATUS_BALANCED), array('id' => $inserted['Cart']['id']));
            }

            return $order;
        } else {

            if (abs($existsOrder['Order']['total_all_price'] * 100 - $fee * 100) >= 1 && $existsOrder['Order']['status'] == ORDER_STATUS_WAITING_PAY) {
                $this->updateAll(array('total_all_price'  => $fee), array('id' => $existsOrder['Order']['id'], 'status' => ORDER_STATUS_WAITING_PAY));
                return $this->createOrFindGrouponOrder($memberId, $uid, $fee, $product_id, $type, $area, $address, $mobile, $name);
            } else {
                return $existsOrder;
            }

        }
    }

    /**
     * @param $operator
     * @param $order_id
     * @param $owner
     * @return bool
     */
    public function cancelWaitingPayOrder($operator, $order_id, $owner) {

        $rtn = $this->updateAll(array('status' => ORDER_STATUS_CANCEL, 'lastupdator' => $operator),
            array('id' => $order_id, 'status' => ORDER_STATUS_WAITING_PAY));

        if ($rtn && $this->getAffectedRows() >= 1) {
            ClassRegistry::init('CouponItem')->unapply_coupons($owner, $order_id);
            $cartModel = ClassRegistry::init('Cart');
            $boughts = $cartModel->find('list', array(
                'conditions' => array('order_id' => $order_id, 'status' => CART_ITEM_STATUS_BALANCED),
                'fields' => 'product_id, num'
            ));
            if(!empty($boughts)) {
                $productModel = ClassRegistry::init('Product');
                foreach($boughts as $pid => $num) {
                    $productModel->update_storage_saled($pid, -$num);
                }
            }
        }

        return $rtn;
    }

    public function set_order_to_paid($orderId, $isTry, $orderOwner, $type, $memberId=0) {
        $rtn = $this->updateAll(array('status' => ORDER_STATUS_PAID, 'pay_time' => "'" . date(FORMAT_DATETIME) . "'")
            , array('id' => $orderId, 'status' => ORDER_STATUS_WAITING_PAY));
        $sold = $rtn && $this->getAffectedRows() >= 1;
        if ($sold) {
            $cartM = ClassRegistry::init('Cart');
            $cartItems = $cartM->find_balanced_items($orderId);
            if (!empty($cartItems)) {
                $pid_list = Hash::extract($cartItems, '{n}.Cart.product_id');
            }
            if (!empty($pid_list)) {
                if ($isTry) {
                    $shichiM = ClassRegistry::init('OrderShichi');
                    foreach ($pid_list as $pid) {
                        $shichiM->create();
                        $shichiM->save(array('OrderShichi' => array(
                            'data_id' => $pid,
                            'creator' => $orderOwner,
                            'order_id' => $orderId,
                        )));
                    }
                    $tryM = ClassRegistry::init('ProductTry');
                    $pTry = $tryM->findById($isTry);
                    if (!empty($pTry)) {
                        //FIXME: do retry if failed
                        $tryM->updateAll(array('sold_num' => 'sold_num + 1'), array('id' => $isTry, 'modified' => $pTry['ProductTry']['modified']));
                    }
                } else if ($type == ORDER_TYPE_GROUP || $type == ORDER_TYPE_GROUP_FILL) {
                    $gmM = ClassRegistry::init('GrouponMember');
                    $gmM->paid_done($memberId, $orderOwner, $type);
                } else {
                    foreach ($pid_list as $pid) {
                        clean_total_sold($pid);
                    }
                }
            }
        }
        return $sold;
    }

    /**
     * @param $uid
     * @param $order_status  int|array
     * @param bool $only_normal_type
     * @throws CakeException
     * @return array orders, order_carts and mapped brands
     */
    public function get_user_orders($uid, $order_status=null, $only_normal_type = true) {

        $cond = array('creator' => $uid, 'published' => PUBLISH_YES, 'deleted' => DELETED_NO);
        if ($order_status !== null) {
            $cond['status'] = $order_status;
        }

        if ($only_normal_type) {
            $cond['type'] = array(ORDER_TYPE_DEF, ORDER_TYPE_GROUP_FILL);
        }

        $orders = $this->find('all', array(
            'order' => 'id desc',
            'conditions' => $cond,
        ));
        $order_ids = array();
        $brandIds = array();
        foreach ($orders as $o) {
            $order_ids[] = $o['Order']['id'];
            $brandIds[] = $o['Order']['brand_id'];
        }

        $order_carts = array();
        if (!empty($order_ids)) {
            $cartM = ClassRegistry::init('Cart');
            $Carts = $cartM->find('all', array(
                'conditions' => array(
                    'order_id' => $order_ids,
                    'creator' => $uid,
//                    'status' => CART_ITEM_STATUS_BALANCED,
                )));

            foreach ($Carts as $c) {
                $order_id = $c['Cart']['order_id'];
                if (!isset($order_carts[$order_id])) $order_carts[$order_id] = array();
                $order_carts[$order_id][] = $c;
            }
        }

        $mappedBrands = array();
        if (!empty($brandIds)) {
            $brandM = ClassRegistry::init('Brand');
            $brands = $brandM->find('all', array(
                'conditions' => array('id' => $brandIds),
                'fields' => array('id', 'name', 'created', 'slug', 'coverimg')
            ));

            foreach ($brands as $brand) {
                $mappedBrands[$brand['Brand']['id']] = $brand;
            }
        }
        return array($orders, $order_carts, $mappedBrands);
    }

    public function find_my_order_byId($orderId, $uid) {
        return $this->find('first', array(
            'conditions' => array('id' => $orderId, 'creator' => $uid),
        ));
    }

    public function find_all_my_order_byId($orderIds, $uid) {
        return $this->find('all', array(
            'conditions' => array('id' => $orderIds, 'creator' => $uid),
        ));
    }
//
//    public function whether_bought($pid, $creator) {
//        $cartM = ClassRegistry::init('Cart');
//        $cartItems = $cartM->balanced_items($pid, $creator);
//        $order_ids = Hash::extract($cartItems, '{n}.Cart.order_id');
//
//        if (!empty($order_ids)) {
//            $this->find('all', array(
//                'conditions' => array
//            ));
//        }
//    }

    public function used_code_cnt($uid, $code) {
        return $this->find('count', array('conditions' => array(
            'creator' => $uid,
            'applied_code' => $code
        )));
    }
}
