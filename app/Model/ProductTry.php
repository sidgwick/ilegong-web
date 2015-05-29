<?php
/**
 * Created by IntelliJ IDEA.
 * User: liuzhr
 * Date: 12/16/14
 * Time: 12:06 AM
 */

class ProductTry extends AppModel {

    public function find_trying() {
        $today = date('Y-m-d');
        $tomorrow = date('Y-m-d',strtotime('+1 day',strtotime($today)));
        $tries = $this->find('all', array(
            'conditions' => array(
                'deleted' => DELETED_NO,
                'status' => PRODUCT_TRY_ING,
                'start_time >=' => $today,
                'start_time <' => $tomorrow
            ),
            'order' => 'start_time DESC'
        ));

        foreach($tries as &$trying) {
            $trying['status'] = $trying->get_status();
        }

        return $tries;
    }

    public function find_global_trying(){
        $today = date('Y-m-d');
        $tomorrow = date('Y-m-d',strtotime('+1 day',strtotime($today)));
        $tries = $this->find('all', array(
            'conditions' => array(
                'deleted' => DELETED_NO,
                'status' => PRODUCT_TRY_ING,
                'start_time >=' => $today,
                'start_time <' => $tomorrow,
                'global_show' => TRY_GLOBAL_SHOW
            ),
            'order' => 'start_time DESC'
        ));

        foreach($tries as &$trying) {
            $trying['status'] = $trying->get_status();
        }

        return $tries;
    }

    function get_status(){
        if ($this->status == PRODUCT_TRY_ING) {
            if ($this->limit_num <= $this->sold_num ) {
                return 'sec_end';
            } else if (before_than($this->start_time)) {
                return 'sec_kill';
            } else {
                return 'sec_unstarted';
            }
        } else {
            return 'sec_end';
        }
    }

    static function cal_op($limit_num, $sold_num, $start_time, $status) {
        if ($status == PRODUCT_TRY_ING) {
            if ($limit_num <= $sold_num ) {
                return 'sec_end';
            } else if (before_than($start_time)) {
                return 'sec_kill';
            } else {
                return 'sec_unstarted';
            }
        } else {
            return 'sec_end';
        }
    }

} 