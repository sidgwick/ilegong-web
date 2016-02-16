<?php

class SharerApiController extends AppController
{


    public $components = array('OAuth.OAuth', 'Session', 'WeshareBuy', 'ShareUtil');


    public function beforeFilter()
    {
        $allow_action = array('test');
        $this->OAuth->allow($allow_action);
        if (array_search($this->request->params['action'], $allow_action) == false) {
            $this->currentUser = $this->OAuth->user();
        }
        $this->autoRender = false;
    }

    public function get_my_shares(){
        $uid = $this->currentUser['id'];
        $createShares = $this->WeshareBuy->get_my_create_shares($uid);
        $share_ids = Hash::extract($createShares, '{n}.Weshare.id');
        $query_order_sql = 'select count(id), member_id from cake_orders where member_id in (' . implode(',', $share_ids) . ') and status=1 group by member_id';
        $orderM = ClassRegistry::init('Order');
        $result = $orderM->query($query_order_sql);
        $result = Hash::combine($result, '{n}.cake_orders.member_id', '{n}.count(id)');
        $share_balacne_money = $this->get_share_balance_result($share_ids);
        $createShares = Hash::extract($createShares, '{n}.Weshare');
        foreach ($createShares as &$shareItem) {
            $shareItem['order_count'] = $result[$shareItem['id']];
            $shareItem['balance_money'] = $share_balacne_money[$shareItem['id']];
        }
        echo json_encode($createShares);
        return;
    }

    private function  get_share_balance_result($share_ids){
        $balance_result = $this->WeshareBuy->get_shares_balance_money($share_ids);
        $summery_data = $balance_result['weshare_summery'];
        $weshare_repaid_map = $balance_result['weshare_repaid_map'];
        $weshare_rebate_map = $balance_result['weshare_rebate_map'];
        $weshare_refund_map = $balance_result['weshare_refund_map'];
        $result = array();
        foreach ($share_ids as $share_id) {
            $current_share_repaid_money = $weshare_repaid_map[$share_id];
            if ($current_share_repaid_money == 0) {
                $current_share_repaid_money = 0;
            }
            $result[$share_id] = floatval($summery_data[$share_id]['total_price']) - floatval($weshare_refund_map[$share_id]) - floatval($weshare_rebate_map[$share_id]) + $current_share_repaid_money;
        }
        return $result;
    }
}