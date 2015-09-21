<?php

class ShareUserBind extends AppModel {

    public $useTable = false;

    /**
     * @var array
     *
     * $key => share_id
     * $val => array() user_ids
     */
    var $shareBindData = array(
        446 => array(544307, 633345),
        542 => array(544307, 633345),
        22 => array(633345),
        484 => array(633345, 141)
    );

    public function checkUserCanManageShare($share_id, $user_id) {
        $shareBindInfo = $this->shareBindData[$share_id];
        if (empty($shareBindInfo)) {
            return false;
        }
        return in_array($user_id, $shareBindInfo);
    }
}