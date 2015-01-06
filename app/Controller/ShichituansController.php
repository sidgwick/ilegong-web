<?php

/**
 * Created by PhpStorm.
 * User: lpy
 * Date: 2014/10/21
 * Time: 17:59
 */
class ShichituansController extends AppController{
    public $name = 'Shichituans';
    public $helpers = array('Html', 'Form', 'Paginator','Session');
    var $components = array(
        'Email', 'Session', 'Paginator'
    );
    var $uses = array('Shichituan', 'User');
    var $paginate = array(
        'Shichituan' => array(
            'order' => 'Shichituan.shichi_id ASC',
            'limit' => 5,
        )
    );

    public function __construct($request = null, $response = null){
        $this->helpers[] = 'PhpExcel';
        parent::__construct($request, $response);
        $this->pageTitle = __('试吃团');
    }

    private function checkAccess(){ //检验是否登录
        if (empty($this->currentUser['id'])) {
            $this->__message('您需要先登录才能操作', '/users/login');
            //return $this->flash('您需要先登录才能操作','/users/login');
        }
    }

    public function apply(){ //试吃团申请
        $this->checkAccess();
        $result = $this->Shichituan->findByUser_id($this->currentUser['id'],array('Shichituan.shichi_id','Shichituan.period'),'Shichituan.shichi_id DESC');
        if ($result){
            if($result['Shichituan']['period']== get_shichituan_period())
            {
            return $this->redirect(array('action' => 'shichi_view'));
            }
            $this->request->data = $this->Shichituan->read(array('Shichituan.wechat','Shichituan.name','Shichituan.company','Shichituan.telenum','Shichituan.email','Shichituan.comment'), $result['Shichituan']['shichi_id']);
        }
           return;
    }

    /**
     * 试吃团申请
     */
    public function shichituan(){
        $this->autoRender = false;
        $this->layout = 'ajax';
        if ($this->request->is('post')) {
            $this->data['Shichituan']['period'] = get_shichituan_period();
            $this->data['Shichituan']['user_id'] = $this->currentUser['id'];
            $this->data['Shichituan']['comment'] = htmlspecialchars($this->data['Shichituan']['comment']);
            if ($this->Shichituan->save($this->data)) {
                $msg ='我们已经收到亲的试吃报名资料，请加QQ群374179511，具体审核信息将会在试吃群中公告.';
                $tel = $this->data['Shichituan']['telenum'];
                message_send($msg,$tel);
                $successinfo = array('success' => __('谢谢您的申请，我们每月30号统一审核,请您耐心等待.', true));
                echo json_encode($successinfo);
            } else {
                echo json_encode($this->{$this->modelClass}->validationErrors);
            }
        }
    }

    /**
     * 导出试吃团申请表excel
     */
    function shichi_list(){
        $Shichituans = $this->Shichituan->find("all");
        $this->set("Shichituans", $Shichituans);
    }

    public function shichi_check($period = '') {
        if ($this->currentUser['id']){
        if ($this->is_admin($this->currentUser['id'])) {
            $this->Paginator->settings = $this->paginate;
            $shichituans = $this->Paginator->paginate('Shichituan', array('Shichituan.period' => $period));
            $this->set('shichituans', $shichituans);

            $period = $this->Shichituan->find('all', array('fields' => array('Shichituan.period'), 'group' => array('Shichituan.period')));
            $this->set('period', $period);
        } else {
            $this->Session->setFlash("请以管理员身份登陆");
            $this->logoutCurrUser();
            $this->redirect('/users/login');
            exit;


        }
        } else {
            $this->redirect('/users/login');
        }

    }

    /**
     * 审核试吃团，并更新到数据库
     */

    public function shichi_save(){
        $this->autoRender = false;
        $this->layout = 'ajax';
        if ($this->request->is('post')) {
            $id = $_REQUEST['id'];
            $this->log('id' . json_encode($id));
            $count = $_REQUEST['count'];
            $val = $_REQUEST['val'];
            $res = array();
            foreach ($id as $re) {
                $this->Shichituan->id = null;
                //$r = $this->Shichituan->read($re);
                $this->log('r' . json_encode($re));
                $data = array('Shichituan' => array('shichi_id' => $re, 'status' => $val));
                if ($this->Shichituan->save($data)) {
                    $res [$re] = array('success' => __('申请状态修改成功.', true));
                    if($data['Shichituan']['status'] == 1) {
                    $shichi_data = $this->Shichituan->find('first',array('conditions' => array('shichi_id' =>$re),'fields' => array('period','telenum')));
                    $msg = '恭喜亲成为第'.$shichi_data['Shichituan']['period'].'期试吃团员,本月所有新品美食都被亲承包了，请加静团长微信[gjj18510162740]，我将大家拉到5期微信群，今天18:00前有效';
                    $tel = $shichi_data['Shichituan']['telenum'];
//                    $this->log('data'.json_encode($shichi_data));
                    message_send($msg,$tel);
                    }
                } else {
                    $res [$re] = array('error' => $this->{$this->modelClass}->validationErrors);
                }
            }
            echo json_encode($res);

        }
    }

    /**
     * 试吃的信息
     */
    public function shichi_message(){
        if ($this->currentUser['id']) {
            $statusinfo = $this->Shichituan->find('first', array('recursive' => -1, 'conditions' => array('user_id' => $this->currentUser['id'])));
            if (empty($statusinfo)) {
                throw new ForbiddenException(__('You cannot view the apply status'));
            }
            $this->set('Mystatus', $statusinfo['Shichituan']);

        } else {
            $this->Session->setFlash(__('You are not authorized to access that location.', true));
            $this->redirect(array('controller' => 'Users', 'action' => 'login'));
        }

    }

    public function shichi_view(){
        $result = $this->Shichituan->findByUser_id($this->currentUser['id'], array('Shichituan.shichi_id', 'Shichituan.status','Shichituan.pictures','Shichituan.period'), 'Shichituan.shichi_id DESC');
        $shichiId = $result['Shichituan']['shichi_id'];
        $status = $result['Shichituan']['status'];
        $this->set('result', $result);
        $this->request->data = $this->Shichituan->read(null, $shichiId);
        if ($status == 0) {
            $shichimessage = __('申请正在审核中,请耐心等待');
        } else if ($status == 1) {
            $shichimessage = __('申请通过,恭喜您加入我们的试吃团');
        } else {
            $shichimessage = __('很遗憾,本期已满，请下次再申请');
        }
        $this->set('message', $shichimessage);
    }



}