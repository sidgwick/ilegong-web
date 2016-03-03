<?php

// 包含：跟分享相关的业务逻辑
// 不包含：订单；产品街；首页产品；团长；用户
class ShareManageComponent extends Component
{
    var $uses = array('Weshare');

    public $components = array('ShareUtil');

    public function stop_weshare($uid, $weshare_id)
    {
        $this->Weshare->updateAll(array('status' => WESHARE_STOP_STATUS), array('id' => $weshare_id, 'creator' => $uid, 'status' => WESHARE_NORMAL_STATUS));
        //stop child share
        $this->Weshare->updateAll(array('status' => WESHARE_STOP_STATUS), array('refer_share_id' => $weshare_id, 'status' => WESHARE_NORMAL_STATUS, 'type' => GROUP_SHARE_TYPE));

        $this->on_weshare_stopped($uid, $weshare_id);
    }

    private function on_weshare_stopped($uid, $weshare_id){
        Cache::write(SHARE_DETAIL_DATA_CACHE_KEY . '_' . $weshare_id, '');
        Cache::write(USER_SHARE_INFO_CACHE_KEY . '_' . $uid, '');
    }

    public function clear_cache_for_index_products_of_type($type){
        Cache::write(INDEX_VIEW_PRODUCT_CACHE_KEY.'_0', '');
        Cache::write(INDEX_VIEW_PRODUCT_CACHE_KEY.'_1', '');
        Cache::write(INDEX_VIEW_PRODUCT_CACHE_KEY.'_2', '');
        Cache::write(INDEX_VIEW_PRODUCT_CACHE_KEY.'_3', '');
    }
}