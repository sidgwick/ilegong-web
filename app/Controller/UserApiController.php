<?php

class UserApiController extends AppController
{

    public $components = array('OAuth.OAuth', 'Session', 'HxChat');

    public $uses = array('User');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $allow_action = array('test', 'ping', 'check_mobile_available');
        $this->OAuth->allow($allow_action);
        if (array_search($this->request->params['action'], $allow_action) == false) {
            $this->currentUser = $this->OAuth->user();
        }
        $this->autoRender = false;
    }

    public function reg_hx_user()
    {
        $user_id = $this->currentUser['id'];
        $result = $this->HxChat->reg_hx_user($user_id);
        echo json_encode($result);
        return;
    }

    public function profile()
    {
        $userM = ClassRegistry::init('User');
        $user_id = $this->currentUser['id'];
        $datainfo = $userM->find('first', array('recursive' => -1,
            'conditions' => array('id' => $user_id),
            'fields' => array('nickname', 'email', 'image', 'sex', 'companies', 'bio', 'mobilephone', 'email', 'username', 'id')));
        $this->set('my_profile', array('User' => $datainfo['User']));
        $this->set('_serialize', array('my_profile'));
    }

    public function test()
    {

    }

    public function check_mobile_available()
    {
        $this->autoRender = false;
        $mobile = $_REQUEST['mobile'];
        if ($this->User->hasAny(array('User.mobilephone' => $mobile))) {
            echo json_encode(array('statusCode' => -1, 'statusMsg' => '手机号已经被注册'));
            return;
        }
        echo json_encode(array('statusCode' => 1));
        return;
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
            return;
        }
        //todo valid username is mobile
        if ($this->User->save($user_info)) {
            echo json_encode(array('statusCode' => 1, 'statusMsg' => '你的账号和手机号绑定成功'));
            return;
        };
        echo json_encode(array('statusCode' => -1, 'statusMsg' => '绑定失败，亲联系客服'));
        return;
    }
}