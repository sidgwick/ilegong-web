<?php

class UserApiController extends Controller
{
    public $uses = array('User', 'UserFriend', 'UserLevel', 'UserRelation');

    public $components = array('OAuth.OAuth', 'Orders', 'ChatUtil', 'WeshareBuy', 'ShareUtil', 'UserFans', 'Weshares');

    public function beforeFilter()
    {
        $allow_action = array('test', 'check_mobile_available', 'show_user_info');
        $this->OAuth->allow($allow_action);
        if ($_REQUEST['access_token']) {
            $this->currentUser = $this->OAuth->user();
        }
        $this->autoRender = false;
    }

    //获取优惠券数量
    public function get_coupon_count(){
        $uid = $this->currentUser['id'];
        $this->loadModel("CouponItem");
        $coupon_count = $this->CouponItem->find_my_share_coupons_count($uid);
        echo json_encode(['coupon_count' => $coupon_count]);
        exit;
    }

    //获取我的优惠券
    public function get_my_coupons(){
        $uid = $this->currentUser['id'];
        $this->loadModel("CouponItem");
        $result = [];
        $coupons = $this->CouponItem->find_my_all_valid_share_coupons($uid);
        foreach($coupons as $item){
            $coupon = [];
            $coupon['sharer_id'] = $item['ShareOffer']['sharer_id'];
            $coupon['reduced_price'] = number_format($item['Coupon']['reduced_price'] / 100);
            $coupon['product_list'] = $item['Coupon']['product_list'];
            $coupon['name'] = $item['ShareOffer']['name'];
            $coupon['expired'] = friendlyDateFromStr($item['Coupon']['valid_end'], 'ymd');
            $result[] = $coupon;
        }
        echo json_encode($result);
        exit;
    }

    //获取积分列表
    public function get_score_logs($page, $limit){
        $uid = $this->currentUser['id'];
        $this->loadModel('Score');
        $scores = $this->Score->find_user_score_logs_by_page($uid, $page, $limit);
        $list = [];
        foreach ($scores as $score) {
            $list[] = [
                'id' => $score['Score']['id'],
                'num' => $score['Score']['score'],
                'reason' => $score['Score']['desc']
            ];
        }
        echo json_encode($list);
        exit;
    }

    //查看别人的信息
    public function show_user_info($uid)
    {
        $curr_uid = $this->currentUser['id'];
        $user_summary = $this->WeshareBuy->get_user_share_summary($uid);
        $user_info = $this->get_user_info($uid);
        $user_info['image'] = get_user_avatar($user_info);
        $sub_status = false;
        if (!empty($curr_uid)) {
            $sub_status = $this->ShareUtil->check_user_is_subscribe($uid, $curr_uid);
        }
        echo json_encode(['sub_status' => $sub_status, 'user_summary' => $user_summary, 'user_info' => $user_info['User']]);
        exit();
    }

    //获取用户创建的分享
    public function get_user_create_shares($uid, $limit, $page)
    {
        $result = $this->Weshares->get_u_create_share($uid, $limit, $page);
        echo json_encode($result);
        exit();
    }

    //获取用户购买的分享
    public function get_user_buy_shares($uid, $limit, $page)
    {
        $result = $this->Weshares->get_u_buy_share($uid, $limit, $page);
        echo json_encode($result);
        exit();
    }

    //个人详情
    public function user_detail()
    {
        $uid = $this->currentUser['id'];
        $user_summary = $this->WeshareBuy->get_user_share_summary($uid);
        $order_summary = $this->WeshareBuy->get_user_order_summary($uid);
        $share_summary = $this->WeshareBuy->get_sharer_summary($uid);
        $share_summary['new_month_trade_money'] = intval(floatval($share_summary['month_trade_money']) * 100);
        $share_summary['month_trade_money'] = intval($share_summary['month_trade_money']);
        echo json_encode(['user_summary' => $user_summary, 'order_summary' => $order_summary, 'share_summary' => $share_summary]);
        exit();
    }

    public function user_orders($status, $limit, $page)
    {
        $uid = $this->currentUser['id'];
        if ($status == -1) {
            $status = [ORDER_STATUS_PAID, ORDER_STATUS_DONE, ORDER_STATUS_RETURN_MONEY, ORDER_STATUS_RETURNING_MONEY, ORDER_STATUS_REFUND_DONE, ORDER_STATUS_SHIPPED, ORDER_STATUS_RECEIVED];
        }
        $params = ['user_id' => $uid, 'status' => $status, 'limit' => $limit, 'page' => $page];
        $orders = $this->Orders->get_user_order($params);
        $result = [];
        foreach ($orders as $order_item) {
            $result_item = $order_item['Order'];
            $result_item['cart_info'] = get_share_order_cart_display_name($order_item['Cart']);
            $result_item['share_info'] = $order_item['Weshare'];
            $result[] = $result_item;
        }
        echo json_encode($result);
        exit();
    }

    public function user_order_detail($order_id)
    {
        $uid = $this->currentUser['id'];
        $order = $this->Orders->get_order_info_with_cart($order_id);
        $sharer_info = $this->WeshareBuy->get_sharer_info_with_sub_status($order['Order']['brand_id'], $uid);
        $user_info = $sharer_info['User'];
        $user_info['image'] = get_user_avatar($user_info);
        $user_info['level'] = $sharer_info['UserLevel']['data_value'];
        $user_info['level_name'] = get_user_level_text($sharer_info['UserLevel']['data_value']);
        $user_info['sub_status'] = !(empty($sharer_info['UserRelation']['id']));
        $result = $order['Order'];
        $result['weshare_status'] = $order['Weshare']['status'];
        $result['pay_type'] = $order['Pay']['trade_type'];
        $result['carts'] = $order['carts'];
        $result['sharer_info'] = $user_info;
        echo json_encode($result);
        exit();
    }


    public function confirm_order_received($order_id)
    {
        $uid = $this->currentUser['id'];
        $result = $this->ShareUtil->confirm_received_order($order_id, $uid);
        echo json_encode($result);
        exit();
    }

    public function reg_hx_user()
    {
        $user_id = $this->currentUser['id'];
        $result = $this->ChatUtil->reg_hx_user($user_id);
        echo json_encode($result);
        exit;
    }

    public function profile()
    {
        $user_id = $this->currentUser['id'];
        $datainfo = $this->get_user_info($user_id);
        $userInfo = $datainfo['User'];
        $user_summery = $this->WeshareBuy->get_user_share_summary($user_id);
        $rebate_money = $this->ShareUtil->get_rebate_money($user_id);
        $user_summery['rebate_money'] = $rebate_money;
        $user_level = $this->ShareUtil->get_user_level($user_id);
        $userInfo['level'] = $user_level;
        $userInfo['rebate_money'] = $userInfo['rebate_money'] / 100;
        echo json_encode(array('my_profile' => array('User' => $userInfo), 'user_summery' => $user_summery));
        exit();
    }

    public function my_profile()
    {
        $user_id = $this->currentUser['id'];
        $datainfo = $this->get_user_info($user_id);
        $userInfo = $datainfo['User'];
        echo json_encode($userInfo);
        exit();
    }

    public function change_password()
    {
        $user_id = $this->currentUser['id'];
        $new_password = $_REQUEST['password'];
        $hash_password = Security::hash($new_password, null, true);
        $this->User->update(['password' => "'" . $hash_password . "'"], ['id' => $user_id]);
        echo json_encode(['success' => true]);
        exit();
    }

    public function update_avatar()
    {
        $user_id = $this->currentUser['id'];
        $avatar_url = $_REQUEST['url'];
        $this->User->update(['image' => "'" . $avatar_url . "'", 'avatar' => "'" . $avatar_url . "'"], ['id' => $user_id]);
        echo json_encode(['success' => true]);
        exit();
    }

    public function update_desc()
    {
        $user_id = $this->currentUser['id'];
        $desc = $_REQUEST['desc'];
        $this->User->update(['description' => "'" . $desc . "'"], ['id' => $user_id]);
        echo json_encode(['success' => true]);
        exit();
    }


    public function update_nickname()
    {
        $user_id = $this->currentUser['id'];
        $nickname = $_REQUEST['nickname'];
        $this->User->update(['nickname' => "'" . $nickname . "'"], ['id' => $user_id]);
        echo json_encode(['success' => true]);
        exit();
    }

    public function subscribe($uid)
    {
        $user_id = $this->currentUser['id'];
        $this->WeshareBuy->subscribe_sharer($uid, $user_id);
        echo json_encode(['success' => true]);
        exit();
    }

    public function  unsubscribe($uid)
    {
        $user_id = $this->currentUser['id'];
        $this->WeshareBuy->unsubscribe_sharer($uid, $user_id);
        echo json_encode(['success' => true]);
        exit();
    }

    /**
     * 绑定支付方式
     */
    public function bind_payment()
    {
        /*
         * {
         *   "type" : 0 =>[支付宝] , 1 => [银行卡]
         *   "account" : 支付宝账号或者银行卡账号
         *   "full_name" : 姓名,
         *   "card_type" : 银行卡类型[选择银行卡的时候有效],
         *   "card_name" : 银行卡名称[选择银行卡的时候有效]
         * }
         */
        $post_str = file_get_contents('php://input');
        $user_id = $this->currentUser['id'];
        $this->User->update(['payment' => "'" . $post_str . "'"], ['id' => $user_id]);
        echo json_encode(['success' => true]);
        exit();
    }

    /**
     * 绑定手机号码
     */
    public function bind_mobile()
    {
        $mobile = $_REQUEST['mobile'];
        $mobileUser = $this->User->find('first',
            array('conditions' => array('mobilephone' => $mobile, 'published' => 1))
        );
        if (!empty($mobileUser)) {
            echo json_encode(['success' => false]);
            exit;
        }
        $uid = $this->currentUser['id'];
        $this->User->update(['mobilephone' => "'" . $mobile . "'"], ['id' => $uid]);
        $this->loadModel('Score');
        $result = $this->Score->add_score_by_bind_mobile($uid, BIND_MOBILE_AWARD_SCORE, $mobile);
        if ($result) {
            $this->User->add_score($uid, BIND_MOBILE_AWARD_SCORE);
        }
        echo json_encode(['success' => true]);
        exit;
    }

    private function get_user_info($user_id)
    {
        $datainfo = $this->User->find('first', array('recursive' => -1,
            'conditions' => array('id' => $user_id),
            'fields' => array('nickname', 'image', 'sex', 'mobilephone', 'username', 'id', 'hx_password', 'description', 'payment', 'avatar', 'rebate_money', 'own_id', 'score')));
        $datainfo['User']['image'] = get_user_avatar($datainfo);

        $payment = json_decode($datainfo['User']['payment'], true);
        if(is_array($payment)) {
            $payment['type'] = strval($payment["type"]);
        }
        $datainfo['User']['payment'] = json_encode($payment);
        return $datainfo;
    }


    public function test()
    {
        echo 'hello world';
        exit();
    }

    /**
     * @param $page
     * @param $limit
     * 接口要替换
     */
    public function load_fans($page, $limit)
    {
        $user_id = $this->currentUser['id'];
        $fans_data = $this->UserRelation->find('all', array(
            'conditions' => array(
                'user_id' => $user_id
            ),
            'limit' => $limit,
            'offset' => ($page - 1) * $limit,
            'order' => array('id DESC')
        ));
        $fans_id = Hash::extract($fans_data, '{n}.UserRelation.follow_id');
        if (empty($fans_id)) {
            echo json_encode(array());
            exit();
        }
        $users = $this->ChatUtil->get_users_info($fans_id);
        echo json_encode($users);
        exit();
    }

    /**
     * @param $type 0=>粉丝 1=>关注
     * @param $uid
     * @param $page
     * @param $limit
     * @param $is_own
     */
    public function get_u_list_data($type, $uid, $page, $limit, $is_own = 0)
    {
        $query = $_REQUEST['query'];
        if ($type == 0) {
            $data = $this->UserFans->get_fans($uid, $page, $query, $limit, $is_own);
        } else {
            $data = $this->UserFans->get_subs($uid, $page, $query, $limit);
        }
        $data['users'] = Hash::extract($data['users'], '{n}.User');
        echo json_encode($data);
        exit();
    }

    /**
     * 获取粉丝汇总信息
     */
    public function get_u_fans_summary(){
        $uid = $this->currentUser['id'];
        $summary = $this->UserFans->get_fans_count_summary($uid);
        echo json_encode($summary);
        exit;
    }

    /**
     * 获取评论的数据
     */
    public function get_comment_list()
    {
        $uid = $this->currentUser['id'];
        $user_share_data = $this->WeshareBuy->prepare_user_share_info($uid);
        $my_create_share_ids = $user_share_data['my_create_share_ids'];
        $shareCommentData = $this->WeshareBuy->load_sharer_comment_data($my_create_share_ids, $uid);
        $userCommentData = $this->WeshareBuy->load_user_share_comments($uid);
        echo json_encode(array('sharer_comment_data' => $shareCommentData, 'user_comment_data' => $userCommentData));
        exit();
    }

    public function delete_friend($friend_id)
    {
        $user_id = $this->currentUser['id'];
        if ($this->UserFriend->updateAll(array('deleted' => DELETED_YES), array('user_id' => $user_id, 'friend_id' => $friend_id))) {
            if ($this->ChatUtil->delete_friend($user_id, $friend_id)) {
                echo json_encode(array('statusCode' => 1, 'statusMsg' => '删除成功'));
                exit();
            }
        }

        echo json_encode(array('statusCode' => -1, 'statusMsg' => '删除失败'));
        exit();
    }

    public function add_friend($friend_id)
    {
        $user_id = $this->currentUser['id'];
        if (!$this->UserFriend->hasAny(array('user_id' => $user_id, 'friend_id' => $friend_id, 'deleted' => DELETED_NO))) {
            $date_now = date('Y-m-d H:i:s');
            $save_data = array();
            //互相添加好友
            $save_data[] = array('user_id' => $user_id, 'friend_id' => $friend_id, 'created' => $date_now, 'updated' => $date_now);
            $save_data[] = array('user_id' => $friend_id, 'friend_id' => $user_id, 'created' => $date_now, 'updated' => $date_now);
            $friend_data = $this->UserFriend->saveAll($save_data);
            if ($friend_data) {
                $result = $this->ChatUtil->add_friend($user_id, $friend_id);
                if (!$result) {
                    $this->log('add hx user friend error');
                }
                $friend_info = $this->get_user_info($friend_id);
                echo json_encode(array('statusCode' => 1, 'statusMsg' => '添加成功', 'data' => $friend_data, 'friend_info' => $friend_info['User']));
                exit();
            } else {
                echo json_encode(array('statusCode' => -1, 'statusMsg' => '添加失败'));
                exit();
            }
        }
        echo json_encode(array('statusCode' => 2, 'statusMsg' => '已经是好友'));
        exit();
    }

    /**
     * 获取好友列表
     */
    public function get_friends()
    {
        $user_id = $this->currentUser['id'];
        $friends_data = $this->UserFriend->find('all', array(
            'conditions' => array(
                'user_id' => $user_id,
                'deleted' => DELETED_NO,
                'status' => 0
            ),
            'limit' => 500
        ));
        $friend_ids = Hash::extract($friends_data, '{n}.UserFriend.friend_id');
        $data = $this->ChatUtil->get_users_info($friend_ids);
        echo json_encode($data);
        exit();
    }

    public function check_mobile_available()
    {
        $this->autoRender = false;
        $mobile = $_REQUEST['mobile'];
        if ($this->User->hasAny(array('User.mobilephone' => $mobile))) {
            echo json_encode(array('statusCode' => -1, 'statusMsg' => '手机号已经被注册'));
            exit();
        }
        echo json_encode(array('statusCode' => 1));
        exit();
    }

    public function bind_mobile_api()
    {
        $this->autoRender = false;
        $uid = $this->currentUser['id'];
        $mobile_num = $_REQUEST['mobile'];
        $user_info = array();
        $user_info['User']['mobilephone'] = $mobile_num;
        $user_info['User']['id'] = $uid;
        $user_info['User']['uc_id'] = 5;
        if ($this->User->hasAny(array('User.mobilephone' => $mobile_num))) {
            echo json_encode(array('statusCode' => -1, 'statusMsg' => '你的手机号已注册过，无法绑定，请用手机号登录'));
            exit();
        }
        //todo valid username is mobile
        if ($this->User->save($user_info)) {
            echo json_encode(array('statusCode' => 1, 'statusMsg' => '你的账号和手机号绑定成功'));
            exit();
        };
        echo json_encode(array('statusCode' => -1, 'statusMsg' => '绑定失败，亲联系客服'));
        exit();
    }

    public function rebate_detail($limit, $page) {
        $uid = $this->currentUser['id'];
        $result = $this->ShareUtil->get_rebate_list($uid, $page, $limit);
        echo json_encode($result);
        exit;
    }

    protected function get_post_raw_data()
    {
        $postStr = file_get_contents('php://input');
        $postData = json_decode($postStr, true);
        return $postData;
    }
}