<?php

class ChatApiController extends Controller
{


    public $components = array('OAuth.OAuth', 'Session', 'ChatUtil');

    public $uses = array('ChatGroup', 'UserGroup', 'UserFriend', 'User');

    public function beforeFilter()
    {
        $allow_action = array();
        $this->OAuth->allow($allow_action);
        if (array_search($this->request->params['action'], $allow_action) == false) {
            $this->currentUser = $this->OAuth->user();
        }
        $this->autoRender = false;
    }

    public function create_group()
    {
        $postData = parent::get_post_raw_data();
        $hx_group_id = $this->ChatUtil->create_group($postData);
        //save database
        $result = $this->save_group_data($postData, $hx_group_id);
        if ($result) {
            echo json_encode(array('statusCode' => 1, 'data' => $result, 'statusMsg' => '创建成功'));
            return;
        }
        echo json_encode(array('statusCode' => -1, 'statusMsg' => '创建失败'));
        return;
    }

    function save_group_data($data, $hx_group_id)
    {

        $chatGroupM = ClassRegistry::init('ChatGroup');
        $userGroupM = ClassRegistry::init('UserGroup');
        $date_now = date('Y-m-d H:i:s');
//        $approval = $data['approval'] ? 1 : 0;
//        $public = $data['public'] ? 1 : 0;
        $approval = 0;
        $public = 0;
        $creator = $data['owner'];
        $desc = $data['desc'];
        $groupname = $data['groupname'];
        $group_data = array('hx_group_id' => $hx_group_id, 'created' => $date_now, 'creator' => $creator, 'approval' => $approval, 'maxusers' => 500, 'is_public' => $public, 'description' => $desc, 'group_name' => $groupname);
        $group_result = $chatGroupM->save($group_data);
        if ($group_result) {
            $group_id = $group_result['ChatGroup']['id'];
            $member_ids = $data['members'] ? $data['members'] : array();
            $member_ids[] = $data['owner'];
            $member_data = [];
            foreach ($member_ids as $m_id) {
                $member_data[] = array('user_id' => $m_id, 'group_id' => $group_id, 'created' => $date_now, 'updated' => $date_now);
            }
            $userGroupM->saveAll($member_data);
            return $group_result;
        }
        return false;
    }

    public function update_group()
    {

    }

    public function get_my_groups()
    {
        $uid = $this->currentUser['id'];
        $user_groups = $this->UserGroup->find('all', array(
            'conditions' => array(
                'deleted' => DELETED_NO,
                'user_id' => $uid
            ),
            'limit' => 500
        ));
        $group_ids = Hash::extract($user_groups, '{n}.UserGroup.group_id');
        $groups = $this->ChatGroup->find('all', array(
            'conditions' => array(
                'id' => $group_ids
            )
        ));
        $groups = Hash::extract($groups, '{n}.ChatGroup');
        echo json_encode(array('groups' => $groups));
        return;
    }

    public function get_group_info($hx_group_id)
    {
        $chatGroupM = ClassRegistry::init('ChatGroup');
        $chatGroup = $chatGroupM->find('first', array(
            'conditions' => array(
                'hx_group_id' => $hx_group_id
            )
        ));
        $userGroupM = ClassRegistry::init('UserGroup');
        $group_id = $this->get_group_id($hx_group_id);
        $member_count = $userGroupM->find('count', array(
            'conditions' => array(
                'group_id' => $group_id,
                'deleted' => DELETED_NO
            )
        ));
        $groupInfo = $chatGroup['ChatGroup'];
        $groupInfo['member_count'] = $member_count;
        echo json_encode($groupInfo);
        return;
    }

    public function get_group_members($hx_group_id)
    {
        $group_id = $this->get_group_id($hx_group_id);
        $members = $this->UserGroup->find('all', array(
            'conditions' => array(
                'group_id' => $group_id,
                'deleted' => DELETED_NO
            ),
            'limit' => 500
        ));
        $member_ids = Hash::extract($members, '{n}.UserGroup.user_id');
        $data = $this->ChatUtil->get_users_info($member_ids);
        echo json_encode($data);
        return;
    }

    public function add_group_members($hx_group_id)
    {
        $postData = parent::get_post_raw_data();
        $user_ids = $postData['usernames'];
        $save_data = array();
        $date_now = date('Y-m-d H:i:s');
        $group_id = $this->get_group_id($hx_group_id);
        foreach ($user_ids as $uid) {
            if (!$this->UserGroup->hasAny(array('user_id' => $uid, 'group_id' => $group_id))) {
                $save_data[] = array('user_id' => $uid, 'group_id' => $group_id, 'created' => $date_now, 'updated' => $date_now);
            }
        }
        $save_result = $this->UserGroup->saveAll($save_data);
        if ($save_result) {
            $this->ChatUtil->add_group_members($user_ids, $hx_group_id);
            echo json_encode(array('statusCode' => 1, 'statusMsg' => '添加成功'));
            return;
        }
        echo json_encode(array('statusCode' => -2, 'statusMsg' => '添加失败'));
        return;
    }

    public function add_group_member($hx_group_id, $user_id)
    {
        $group_id = $this->get_group_id($hx_group_id);
        if ($this->UserGroup->hasAny(array('user_id' => $user_id, 'group_id' => $group_id, 'deleted' => DELETED_NO))) {
            echo json_encode(array('statusCode' => -1, 'statusMsg' => '用户已经在群里'));
            return;
        }
        $date_now = date('Y-m-d H:i:s');
        $save_data = array('user_id' => $user_id, 'group_id' => $group_id, 'created' => $date_now, 'updated' => $date_now);
        $user_group = $this->UserGroup->save($save_data);
        if ($user_group) {
            $this->ChatUtil->add_group_member($user_id, $hx_group_id);
            echo json_encode(array('statusCode' => 1, 'statusMsg' => '添加成功', 'data' => $user_group));
            return;
        }
        echo json_encode(array('statusCode' => -1, 'statusMsg' => '添加失败'));
        return;
    }

    public function delete_group_member($hx_group_id, $user_id)
    {
        $group_id = $this->get_group_id($hx_group_id);
        $update_result = $this->UserGroup->updateAll(array('deleted' => DELETED_YES), array('group_id' => $group_id, 'user_id' => $user_id, 'deleted' => DELETED_NO));
        if ($update_result) {
            $this->ChatUtil->delete_group_member($user_id, $hx_group_id);
            echo json_encode(array('statusCode' => 1, 'statusMsg' => '删除成功'));
            return;
        }
        echo json_encode(array('statusCode' => -1, 'statusMsg' => '删除失败'));
        return;
    }

    public function delete_group_members($hx_group_id)
    {
        $postData = parent::get_post_raw_data();
        $user_ids = $postData['usernames'];
        $group_id = $this->get_group_id($hx_group_id);
        $update_result = $this->UserGroup->updateAll(array('deleted' => DELETED_YES), array('group_id' => $group_id, 'user_id' => $user_ids));
        if ($update_result) {
            $hx_group_id = $this->get_hx_group_id($group_id);
            $this->ChatUtil->delete_group_members($user_ids, $hx_group_id);
            echo json_encode(array('statusCode' => 1, 'statusMsg' => '删除成功'));
            return;
        }
        echo json_encode(array('statusCode' => -1, 'statusMsg' => '删除失败'));
        return;
    }


    private function get_group_id($hx_group_id)
    {
        $chatGroup = $this->ChatGroup->find('first', array(
            'conditions' => array(
                'hx_group_id' => $hx_group_id
            )
        ));
        return $chatGroup['ChatGroup']['id'];
    }

    private function get_hx_group_id($group_id)
    {
        $chatGroup = $this->ChatGroup->find('first', array(
            'conditions' => array(
                'id' => $group_id
            )
        ));
        return $chatGroup['ChatGroup']['hx_group_id'];
    }

}