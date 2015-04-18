<?php
/**
 * Created by PhpStorm.
 * User: shichaopeng
 * Date: 4/18/15
 * Time: 17:03
 */
class TuanSecKillController extends AppController{

    var $name = 'TuanSecKill';

    var $uses = array('ProductTry','Product');

    public function admin_index(){
        $query_cond = array();
        $tuan_id = $_REQUEST['tuan_id'];
        $product_id = $_REQUEST['product_id'];
        $start_time = $_REQUEST['start_time'];
        $end_time = $_REQUEST['end_time'];
        if(empty($start_time)){
            $start_time = date('Y-m-d H:i',strtotime('-7 days'));
        }
        if(empty($end_time)){
            $end_time = date('Y-m-d H:i',strtotime('+1 hours'));
        }
        if(!empty($tuan_id)){
            $query_cond['tuan_id'] = $tuan_id;
        }
        if(!empty($product_id)){
            $query_cond['product_id'] = $product_id;
        }

        $query_cond['start_time >='] = $start_time;

        $query_cond['end_time <'] = $end_time;

        $datas = $this->ProductTry->find('all',array(
            'conditions' => $query_cond,
            'order' => 'start_time DESC',
        ));
        $this->set('datas',$datas);
    }

    public function admin_new(){

    }

    public function admin_add(){

    }

    public function admin_edit(){

    }

    public function admin_update(){

    }

    public function admin_delete(){

    }

}