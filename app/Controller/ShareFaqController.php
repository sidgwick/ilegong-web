<?php

class ShareFaqController extends AppController {

    var $name = 'share_faq';

    var $uses = array('ShareFaq', 'Weshare', 'User');

    var $components = array('Weixin', 'WeshareBuy', 'ShareUtil', 'ShareFaqUtil');


    public function beforeFilter() {
        parent::beforeFilter();
        $this->layout = 'weshare_bootstrap';
    }

    //目前只有分享者可以看到
    /**
     * @param $shareId
     * @param $shareCreator
     * 分享者和用户交互的列表
     */
    public function faq_list($shareId, $shareCreator) {
        $faq_list = $this->ShareFaqUtil->share_faq_list($shareId, $shareCreator);
        $this->set($faq_list);
    }

    /**
     * @param $shareId
     * @param $userId
     * 分享者和购买者交流页面
     */
    public function faq($shareId, $userId) {
        $share_info = $this->get_share_info($shareId);
        //todo check is login
        $current_user_id = $this->currentUser['id'];
        $share_creator = $share_info['Weshare']['creator'];
        //join other faq info
        if ($current_user_id != $share_creator && $userId != $share_creator) {
            $this->redirect('/weshares/view/' . $shareId);
            return;
        }
        //别人发给分享者
        if ($userId != $share_creator) {
            if ($current_user_id != $userId) {
                $this->redirect('/weshares/view/' . $shareId);
                return;
            }
        }


        $user_info = $this->User->find('all', array(
            'conditions' => array(
                'id' => array($userId, $share_creator, $current_user_id)
            ),
            'fields' => array('id', 'nickname', 'image')
        ));
        $user_info = Hash::combine($user_info, '{n}.User.id', '{n}.User');
        $share_faqs = $this->ShareFaq->find('all', array(
            'conditions' => array(
                'share_id' => $shareId,
                'OR' => array(
                    'sender' => array($share_creator, $current_user_id),
                    'receiver' => array($share_creator, $current_user_id),
                )
            ),
            'order' => array('created ASC')
        ));
        $this->set('hide_footer', true);
        $this->set('current_user', $current_user_id);
        $this->set('share_id', $shareId);
        $this->set('receiver', $userId);
        $this->set('current_user_id', $current_user_id);
        $this->set('share_info', $share_info);
        $this->set('user_info', $user_info);
        $this->set('share_faqs', $share_faqs);
    }

    /**
     * 发送消息
     */
    public function create_faq() {
        $this->autoRender = false;
        //todo check login
        $sender = $this->currentUser['id'];
        $msg = $_REQUEST['msg'];
        $receiver = $_REQUEST['receiver'];
        $shareId = $_REQUEST['share_id'];
        $faq_data = array(
            'sender' => $sender,
            'receiver' => $receiver,
            'created' => date('Y-m-d H:i:s'),
            'share_id' => $shareId,
            'msg' => $msg
        );
        $faq_data = $this->ShareFaq->save($faq_data);
        $this->ShareFaqUtil->send_notify_template_msg($sender, $receiver, $msg, $shareId);
        echo json_encode($faq_data);
        return;
    }

    public function update_faq_read($shareId, $userId) {
        $this->autoRender = false;
        $this->ShareFaqUtil->update_user_faq_has_read($shareId, $userId);
        echo json_encode(array('success' => true));
        return;
    }

    public function get_sharer_faq_list($shareId, $shareCreator) {
        $this->autoRender = false;
        $unread_count = $this->ShareUtil->sharer_has_unread_info($shareId, $shareCreator);
        echo json_encode(array('unread_count' => $unread_count));
        return;
    }

    /**
     * @param $shareId
     * @param $userId
     * @param $shareCreator
     * 获取用户未读信息
     */
    public function get_user_unread_info($shareId, $userId, $shareCreator) {
        $this->autoRender = false;
        $unread_count = $this->ShareFaqUtil->has_unread_info($shareId, $userId, $shareCreator);
        echo json_encode(array('unread_count' => $unread_count));
        return;
    }


    private function get_share_info($share_id) {
        $share_info = $this->Weshare->find('first', array(
            'conditions' => array(
                'id' => $share_id
            )
        ));
        return $share_info;
    }

}