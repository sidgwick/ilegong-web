<?php

class ShareManageComponent extends Component
{

    public $components = array('ShareUti', 'WeshareBuy');

    public function get_index_products($tag_id)
    {
        $indexProductM = ClassRegistry::init('IndexProduct');
        $indexProducts = $indexProductM->find('all', array(
            'conditions' => array(
                'deleted' => DELETED_NO,
                'tag_id' => $tag_id
            ),
            'order' => array('sort_val ASC')
        ));
        return $indexProducts;
    }

    public function get_pool_product($id)
    {
        $indexProductM = ClassRegistry::init('PoolProduct');
        $indexProducts = $indexProductM->find('all', [
            'conditions' => [
                'PoolProduct.deleted' => DELETED_NO,
                'PoolProduct.id' => $id,
            ],
            'fields' => [
                'PoolProduct.*',
                'WeshareProducts.*',
                'Weshares.*'
                ],
            'joins' => [
                [
                    'table' => 'weshare_products',
                    'alias' => 'WeshareProducts',
                    'conditions' => [
                        'PoolProduct.weshare_id = WeshareProducts.weshare_id',
                    ],
                ], [
                    'table' => 'weshares',
                    'alias' => 'Weshares',
                    'conditions' => [
                        'PoolProduct.weshare_id = Weshares.id',
                    ],
                ],
            ],
            //'order' => array('weshare_id ASC')
        ]);

        return $this->rearrange_pool_product($indexProducts)[0];
    }
    public function get_pool_products()
    {
        $indexProductM = ClassRegistry::init('PoolProduct');
        $indexProducts = $indexProductM->find('all', [
            'conditions' => [
                'PoolProduct.deleted' => DELETED_NO,
            ],
            'fields' => [
                'PoolProduct.*',
                'WeshareProducts.*'
                ],
            'joins' => [
                [
                    'table' => 'weshare_products',
                    'alias' => 'WeshareProducts',
                    'conditions' => [
                        'PoolProduct.weshare_id = WeshareProducts.weshare_id',
                    ],
                ]
            ],
            //'order' => array('weshare_id ASC')
        ]);

        return $this->rearrange_pool_product($indexProducts);
    }

    private function rearrange_pool_product($data) {
        if (count($data) == 0) {
            return [];
        }

        $oldid = 0;
        $ak = -1;
        foreach ($data as $k => $v) {
            $productid = $v['PoolProduct']['id'];
            if ($oldid == $productid) {
                $arr[$ak]['WeshareProducts'][] = $v['WeshareProducts'];
            } else {
                $ak++;
                $arr[$ak]['PoolProduct'] = $v['PoolProduct'];
                $arr[$ak]['Weshares'] = $v['Weshares'];
                $arr[$ak]['WeshareProducts'][] = $v['WeshareProducts'];
                $oldid = $productid;
            }
        }

        return $arr;
    }

    public function save_pool_product($data)
    {
        // 更新cake_pool_products表
        $poolProductM = ClassRegistry::init('PoolProduct');
        $poolProduct = $data['PoolProduct'];
        $poolProductM->save($data);

        // 更新cake_weshare_products表
        $weshareProductM = ClassRegistry::init('WeshareProduct');
        foreach ($data['WeshareProduct'] as $item) {
            $weshareProductM->save($item);
        }

        // 更新cake_weshares表
        $wesharesM = ClassRegistry::init('Weshare');
        $wesharesM->save($data['Weshares']);

        return true;
    }

    public function save_index_product($data)
    {
        $indexProductM = ClassRegistry::init('IndexProduct');
        $share_id = $data['IndexProduct']['share_id'];
        $user_info = $this->get_user_info_by_share_id($share_id);
        $data['IndexProduct']['share_user_id'] = $user_info['id'];
        $data['IndexProduct']['share_user_img'] = get_user_avatar($user_info);
        $data['IndexProduct']['share_user_name'] = $user_info['nickname'];
        $result = $indexProductM->save($data);

        if(empty($data['IndexProduct']['id'])){
            $this->log('add index product '.$result['IndexProduct']['id'].': '.json_encode($result), LOG_INFO);
        }
        else{
            $this->log('update index product '.$result['IndexProduct']['id'].': '.json_encode($result), LOG_INFO);
        }

        $this->on_index_product_saved($result);
        return $result;
    }

    private function get_user_info_by_share_id($share_id)
    {
        $weshareM = ClassRegistry::init('Weshare');
        $userM = ClassRegistry::init('User');
        $share_info = $weshareM->find('first', array(
            'conditions' => array('id' => $share_id),
            'fields' => array('id', 'creator')
        ));
        $creator_id = $share_info['Weshare']['creator'];
        $user_info = $userM->find('first', array(
            'conditions' => array('id' => $creator_id),
            'fields' => array('id', 'nickname', 'avatar', 'image')
        ));
        return $user_info['User'];
    }

    /**
     * @param $shareId
     * @return percent
     */
    public function get_weshare_rebate_setting($shareId)
    {
        $proxyRebatePercentM = ClassRegistry::init('ProxyRebatePercent');
        $proxyRebatePercent = $proxyRebatePercentM->find('first', array(
            'conditions' => array(
                'share_id' => $shareId
            )
        ));
        return $proxyRebatePercent;
    }

    /**
     * @param $shareId
     * @return array
     * 获取到分享的物流设置
     */
    public function get_weshare_ship_settings($shareId)
    {
        $weshareShipSettingM = ClassRegistry::init('WeshareShipSetting');
        $shipSettings = $weshareShipSettingM->find('all', array(
            'conditions' => array(
                'weshare_id' => $shareId
            )
        ));

        return $shipSettings;
    }

    /**
     * @param $shareId
     * @return mixed
     * 分享地址
     */
    public function get_weshare_addresses($shareId)
    {
        $weshareAddressM = ClassRegistry::init('WeshareAddress');
        $weshareAddresses = $weshareAddressM->find('all', array(
            'conditions' => array(
                'weshare_id' => $shareId
            )
        ));
        return $weshareAddresses;
    }

    public function get_weshare_products($shareId)
    {
        $weshareProductM = ClassRegistry::init('WeshareProduct');
        $weshare_products = $weshareProductM->find('all', array(
            'conditions' => array(
                'weshare_id' => $shareId,
                'deleted' => DELETED_NO
            )
        ));
        return $weshare_products;
    }

    public function get_share_product_tags($uid)
    {
        $weshareProductTagM = ClassRegistry::init('WeshareProductTag');
        $tags = $weshareProductTagM->find('all', array(
            'conditions' => array(
                'user_id' => $uid,
                'deleted' => DELETED_NO
            )
        ));
        return $tags;
    }


    public function get_share_orders($share_id)
    {
        $OrderM = ClassRegistry::init('Order');
        $orders = $OrderM->find('all', array(
            'conditions' => array(
                'type' => ORDER_TYPE_WESHARE_BUY,
                'member_id' => $share_id,
                'status' => array(ORDER_STATUS_PAID, ORDER_STATUS_SHIPPED, ORDER_STATUS_RECEIVED, ORDER_STATUS_COMMENT, ORDER_STATUS_RETURNING_MONEY, ORDER_STATUS_RETURN_MONEY, ORDER_STATUS_COMMENT, ORDER_STATUS_DONE),
            ),
            'limit' => 2000
        ));
        return $orders;
    }

    public function get_users_data($user_ids)
    {
        $UserM = ClassRegistry::init('User');
        $user_data = $UserM->find('all', array(
            'conditions' => array(
                'id' => $user_ids
            ),
            'fields' => array('id', 'nickname')
        ));
        return $user_data;
    }


    public function set_dashboard_collect_data($uid)
    {
        $weshareM = ClassRegistry::init('Weshare');
        $orderM = ClassRegistry::init('Order');
        $userRelationM = ClassRegistry::init('UserRelation');
        $shareFaqM = ClassRegistry::init('ShareFaq');
        $share_count = $weshareM->find('count', array(
            'conditions' => array(
                'creator' => $uid
            )
        ));
        $last_300_shares = $weshareM->find('all', array(
            'conditions' => array(
                'creator' => $uid
            ),
            'order' => array('id' => 'desc'),
            'fields' => array('id'),
            'limit' => 300
        ));
        $last_300_share_ids = Hash::extract($last_300_shares, '{n}.Weshare.id');
        $order_count = $orderM->find('count', array(
            'conditions' => array(
                'member_id' => $last_300_share_ids,
                'type' => ORDER_TYPE_WESHARE_BUY,
                'status' => array(ORDER_STATUS_PAID, ORDER_STATUS_SHIPPED, ORDER_STATUS_RECEIVED, ORDER_STATUS_COMMENT, ORDER_STATUS_RETURNING_MONEY, ORDER_STATUS_RETURN_MONEY, ORDER_STATUS_COMMENT, ORDER_STATUS_DONE),
            )
        ));
        $faq_count = $shareFaqM->find('count', array(
            'conditions' => array(
                'receiver' => $uid,
                'has_read' => 0
            )
        ));
        $fans_count = $userRelationM->find('count', array(
            'conditions' => array(
                'user_id' => $uid
            )
        ));
        return array('share_count' => $share_count, 'order_count' => $order_count, 'faq_count' => $faq_count, 'fans_count' => $fans_count);
    }

    public function on_index_product_saved($index_product){
        $this->clear_cache_for_index_products_of_type($index_product['IndexProduct']['type']);
    }

    public function on_index_product_deleted($index_product){
        $this->clear_cache_for_index_products_of_type($index_product['IndexProduct']['type']);
    }

    public function clear_cache_for_index_products_of_type($type){
        // 普通情况下，只按type清除缓存即可；但有时候商品从一个type1修改为type2，需求同事清除type1和type2的缓存；
        Cache::write(INDEX_VIEW_PRODUCT_CACHE_KEY.'_0', '');
        Cache::write(INDEX_VIEW_PRODUCT_CACHE_KEY.'_1', '');
        Cache::write(INDEX_VIEW_PRODUCT_CACHE_KEY.'_2', '');
        Cache::write(INDEX_VIEW_PRODUCT_CACHE_KEY.'_3', '');
    }
}
