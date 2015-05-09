<?php
/**
 * Created by PhpStorm.
 * User: ldy
 * Date: 15/3/16
 * Time: 下午6:31
 */
class TuanBuyingsController extends AppController{


    public $components = array('ProductSpecGroup');

    private static function key_balance_pids() {
        return "Balance.balance.pids";
    }
    public static function key_balanced_scores() {
        return "Balance.apply_scores";
    }
    public function detail($tuan_buy_id){
        $this->pageTitle = '团购详情';
        $this->loadModel('TuanTeam');
        if($tuan_buy_id == null){
            $this->redirect('/tuan_teams/mei_shi_tuan');
            return;
        }else{
            $tuan_b = $this->TuanBuying->find('first',array(
                'conditions' => array('id' => $tuan_buy_id)
            ));
        }
        if(empty($tuan_b)){
            $this->__message('该团购不存在', '/tuan_teams/mei_shi_tuan');
            return;
        }
        $tuan_team = $this->TuanTeam->find('first',array('conditions' => array('id' => $tuan_b['TuanBuying']['tuan_id'])));
        if(empty($tuan_team)){
            $this->__message('该团不存在', '/tuan_teams/mei_shi_tuan');
        }
        $this->loadModel('OfflineStore');
        $offline_store = $this->OfflineStore->findById($tuan_team['TuanTeam']['offline_store_id']);

        $current_time = time();
        $exceed_time = false;
        if(strtotime($tuan_b['TuanBuying']['end_time']) < $current_time){
            $exceed_time = true;
        }
        $this->set('exceed_time', $exceed_time);
        //团购的状态
        $tuan_buy_status = $tuan_b['TuanBuying']['status'];
        $this->set('tuan_buy_status', $tuan_buy_status);
        //tuan exceed redirect to a availble
        if($exceed_time||$tuan_buy_status!=0){
            $available_tuan = $this->TuanBuying->find('first',array(
                'conditions' => array(
                    'pid' => $tuan_b['TuanBuying']['pid'],
                    'status' => 0,
                    'end_time >' => date('Y-m-d H:i'),
                    'tuan_id' => $tuan_b['TuanBuying']['tuan_id']
                ),
                'order' => array('id DESC')
            ));
            if(!empty($available_tuan)){
                $available_tuan_id = $available_tuan['TuanBuying']['id'];
                $this->redirect('/tuan_buyings/detail/'.$available_tuan_id);
            }
        }
        $pid=$tuan_b['TuanBuying']['pid'];
        $end_time = $tuan_b['TuanBuying']['end_time'];
        $tuan_buy_type = $tuan_b['TuanBuying']['consignment_type'];
        //根据发货类型显示
        $consign_time = $tuan_b['TuanBuying']['consign_time'];
        $consign_time_text = $tuan_buy_type != 2 ? (empty($consign_time) ? ($tuan_buy_type == 0 ? "成团后发货" : "团满准备发货") : friendlyDateFromStr($consign_time, FFDATE_CH_MD)) : '请选择发货时间';
        $this->set(compact('pid', 'consign_time', 'consign_time_text', 'tuan_buy_id', 'tuan_team', 'end_time'));

        $sold_num = $tuan_b['TuanBuying']['sold_num'];
        $max_num = $tuan_b['TuanBuying']['max_num'];
        $per_buy_num = $tuan_b['TuanBuying']['limit_buy_num'];
        if($per_buy_num>0){
            $this->set('is_limit_num',true);
            //TODO limit buy num
            $this->set('limit_num',$per_buy_num);
        }
        if($max_num>0){
            if($sold_num>=$max_num){
                $this->set('is_limit',true);
            }
        }
        $this->set('sold_num',$sold_num);
        $this->set('tuan_id',$tuan_b['TuanBuying']['tuan_id']);
        $target_num = max($tuan_b['TuanBuying']['target_num'], 1);
        $this->set('target_num', $target_num);
        if($tuan_buy_type==2){
            //排期
            $consignment_dates = consignment_send_date($pid);
            if(!empty($consignment_dates)){
                $this->set('consignment_dates', $consignment_dates);
            }
        }
        $this->set('tuan_buy_type',$tuan_buy_type);
        $this->set('hideNav',true);
        if($this->is_weixin()){
            $currUid = empty($this->currentUser) ? 0 : $this->currentUser['id'];
            $weixinJs = prepare_wx_share_log($currUid, 'pid', $pid);
            $this->set($weixinJs);
            $this->set('jWeixinOn', true);
        }
        $this->loadModel('Product');
        $this->loadModel('Uploadfile');
        $this->loadModel('Brand');
        $Product = $this->Product->find('first',array('conditions' => array('id' => $pid,'deleted' => DELETED_NO)));
        $brand = $this->Brand->find('first', array(
            'conditions' => array('id' => $Product['Product']['brand_id']),
            'fields' => array('name', 'slug','coverimg')
        ));
        $this->set('brand',$brand);
        //get specs from database
        $product_spec_group = $this->ProductSpecGroup->extract_spec_group_map($pid,'spec_names');
        $this->set('product_spec_group',json_encode($product_spec_group));

        //product spec
        $specs_map = $this->ProductSpecGroup->get_product_spec_json($pid);
        if (!empty($specs_map['map'])) {
            $str = '<script>var _p_spec_m = {';
            foreach($specs_map['map'] as $mid => $mvalue) {
                $str .= '"'.$mvalue.'":"'. $mid ."\",";
            }
            $str .= '};</script>';
            $this->set('product_spec_map', $str);
        }
        $this->set('specs_map', $specs_map);

        $con = array('modelclass' => 'Product','fieldname' =>'photo','data_id' => $pid);
        $Product['Uploadfile']= $this->Uploadfile->find('all',array('conditions' => $con, 'order'=> array('sortorder DESC')));
        $original_price = $Product['Product']['original_price'];
        $tuan_price = $tuan_b['TuanBuying']['tuan_price'];
        if($tuan_price > 0){
            $this->set('tuan_price',$tuan_price);
        }else{
            $tuan_product_price = getTuanProductPrice($pid);
            if($tuan_product_price>0){
                $this->set('tuan_product_price',$tuan_product_price);
            }
        }
        $this->set('original_price',$original_price);
        $this->set('Product', $Product);
        $this->set('category_control_name', 'products');
        $this->set('current_data_id', $pid);

        $this->loadModel('Comment');
        //load shichi comment count
        $same_pids = get_group_product_ids($pid);
        $shi_chi_comment_count = $this->Comment->find('count',array(
            'conditions'=>array(
                'data_id'=>$same_pids,
                'status'=>1,
                'is_shichi_vote'=>1
            )
        ));
        $comment_count = $this->Comment->find('count',array(
            'conditions'=>array(
                'data_id'=>$same_pids,
                'status'=>1
            )
        ));
        if($_REQUEST['tagId']){
            $this->set('tagId',$_REQUEST['tagId']);
        }
        $this->set('shi_chi_comment_count',$shi_chi_comment_count);
        $this->set('comment_count',($comment_count-$shi_chi_comment_count));
        $this->set('limitCommentCount',COMMENT_LIMIT_IN_PRODUCT_VIEW);
        $recommC = $this->Components->load('ProductRecom');
        $recommends = $recommC->recommend($pid);
        $this->set('items', $recommends);
        if($tuan_team['TuanTeam']['type'] == 1){
            $this->set('big_tuan', true);
        }
        $this->set('tuan_team', $tuan_team);
        $this->set('tuan_address', get_address($tuan_team, $offline_store));
    }

    public function cart_info(){
        $this->autoRender = false;
        $this->loadModel('Cart');
        $this->loadModel('ConsignmentDate');
        $tuan_buy_id = intval($_REQUEST['tuan_buy_id']);
        $product_id = intval($_REQUEST['product_id']);
        $product_num = intval($_REQUEST['product_num']);
        $spec_id = intval($_REQUEST['spec_id']);
        $consignment_date_id = intval($_REQUEST['consignment_date_id']);
        $send_date = $_REQUEST['send_date'];

        if((empty($consignment_date_id) || $consignment_date_id == 0) && empty($send_date)){
            echo json_encode(array('success'=> false, 'error' => '对不起，系统错误，请重新点击购买'));
            return;
        }
        if(!empty($consignment_date_id) && $consignment_date_id != 0){
            $consignment_date = $this->ConsignmentDate->findById($consignment_date_id);
            if(empty($consignment_date) || $consignment_date['ConsignmentDate']['published'] == 0){
                echo json_encode(array('success'=> false, 'error' => '发货时间选择有误，请重新点击购买'));
                return;
            }
        }
        $uId = $this->currentUser['id'];
        $cart_tuan_param = array(
            'tuan_buy_id' => $tuan_buy_id,
            'product_id' => $product_id
        );
        $sessionId = $this->Session->id();
        $cartInfo = $this->Cart->add_to_cart($product_id,$product_num,$spec_id,ORDER_TYPE_TUAN,0,$uId, $sessionId,  null, null,$cart_tuan_param);
        $this->log('cartInfo'.json_encode($cartInfo));
        if($cartInfo){
            $result = $this->Cart->updateAll(array('consignment_date' => $consignment_date_id, 'send_date' => "'".$send_date."'"), array('id' => $cartInfo['Cart']['id']));
            if(!$result){
                echo json_encode(array('success'=> false, 'error' => '发货时间选择有误，请重新点击购买'));
                $this->log("failed to update consignment_date and send_date for cart ".$cartInfo['Cart']['id'].": consignment_date: ".$consignment_date_id.", send_date: ".$send_date);
                return;
            }

            if($_POST['way_type'] == 'ziti'){
                echo json_encode(array('success' => true, 'direct'=>'big_tuan_list', 'cart_id'=>$cartInfo['Cart']['id']));
            }else{
                $ship_fee = floatval($_POST['way_fee']);
                echo json_encode(array('success' => true, 'direct'=>'normal','way_type'=>$_POST['way_type'],'way_fee'=>$ship_fee, 'cart_id'=>$cartInfo['Cart']['id']));
            }
            $cart_array = array(0 => strval($cartInfo['Cart']['id']));
            $this->Session->write(self::key_balance_pids(), json_encode($cart_array));
        }else{
            echo json_encode(array('success'=> false, 'error' => '对不起，系统出错，请联系客服'));
        }
    }

    public function balance_tuan_sec_kill(){
        $this->pageTitle='订单确认';
        $tuan_id = $_REQUEST['tuan_id'];
        $cart_id = $_REQUEST['pid_list'];
        $try_id = $_REQUEST['try'];
        $from = $_REQUEST['from'];
        if($from!='tuan_sec'){
            if(empty($tuan_id)){
                $this->__message('请求出错', '/');
            }else{
                $this->__message('请求出错', '/tuan_teams/info/'.$tuan_id);
            }
            return;
        }
        $uid = $this->currentUser['id'];
        if(empty($uid)){
            $this->redirect('/users/login?referer=' . urlencode($_SERVER['REQUEST_URI']));
            return;
        }
        if(!empty($tuan_id)){
            $this->loadModel('TuanTeam');
            $team = $this->TuanTeam->find('first',array(
                'conditions' => array(
                    'id' => $tuan_id
                )
            ));
            if(empty($team)){
                $this->__message('该团不存在', '/tuan_teams/mei_shi_tuan');
                return;
            }
            $this->set('tuan_id', $tuan_id);
            $this->set('can_mark_address',$this->can_mark_address($tuan_id));
            $this->set('tuan_address', $team['TuanTeam']['tuan_addr']);
        }

        $this->loadModel('Cart');
        $Carts = $this->Cart->find('first', array(
            'conditions' => array(
                'id' => $cart_id
            ),
        ));
        $pid = $Carts['Cart']['product_id'];
        $total_price = $Carts['Cart']['price'] * $Carts['Cart']['num'];
        $this->loadModel('Product');
        $this->loadModel('Brand');
        $product_brand = $this->Product->find('first', array(
            'conditions' => array('id' => $pid),
            'fields' => array('brand_id')
        ));
        $brand = $this->Brand->find('first', array(
            'conditions' => array('id' => $product_brand['Product']['brand_id']),
            'fields' => array('name', 'slug')
        ));
        $this->loadModel('OrderConsignees');
        $consignee_info = $this->OrderConsignees->find('first', array(
            'conditions' => array('creator' => $uid, 'status' => STATUS_CONSIGNEES_TUAN),
            'fields' => array('name', 'address', 'mobilephone')
        ));
        if($consignee_info){
            $this->set('consignee_info', $consignee_info['OrderConsignees']);
        }
        $this->set('try_id',$try_id);
        $this->set('way_type','ziti');
        $this->set('buy_count',$Carts['Cart']['num']);
        $this->set('total_price', $total_price);
        $this->set('cart_id', $Carts['Cart']['id']);
        $this->set('cart_info',$Carts);
        $this->set('brand', $brand['Brand']);
        $this->set('hideNav',true);
    }

    public function balance($tuan_buy_id, $cart_id){
        $this->pageTitle = '订单确认';
        if(empty($this->currentUser['id'])){
            $this->redirect('/users/login?referer=' . urlencode($_SERVER['REQUEST_URI']));
            return;
        }

        $uid =$this->currentUser['id'];
        $user_condition = array(
            'session_id'=>	$this->Session->id(),
            'creator' => $uid
        );
        $cond = array(
            'id' => $cart_id,
            'OR' => $user_condition
        );
        $this->loadModel('Cart');
        $Carts = $this->Cart->find('first', array(
            'conditions' => $cond,
            'order' => 'id DESC'
        ));
        if(empty($Carts)){
            $message = '结算失败，请重试';
            $url = '/';
            $this->__message($message, $url);
            return;
        }

        $tuan_b = $this->TuanBuying->find('first', array(
            'conditions' => array('id' => $tuan_buy_id),
            'fields' => array('pid', 'tuan_id', 'status', 'end_time')
        ));
        $current_time = strtotime($tuan_b['TuanBuying']['end_time']);
        $sold_num = $tuan_b['TuanBuying']['sold_num'];
        $max_num = $tuan_b['TuanBuying']['max_num'];
        if($max_num>0){
            if($sold_num>=$max_num){
                $message = '该团购已经结束。';
                $url = '/';
                $this->__message($message, $url);
                return;
            }
        }
        if(empty($tuan_b) && $current_time < time()){
            $message = '该团购已到截止时间';
            $url = '/tuan_teams/mei_shi_tuan';
            $this->__message($message, $url);
            return;
        }
        $this->loadModel('TuanTeam');
        $tuan_info = $this->TuanTeam->findById($tuan_b['TuanBuying']['tuan_id']);
        if(empty($tuan_info)){
            $this->__message('该团不存在', '/tuan_teams/mei_shi_tuan');
            return;
        }
        $this->loadModel('OfflineStore');
        $offline_store = $this->OfflineStore->findById($tuan_info['TuanTeam']['offline_store_id']);

        $ship_fee = floatval($_REQUEST['way_fee']);
        $total_price = $Carts['Cart']['price'] * $Carts['Cart']['num'];
        $this->set('ship_fee',$ship_fee);
        $total_price = $total_price+floatval($ship_fee);
        $pid = $tuan_b['TuanBuying']['pid'];
        $this->loadModel('Product');
        $this->loadModel('Brand');
        $product_brand = $this->Product->find('first', array(
            'conditions' => array('id' => $pid),
            'fields' => array('brand_id')
        ));
        $brand = $this->Brand->find('first', array(
            'conditions' => array('id' => $product_brand['Product']['brand_id']),
            'fields' => array('name', 'slug')
        ));
        $this->loadModel('OrderConsignees');
        $consignee_info = $this->OrderConsignees->find('first', array(
            'conditions' => array('creator' => $uid, 'status' => STATUS_CONSIGNEES_TUAN),
            'fields' => array('name', 'address', 'mobilephone')
        ));
        if($consignee_info){
            $this->set('consignee_info', $consignee_info['OrderConsignees']);
        }
        //积分统计
        $this->loadModel('User');
        $score = $this->User->get_score($uid, true);
        $could_score_money = cal_score_money($score, $total_price);
        $this->set('score_usable', $could_score_money * 100);

        $tuan_id = $tuan_info['TuanTeam']['id'];
        $way_type = $_REQUEST['way_type'];
        $this->set('way_type',$way_type);
        $this->set('buy_count',$Carts['Cart']['num']);
        $this->set('total_price', $total_price);
        $this->set('cart_id', $Carts['Cart']['id']);
        $this->set('tuan_id', $tuan_id);
        $this->set('can_mark_address',$this->can_mark_address($tuan_id));
        $this->set('tuan_address', get_address($tuan_info, $offline_store));
        $this->set('end_time', date('m-d', $current_time));
        $this->set('tuan_buy_id', $tuan_buy_id);
        $this->set('cart_info',$Carts);
        $this->set('max_num',$max_num);
        $this->set('brand', $brand['Brand']);
        if($tuan_info['TuanTeam']['type'] == 1){
            $this->set('big_tuan', true);
        }
        $this->set('hideNav',true);
    }

    public function tuan_pay($orderId){
        $this->pageTitle = '团购';
        $this->loadModel('Order');
        $this->loadModel('Cart');
        $order_info = $this->Order->find('first', array(
            'conditions' =>array('id' => $orderId),
            'fields' => array('total_all_price', 'created', 'id', 'consignee_address', 'consignee_name', 'member_id','type','try_id')
        ));
        if($order_info['Order']['type'] == ORDER_TYPE_TUAN_SEC||$order_info['Order']['try_id'] > 0){
            $this->__message('只支持团购订单结算', '/orders/mine.html?tab=waiting_pay');
            return;
        }
        $cart_info = $this->Cart->find('first', array(
            'conditions' =>array('order_id' => $orderId),
            'fields' => array('num', 'price')
        ));
        $this->set('orderId', $orderId);
        $this->set('order', $order_info);
        $this->set('cart', $cart_info );
        if($_GET['tuan_id']){
            $this->set('tuan_id',$_GET['tuan_id'] );
        }
    }

    public function pre_order() {
        $this->autoRender = false;
        $cart_id = $_POST['cart_id'];
        $tuan_id = $_POST['tuan_id'];
        //tuan sec
        $tuan_sec = $_POST['tuan_sec'];
        $member_id = $_POST['member_id'];
        $mobile = $_POST['mobile'];
        $name = $_POST['name'];
        $shop_name = $_POST['shop_name'];
        $uid = $this->currentUser['id'];
        $way = $_POST['way'];
        $global_sec = $_POST['global_sec'];
        if (empty($uid)) {
            $this->log("not login for tuan order:".$cart_id);
            echo json_encode(array('success'=> false));
            return;
        }
        if(empty($cart_id)){
            $this->log("tuan cart id error:".$cart_id);
            echo json_encode(array('success'=> false));
            return;
        }
        $this->loadModel('Cart');
        $this->loadModel('Order');
        $this->loadModel('TuanTeam');
        $this->loadModel('OfflineStore');
        $cart_info = $this->Cart->findById($cart_id);
        $creator = $cart_info['Cart']['creator'];
        $order_type = $cart_info['Cart']['type'];
        if(empty($cart_info)){
            $this->log("cart record not exist". $cart_id);
            $res = array('success'=> false, 'info'=> '购物车记录为查询到');
        }elseif($creator != $uid){
            $this->log("no right to this order, uid".$uid. "creator:".$creator);
            $res = array('success'=> false, 'info'=> '团购订单不属于你，请刷新重试');
        }elseif($order_type != CART_ITEM_TYPE_TUAN&&$order_type != CART_ITEM_TYPE_TUAN_SEC){
            $res = array('success'=> false, 'info'=> '该订单不属于团购订单，请重试');
        }else{
            if(!empty($cart_info['Cart']['order_id'])){
                $this->log("cart order id error,cart id".$cart_id);
                return;
            }
            $total_price = $cart_info['Cart']['num'] * $cart_info['Cart']['price'];
            if($total_price < 0 ){
                $this->log("error tuan price, cart id".$cart_id);
                return;
            }
            $pid = $cart_info['Cart']['product_id'];
            $area = '';
            if($global_sec!="true"){
                $tuan_info = $this->TuanTeam->findById($tuan_id);
                if(empty($tuan_info)){
                    $this->log("can't find tuan ".$tuan_id);
                    return;
                }
                $offline_store = $this->OfflineStore->findById($tuan_info['TuanTeam']['offline_store_id']);
            }
            $this->loadModel('OrderConsignees');
            $consignees = array('name' => $name, 'mobilephone' => $mobile, 'status' => STATUS_CONSIGNEES_TUAN);
            $p_address = $_POST['address'];
            if($tuan_info['TuanTeam']['type'] == IS_BIG_TUAN||$global_sec=='true'){
                if($_POST['way'] != 'ziti'){
                    $consignees['address'] = $p_address;
                }
                $address = $p_address;
            }else{
                $address = get_address($tuan_info, $offline_store);
                if(!empty($p_address)){
                    $address = $address.'['.$p_address.']';
                }
                if(empty($address)){
                    $this->log("post address is empty ".$tuan_id);
                }
            }
            //TODO to set
            if($way == 'kddj'){
                if($pid==876){
                    //蔬菜加10元邮费
                    $total_price = $total_price+10;
                }
                if($pid==879){
                    //蔬菜加10元邮费
                    $total_price = $total_price+13;
                }
            }
            $tuan_consignees = $this->OrderConsignees->find('first', array(
                'conditions' => array('status' => STATUS_CONSIGNEES_TUAN, 'creator' => $uid),
                'fields' => array('id')
            ));
            if($tuan_consignees){
                $consignees['id'] = $tuan_consignees['OrderConsignees']['id'];
            }else {
                $consignees['creator'] = $uid;
            }
            $this->OrderConsignees->save($consignees);
            $offline_store_id = empty($tuan_info['TuanTeam']['offline_store_id'])?0:$tuan_info['TuanTeam']['offline_store_id'];
            $shop_id= $offline_store_id;
            if(!empty($_POST['shop_id'])){
                $shop_id= $_POST['shop_id'];
            }
            if($tuan_sec=='true'){
                //remark order sec kill
                $order = $this->Order->createTuanOrder($member_id, $uid, $total_price, $pid, $order_type, $area, $address, $mobile, $name, $cart_id, $way, '秒杀',$shop_id);
            }else{
                $order = $this->Order->createTuanOrder($member_id, $uid, $total_price, $pid, $order_type, $area, $address, $mobile, $name, $cart_id, $way, $shop_name, $shop_id);
            }
            $order_id = $order['Order']['id'];
            $score_consumed = 0;
            $spent_on_order = intval($this->Session->read(self::key_balanced_scores()));
            $order_id_spents = array();
            if($spent_on_order > 0 ) {
                $reduced = $spent_on_order / 100;
                $toUpdate = array('applied_score' => $spent_on_order,
                    'total_all_price' => 'if(total_all_price - ' . $reduced .' < 0, 0, total_all_price - ' . $reduced .')');
                if($this->Order->updateAll($toUpdate, array('id' => $order_id, 'status' => ORDER_STATUS_WAITING_PAY))){
                    $this->log('apply user score=' . $spent_on_order . ' to order-id=' . $order_id . ' successfully');
                    $score_consumed += $spent_on_order;
                    $order_id_spents[$order_id] = $spent_on_order;
                }
            }
            if ($score_consumed > 0) {
                $scoreM = ClassRegistry::init('Score');
                $scoreM->spent_score_by_order($uid, $score_consumed, $order_id_spents);
                $this->loadModel('User');
                $this->User->add_score($uid, -$score_consumed);
            }
            // 注意必须清除key_balanced_scores
            $this->Session->write(self::key_balanced_scores(), '');

            if ($order['Order']['status'] != ORDER_STATUS_WAITING_PAY) {
                $res = array('success'=> false, 'info'=> '你已经支付过了');
            }else{
                //$consign_time = friendlyDateFromStr($tuanBuy['TuanBuying']['consign_time'], FFDATE_CH_MD);
                $cart_name = $cart_info['Cart']['name'];
                if($tuan_info['TuanTeam']['type'] == 1 && $way == 'sf'){
                    $cart_name = $cart_name.'(顺丰到付)';
                }
                if($tuan_info['TuanTeam']['type'] == 1 && $way == 'baoyou'){
                    $cart_name = $cart_name.'(包邮)';
                }
                if($tuan_info['TuanTeam']['type'] == 1 && $way == 'kddj'){
                    $cart_name = $cart_name.'(快递到家)';
                }
                $this->Cart->update(array('name' => '\'' . $cart_name . '\'' ), array('id' => $cart_id));
                $res = array('success'=> true, 'order_id'=>$order['Order']['id']);
            }
        }
        echo json_encode($res);
    }

    function product_detail($pid){
        if(empty($pid)){
            return;
        }
        $tuan_buy_id = $_REQUEST['tuan_buy_id'];
        $fields = array('id','slug','name','content','created');
        $this->loadModel('Product');
        $Product =$this->Product->find('first', array(
            'conditions' => array('id' => $pid),
            'fields' => $fields
        ));
        $this->pageTitle = mb_substr($Product['Product']['name'],0,13);
        $this->set('pid',$pid);
        $this->set('tuan_buy_id',$tuan_buy_id);
        $this->set('Product',$Product);
        $this->set('hideNav',true);
    }
    public function goods(){
        $this->pageTitle = '团购商品';
        $currentDate = date(FORMAT_DATETIME);
        $tuanProducts = getTuanProducts();
        $tuanProducts = Hash::combine($tuanProducts,'{n}.TuanProduct.product_id','{n}.TuanProduct');
        $exclude_pids = array(863,900, 907);
        $tuan_product_ids = $this->TuanBuying->find('all',array('conditions' => array("pid != " => $exclude_pids,'status'=>0,'end_time > '=>$currentDate),'group' => array('pid')));
        $tuan_product_ids = Hash::extract($tuan_product_ids,'{n}.TuanBuying.pid');
        $this->loadModel('Product');
        $tuan_products_info = array();
        $tuan_productId = array();
        $tuan_product = $this->Product->find('all',array(
            'conditions' => array('id' => $tuan_product_ids),
            'fields' => array('deleted','name','original_price','price','priority','id'),
            'order' => 'Product.priority desc'
        ));
//        $tuan_product = Hash::combine($tuan_product,'{n}.Product.id','{n}.Product');
        foreach($tuan_product as $tuanProduct){
            $pid = $tuanProduct['Product']['id'];
            $tuan_products_info[$pid] = $this->TuanBuying->query("select sum(sold_num) as sold_number from cake_tuan_buyings  where pid = $pid");
            $tuan_products_info[$pid]['status'] = $tuanProduct['Product']['deleted'];
            $tuan_products_info[$pid]['name'] = $tuanProduct['Product']['name'];
            $tuan_products_info[$pid]['price'] = $tuanProduct['Product']['price'];
            $tuan_products_info[$pid]['list_img'] = $tuanProducts[$pid]['list_img'];
            $tuan_products_info[$pid]['original_price'] = $tuanProduct['Product']['original_price'];
            $tuan_productId[$pid] = $pid;
        }
        $this->set('tuan_product_ids',$tuan_productId);
        $this->set('tuan_products_info',$tuan_products_info);
        $this->set('hideNav',true);
    }

    public function goods_tuans($pid=null){
        $this->pageTitle = '团购列表';
        $this->loadModel('TuanTeam');
        $date_time = date('Y-m-d H:i:s', time());
        $tuan_buy_num = $this->TuanBuying->query("select sum(sold_num) as sold_number from cake_tuan_buyings  where pid = $pid");
        $tuan_buyings = $this->TuanBuying->find('all',array(
            'conditions' => array('pid' => $pid, 'status'=>0, 'end_time >'=> $date_time),
            'order' => array('TuanBuying.end_time')
        ));
        $tuan_ids = array_unique(Hash::extract($tuan_buyings, '{n}.TuanBuying.tuan_id'));
        $tuan_info = $this->TuanTeam->find('all', array(
            'conditions' =>array('id'=>$tuan_ids),
        ));
        $tuan_info = Hash::combine($tuan_info,'{n}.TuanTeam.id','{n}.TuanTeam');
        //只有一个大团
        if(count($tuan_info)==1){
            $tuan_id = $tuan_buyings[0]['TuanBuying']['tuan_id'];
            $tuan_buy_id = $tuan_buyings[0]['TuanBuying']['id'];
            $tuan = $tuan_info[$tuan_id];
            if($tuan['type']==1){
                $this->redirect('/tuan_buyings/detail/'.$tuan_buy_id);
            }
        }
        $this->loadModel('Product');
        $tuan_product = $this->Product->find('first', array(
            'conditions' => array('id' => $pid),
            'fields' => array('name', 'promote_name', 'price','id','original_price')
        ));
        $tuanProducts = getTuanProducts();
        $tuanProducts = Hash::combine($tuanProducts,'{n}.TuanProduct.product_id','{n}.TuanProduct');
        $tuan_product_price = getTuanProductPrice($pid);
        if($tuan_product_price>0){
            $this->set('tuan_product_price',$tuan_product_price);
        }
        if($_REQUEST['tagId']){
            $this->set('tagId',$_REQUEST['tagId']);
        }
        $this->set('detail_img',$tuanProducts[$pid]['detail_img']);
        $this->set('tuan_product', $tuan_product);
        $this->set('tuan_info',$tuan_info);
        $this->set('pid',$pid);
        $this->set('tuan_buyings',$tuan_buyings);
        $this->set('tuan_buy_num',$tuan_buy_num[0][0]['sold_number']);
        $this->set('hideNav',true);
    }

    public function balance_global_sec_kill(){
        $this->balance_tuan_sec_kill();
    }

    public function big_tuan_balance($tuan_buy_id, $cart_id){
        $this->balance($tuan_buy_id, $cart_id);
        $this->set('hideNav',true);
    }

    //可以备注的地址
    //TODO 昌平的的团可以备注地址
    private function can_mark_address($tuan_id){
        $mark_address_tuan_ids = array(15,25,28,41,43,45,46,47,48,58,60,66,104);
        return in_array($tuan_id,$mark_address_tuan_ids);
    }
    public function get_offline_address(){
        $this->autoRender = false;
        $this->loadModel('OfflineStores');
        $address = $this->OfflineStores->find('all',array(
            'conditions' => array('deleted'=>0),
        ));
        $address=Hash::combine($address, '{n}.OfflineStores.id', '{n}.OfflineStores', '{n}.OfflineStores.area_id');
        echo json_encode($address);
    }
}

