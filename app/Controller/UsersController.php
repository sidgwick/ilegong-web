<?php

/**
 * Users Controller
 *
 * PHP version 5
 *
 * @category Controller
 * @package  SaeCMS
 * @version  1.0
 * @author   Arlon <saecms@google.com>

 * @link     http://www.saecms.net
 */
class UsersController extends AppController {
    const WX_BIND_REDI_PREFIX = 'redirect_url_';

    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    var $name = 'Users';
    var $components = array(
    	'Auth',
        'Email',
        'Kcaptcha',
    	'Cookie' => array('name' => 'SAECMS', 'time' => '+2 weeks'),
    );
    /**
     * Models used by the Controller
     *
     * @var array
     * @access public
     */
    var $uses = array('User', 'Oauthbinds', 'WxOauth');

    public function beforeFilter(){
    	parent::beforeFilter();

        $this->Auth->authenticate = array('WeinxinOAuth', 'Form');

    	if(!defined('IS_LOCALHOST')){
    		if(defined('UC_APPID')){
    			$this->Auth->authenticate[] = 'UCenter';
    		}
    	}
    	$this->Auth->allowedActions = array('register','login','forgot','captcha','reset', 'wx_login', 'wx_auth', 'wx_menu_point', 'login_state');
    }

    function captcha() {
        error_reporting(0);
        if (0) {
            //Kcaptcha
            $this->Kcaptcha->render();
        }
//		else
//		{
//			//Securimage
//			$this->autoRender = false;
//	        //override variables set in the component - look in component for full list
////	        $this->captcha->image_height = 75;
////	        $this->captcha->image_width = 350;
////	        $this->captcha->image_bg_color = '#ffffff';
////	        $this->captcha->line_color = '#cccccc';
////	        $this->captcha->arc_line_colors = '#999999,#cccccc';
////	        $this->captcha->code_length = 5;
////	        $this->captcha->font_size = 45;
//	        $this->captcha->text_color = '#000000';
//	        $this->captcha->show(); //dynamically creates an image
//
//
//		}
    }

    public function account() {
        $this->pageTitle = __('Users', true);
    }

    function index() {
        $this->pageTitle = __('Users', true);
        $this->layout = 'user_portlet';
    }

    function goTage(){
    	$code = authcode($this->currentUser['id'].','.$this->currentUser['username'],'ENCODE');
    	$this->set('code',rawurlencode($code));
    	$this->layout = false;
    }

    function layout() {
        $this->pageTitle = __('Users', true);
        $this->layout = 'user_portlet';
    }

    function checkusername() {
        print_r($this->data);
//        $user = $this->User->findByUsername($username);
        exit;
    }

    function register() {
        $this->pageTitle = lang('user_register');
        if (!empty($this->data)) {
        	$this->User->create();
            $this->data['User']['role_id'] = Configure::read('User.defaultroler'); // Registered defaultroler
            $this->data['User']['activation_key'] = md5(uniqid());
            $useractivate = Configure::read('User.activate');
            if ($useractivate == 'activate') {
                $this->data['User']['status'] = 1;
            } else {
                $this->data['User']['status'] = 0;
            }

            /*对密码加密*/
		    $src_password = $this->data['User']['password'];

            $this->data['User']['username'] = trim($this->data['User']['username']);

            if (mb_strlen($this->data['User']['username'], 'UTF-8') < 4) {
                $this->Session->setFlash('用户名长度不能小于4个字符');
            } else if ($this->data['User']['password'] != $this->data['User']['password_confirm']) {
                $this->Session->setFlash('两次密码不相等');
            } else if (is_null($this->data['User']['password']) || trim($this->data['User']['password']) == '') {
                $this->Session->setFlash(__('Password should be longer than 6 characters'));
            } else if ($this->User->hasAny(array('User.username' => $this->data['User']['username']))){
                $this->Session->setFlash(__('Username is taken by others.'));
            } else {
            	$this->data['User']['password'] = Security::hash($this->data['User']['password'], null, true);
            	$this->data['User']['nickname'] = $this->data['User']['username'] ;
            	$has_error = false;
            	if(defined('UC_APPID')){
            		App::import('Vendor', '',array('file' => 'uc_client'.DS.'client.php'));
            		App::uses('Charset', 'Lib');
            		$username =  $this->data['User']['username'];
            		$username = Charset::utf8_gbk($username);
            		$uid = uc_user_register($username,$src_password, $this->data['User']['email'],'','', $this->request->clientIp());
            		if($uid<=0){
            			if($uid == -1) {
            				$error_msg = '用户名不合法';
            			} elseif($uid == -2) {
            				$error_msg = '包含不允许注册的词语';
            			} elseif($uid == -3) {
            				$error_msg = '用户名已经存在';
            			} elseif($uid == -4) {
            				$error_msg = 'Email 格式有误';
            			} elseif($uid == -5) {
            				$error_msg = 'Email 不允许注册';
            			} elseif($uid == -6) {
            				$error_msg = '该 Email 已经被注册';
            			} else{
            				$error_msg = '未知错误';
            			}
            			$has_error = true;
            		}
            		else if($uid>0){
            			$this->data['User']['uc_id'] = $uid;
            		}
            	}
                if ($has_error==false && $this->User->save($this->data)) {
//	                $this->autoRender = false;
                	$this->data['User']['id'] = $this->User->getLastInsertID();
                    if ($useractivate == 'email') {
                        $this->Email->from = Configure::read('Site.title') . ' '
                                . '<SaeCMS@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME'])) . '>';
                        $this->Email->to = $this->data['User']['email'];
                        $this->Email->subject = __('[' . Configure::read('Site.title') . '] Please activate your account', true);
                        $this->Email->template = 'register';

                        $this->data['User']['password'] = null;
                        $this->set('user', $this->data);
                        $this->Email->send();

                        $this->Session->setFlash(__('Please receive email and activate your account.', true));
                    } elseif ($useractivate == 'hand') {
                        $this->Session->setFlash(__('Please wait administrator to activate your account.', true));
                    }
					else{
						$this->Session->setFlash('注册成功!');
					}
                    $data = $this->User->find('first', array('conditions' => array('username' =>  $this->data['User']['username']) ));
                    $this->Session->write('Auth.User', $data['User']);
	                $this->redirect('/');
                } else {
                    $this->Session->setFlash('注册失败，用户名或已存在');
                }
            }
        } else {
            $this->Session->delete('Message.flash');
        }

        // 加载选项，默认值等
        //$this->__loadFormValues('User');
    }

    function activate($username = null, $key = null) {

        $this->pageTitle = __('激活');

        if ($username == null || $key == null) {
            $user = $this->Auth->user();
            if (!empty($user['User']) && $user['User']['status'] == 0) {
                //'您的用户没有激活';
                //exit;
            } else {
                $this->redirect(array('action' => 'login'));
            }
        } else {
            if ($this->User->hasAny(array(
                        'User.username' => $username,
                        'User.activation_key' => $key, 'User.status' => 0,)
            )) {
                $user = $this->User->findByUsername($username);
                $this->User->id = $user['User']['id'];
                $this->User->saveField('status', 1);
                $activation_key = md5(uniqid());
                $this->User->saveField('activation_key', $activation_key);

                // 更新cookie与session
                $user['User']['status'] = 1;
                $user['User']['activation_key'] = $activation_key;
                $this->Session->write('Auth.User', $user['User']);
                $this->Cookie->write('User', $user['User']);
            } else {
                //exit;
                $this->__message('激活链接已失效，请重新获取。', array('action' => 'activate'));
                //$this->redirect(array('action' => 'activate'));
            }
        }
    }

    function edit($id=null) {

        $userinfo = $this->Auth->user();
        if ($userinfo['id']) {
            $datainfo = $this->{$this->modelClass}->find('first', array('recursive' => -1, 'conditions' => array('id' => $userinfo['id'])));
            if (empty($datainfo)) {
                throw new ForbiddenException(__('You cannot edit this data'));
            }

            if (!empty($this->data)) {
                if ($this->{$this->modelClass}->save($this->data)) {
                    $this->Session->setFlash(__('The Data has been saved', true));
                    //$this->redirect(array('action'=>'index'));
                } else {
                    $this->Session->setFlash(__('The Data could not be saved. Please, try again.', true));
                }
                $successinfo = array('success' => __('Edit success', true), 'actions' => array('OK' => 'closedialog'));
                //echo json_encode($successinfo);
                //return ;
            }
            if (empty($this->data)) {
                $this->data = $datainfo;
            }
        } else {
            $this->Session->setFlash(__('You are not authorized to access that location.', true));
            $this->redirect(array('action' => 'login'));
        }
    }

    function my_profile() {
        $userinfo = $this->Auth->user();
        if ($userinfo['id']) {
            $datainfo = $this->{$this->modelClass}->find('first', array('recursive' => -1, 'conditions' => array('id' => $userinfo['id'])));
            if (empty($datainfo)) {
                throw new ForbiddenException(__('You cannot view this data'));
            }
            $this->set('profile', $datainfo['User']);
        } else {
            $this->Session->setFlash(__('You are not authorized to access that location.', true));
            $this->redirect(array('action' => 'login'));
        }
        $this->pageTitle = __('个人信息');
    }


    function editusername() {
    	$userinfo = $this->Auth->user();
    	if (!$userinfo['id']) {
    		$this->Session->setFlash(__('You are not authorized to access that location.', true));
    		$this->redirect(array('action' => 'login'));
    	}

        $this->data['User']['username'] = trim($this->data['User']['username']);
    	if (!empty($this->data) && !empty($this->data['User']['username']) && !empty($this->data['User']['password'])) {

            if ($this->User->hasAny(array('User.username' => $this->data['User']['username']))){
                $this->Session->setFlash(__('Username is taken by others.'));
            }

            $user = array();
            $user['User']['id'] = $userinfo['id'];
            $user['User']['username'] = $this->data['User']['username'];
            $user['User']['password'] = Security::hash(trim($this->data['User']['password']), null, true);
            $user['User']['activation_key'] = md5(uniqid());

            if ($this->User->save($user['User'])) {
                $this->Session->setFlash(__('成功设置用户名与密码'));
            }
            else {
                $this->Session->setFlash(__('设置用户名与密码失败', true));
            }
    	} else {
    		$this->Session->delete('Message.flash');
    	}
    }

    function edit_nick_name() {
        $userinfo = $this->Auth->user();
        if (!$userinfo['id']) {
            $err = __('You are not authorized to access that location.', true);
        }  else {
            $newNickname = htmlspecialchars(trim($_POST['nickname']));
            if (mb_strlen($newNickname) < PROFILE_NICK_MIN_LEN) {
                $err = '昵称至少需要' . PROFILE_NICK_MIN_LEN . '个字符！';
            } else if ($this->nick_should_edited($newNickname)) {
                $err = '昵称不能使用微信用户XXX!';
            } else if ($this->User->hasAny(array('User.nickname' => "$newNickname"))) {
                $err = __('Username is taken by others.');
            } else if (!$this->User->updateAll(array('User.nickname' => "'$newNickname'"), array('User.id' => $userinfo['id']))) {
                $err = __('系统提交保存时失败，请重试');
            } else {
                $this->Session->write('Auth.User.nickname', $newNickname);
                $err = 'ok';
            }
        }

        echo $err;
        $this->autoRender = false;
        return;
    }


    function editpassword() {
        $userinfo = $this->Auth->user();
        if (!$userinfo['id']) {
            $this->Session->setFlash(__('You are not authorized to access that location.', true));
            $this->redirect(array('action' => 'login'));
        }
        if (!empty($this->data) && isset($this->data['User']['password'])) {
            $before_edit = $this->{$this->modelClass}->read(null, $userinfo['id']);

            if ($before_edit['User']['password'] == Security::hash($this->data['User']['password'], null, true)) {

                if (!empty($this->data['User']['new_password']) && $this->data['User']['new_password'] == $this->data['User']['password_confirm']) {
                    $user = array();
                    $user['User']['id'] = $userinfo['id'];
                    $user['User']['password'] = Security::hash($this->data['User']['new_password'], null, true);
                    $user['User']['activation_key'] = md5(uniqid());

                    if ($this->User->save($user['User'])) {
                        $this->Session->setFlash(__('Password is updated success.', true));
                    }
                } else {
                    $this->Session->setFlash(__('Two password is empty or not equare.', true));
                }
            } else {
                $this->Session->setFlash(__('Your password is not right.', true));
            }
        } else {
            $this->Session->delete('Message.flash');
        }
    }

    function forgot() {
        $this->pageTitle = __('Forgot Password', true);
        if (!empty($this->data) && isset($this->data['User']['username'])) {
            $user = $this->User->findByUsername($this->data['User']['username']);
            if (!isset($user['User']['id'])) {
                $this->redirect(array('action' => 'login'));
            }
            $this->User->id = $user['User']['id'];
            $activationKey = md5(uniqid());
            $this->User->saveField('activation_key', $activationKey);
            $this->set(array('user'=>$user, 'activationKey'=>$activationKey));

            $this->Email->from = Configure::read('Site.title') . ' '
            . '<' . Configure::read('Site.email') . '>';

            //$this->Email->from = Configure::read('Site.title') . ' '
            //        . '<SaeCMS@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME'])) . '>';
            $this->Email->to = $user['User']['email'];
            $this->Email->subject = '[' . Configure::read('Site.title') . '] ' . __('Reset Password', true);
            $this->Email->template = 'forgot_password';
            $this->autoRender = false;

//             $this->Email->viewRender('Dzstyle');
            if ($this->Email->send()) {
                $this->redirect(array('action' => 'login'));
            }
            die();
        }
    }

    function reset($username = null, $key = null) {
        $this->pageTitle = __('Reset Password', true);

        if ($username == null || $key == null) {
            $this->redirect(array('action' => 'login'));
        }

        if ($username == '') {
            $this->Session->setFlash(__('您没有设置用户名，找回密码请联系技术客服。'));
        }

        $user = $this->User->find('first', array(
                    'conditions' => array(
                        'User.username' => $username,
                        'User.activation_key' => $key,
                    ),
                ));
        if (!isset($user['User']['id'])) {
            $this->redirect(array('action' => 'login'));
        }

        if (!empty($this->data) && isset($this->data['User']['password'])) {
            $this->User->id = $user['User']['id'];
            $user['User']['password'] = Security::hash($this->data['User']['password'], null, true);
            $user['User']['activation_key'] = md5(uniqid());
            if ($this->User->save($user['User'])) {
                $this->redirect(array('action' => 'login'));
            }
        }

        $this->set(array('user'=>$user, 'username'=>$username, 'key'=>$key));
    }

    function login_state() {
        $login = true;
        if (!$this->currentUser['id']) {
            if (!$this->Auth->login()) {
                $login = false;
            }
        }
        echo json_encode(array('login'=>$login));
        $this->autoRender = false;
    }

    function login() {

        $this->pageTitle = __('登录');

        $redirect = $this->data['User']['referer'] ? $this->data['User']['referer'] : ($_GET['referer'] ? $_GET['referer'] : $this->Auth->redirect());
        $success = false;

        if(empty($this->data) && $this->request->query['data']){ //get 方式传入时,phonegap
        	$this->data = $this->request->query['data'];
        }

        if ($id = $this->Auth->user('id')) { //已经登录的
            $this->User->id = $id;
            $this->User->updateAll(array(
                'last_login' => "'" . date('Y-m-d H:i:s') . "'",
                'last_ip' => "'". $this->request->clientIp() . "'"
            ), array('id' => $id,));
            $success = true;
        }
        else { // 通过表单登录
            if ($this->Auth->login()) {

                $this->User->id = $this->Auth->user('id');
                $this->User->updateAll(array(
                    'last_login' => "'" . date('Y-m-d H:i:s') . "'",
                    'last_ip' => "'". $this->request->clientIp() ."'"
                ), array('id' => $this->User->id,));

                $this->Session->setFlash('登录成功'.$this->Session->read('Auth.User.session_flash'));
                $success = true;
            }
        }

        if ($success) {
            $this->Hook->call('loginSuccess');

            if ($this->RequestHandler->accepts('json') || $this->RequestHandler->isAjax() || isset($_GET['inajax'])) {
                // ajax 操作
                $user = $this->Auth->user();
                $userinfo = array(
                	'id' => $user['id'],
                	'username' => $user['username'],
                );

                if(!empty($this->data['User']['remember_me'])){
                	$cookietime = 2592000; // 一月内30*24*60*60
                }
                else{
                	$cookietime = 0;
                }
                $this->Cookie->write('Auth.User',$userinfo, true, $cookietime);

                $successinfo = array('success' => '登录成功',
                		'userinfo' => $userinfo,
                		'tasks'=>array(array('dotype'=>'reload')));

                $this->autoRender = false; // 不显示模板

                $content = json_encode($successinfo);
                if($_GET['jsoncallback']){
                	$content = $_GET['jsoncallback'] . '(' . $content . ');';
                }
                $this->response->body($content);
                $this->response->send(); exit;// exit退出时，cookie信息未发出，cookie创建失败。
            } else {
                $this->redirect($redirect);
            }
        } elseif (isset($this->data['User']['username'])) {
            if ($this->RequestHandler->accepts('json') || $this->RequestHandler->isAjax() || isset($_GET['inajax'])) {
                // ajax 操作
                $errorinfo = array('error' => '用户名或密码错误', 'tasks' => array(array('dotype' => 'html', 'selector' => '#login_errorinfo', 'content' => __('username or password not right'))));
                $content = json_encode($errorinfo);
                $this->autoRender = false; // 不显示模板
                if($_GET['jsoncallback']){
                	echo $_GET['jsoncallback'] . '(' . $content . ');';
                }
                else{
                	echo $content;
                }
            }
            else {
            	$this->Session->setFlash(__('username or password not right'));
            }
            //$this->redirect(array('action' => 'forgot'), 401);
        } else {
            if ($this->RequestHandler->accepts('json') || $this->RequestHandler->isAjax() || isset($_GET['inajax'])) {
                // ajax 操作
                $this->set('isajax', true);
            }
        }
        $this->set('supportWeixin', !$this->is_pengyoushuo_com_cn() && $this->is_weixin());
        $this->data['User']['referer'] = $redirect;
        $this->set('referer', $redirect);
    }

    function logout() {

        $this->logoutCurrUser();
        if(defined('UC_APPID')){
            App::import('Vendor', '',array('file' => 'uc_client'.DS.'client.php'));
            if(function_exists('uc_user_synlogout')){
                $synlogout = uc_user_synlogout();
            }
        }
        $this->Session->setFlash(__('Logout Success', true).$synlogout);
        $referer = $this->referer();
        $this->log("Refer:".$referer);
        $this->redirect($referer);
    }

    function view($username) {
        $user = $this->User->findByUsername($username);
        if (!isset($user['User']['id'])) {
            $this->redirect('/');
        }

        $this->pageTitle = $user['User']['name'];
        $this->set('user',$user);
    }

    function bindWxSub() {
        $this->Session->delete('Message.flash');
    }

    function after_bind_relogin() {
        $this->logoutCurrUser();
        $this->redirect('/users/wx_login?referer=/users/bindWxSub');
    }

    /**
     *
     */
    function sinalist() {
        $username = '';
        $user = $this->User->findByUsername($username);
        if (!isset($user['User']['id'])) {
            $this->redirect('/');
        }

        $this->pageTitle = $user['User']['name'];
        $this->set('user',$user);
    }

    function myquestion($type='', $page=1, $count = 30) {
        $this->loadModel('Question');
        $questionlist = $this->Question->find('all',
                        array('conditions' => array(
                                'creator' => $this->currentUser['User']['sina_uid'],
                                'published' => 1,
                            )
                ));
        $this->set('questionlist', $questionlist);
    }

    function myweibo($type='', $page=1, $count = 30) {
        $this->loadModel('Weibo');
        $weibolist = $this->Weibo->find('all',
                        array(
                            'conditions' => array(
                                'Weibo.creator' => $this->currentUser['User']['sina_uid'],
                                'Weibo.published' => 1,
                            ),
                            'fields' => array('Weibo.*', 'Question.*'),
                            'limit' => 20,
                            'joins' => array(
                                array(
                                    'table' => Inflector::tableize('Question'),
                                    'alias' => 'Question',
                                    'type' => 'left',
                                    'conditions' => array('Weibo.data_id=Question.id', 'Weibo.model' => 'Question'),
                                ),
                            )
                ));
        $this->set('weibolist', $weibolist);
        $this->set('action', 'myweibo');
    }

    function wx_menu_point() {
        $redirect = '/';
        if (!empty($_GET['referer_key'])) {
            $redirect = oauth_wx_goto($_GET['referer_key'], WX_HOST);
        }

        if ($_GET['do_wx_auth_if_not_log_in'] && (empty($this->currentUser) || !$this->currentUser['id'])) {
            $this->_goto_wx_oauth($redirect);
        }  else {
            $this->redirect($redirect);
        }
    }

    function wx_login() {

        $ref = '';
        if (!empty($_GET['referer'])) {
            $ref = $_GET['referer'];
        } else if (!empty($_GET['referer_key'])) {
            $ref = oauth_wx_goto($_GET['referer_key'], WX_HOST);
        }

        $this->_goto_wx_oauth($ref);
    }

    function wx_auth() {
        $param_referer = $_GET['referer'];
        $oauth_wx_source = oauth_wx_source();
        if (!empty($_REQUEST['code'])) {
            $rtn = $this->WxOauth->find('all', array(
                'method' => 'get_access_token',
                'code' => $_REQUEST['code']
            ));

            if (!empty($rtn) && $rtn['WxOauth']['errcode'] == 0) {
                $res = $rtn['WxOauth'];
                $access_token = $res['access_token'];
                $openid = $res['openid'];
                $refresh_token = $res['refresh_token'];
                if (!empty($access_token) && !empty($res['openid']) && is_string($access_token) && is_string($openid)) {
                    $oauth = $this->Oauthbinds->find('first', array('conditions' => array('source' => $oauth_wx_source,
                        'oauth_openid' => $res['openid']
                    )));

                    $need_transfer = false;
                    if (empty($oauth)) {
                        $oauth['Oauthbinds']['oauth_openid'] = $openid;
                        $oauth['Oauthbinds']['created'] = date('Y-m-d H:i:s');
                        $oauth['Oauthbinds']['source'] = $oauth_wx_source;
                        $oauth['Oauthbinds']['domain'] = $oauth_wx_source;
                    } else {
                        $old_serviceAccount_binded_uid = $oauth['Oauthbinds']['user_id'];
                    }
                    $oauth['Oauthbinds']['oauth_token'] = $access_token;
                    $oauth['Oauthbinds']['oauth_token_secret'] = empty($refresh_token) ? '' : $refresh_token;
                    $oauth['Oauthbinds']['updated'] = date('Y-m-d H:i:s');

                    $refer_by_state = '';
                    if (!empty($_REQUEST['state'])) {
                        $str = base64_decode($_REQUEST['state']);
                        $this->log("got state(after base64 decode):".$str);
                        if (($idx = strpos($str, self::WX_BIND_REDI_PREFIX)) !== false) {

                            //TODO: handle decrypt risk
                            $old_openid = authcode(substr($str, 0, $idx), 'DECODE');
                            if (!empty($old_openid)) {
                                $r = $this->Oauthbinds->find('first', array('conditions' => array('oauth_openid' => $old_openid, 'source' => 'weixin',)));
                                if (!empty($r) && !empty($r['Oauthbinds']['user_id'])) {

                                    if (isset($old_serviceAccount_binded_uid) && ($old_serviceAccount_binded_uid > 0
                                        && $old_serviceAccount_binded_uid != $r['Oauthbinds']['user_id'])) {
                                        $need_transfer = true;
                                    }

                                    $oauth['Oauthbinds']['user_id'] = $r['Oauthbinds']['user_id'];
                                }
                            }
                            $url = substr($str, $idx + strlen(self::WX_BIND_REDI_PREFIX));
                            //TODO: handle risk for external links!!!
                            if (
                                strpos($url, "http://".WX_HOST."/") === 0 ||
                                strpos($url, 'http://www.pyshuo.com/') === 0 ||
                                strpos($url, 'http://www.pengyoushuo.com.cn/') === 0 ||
                                strpos($url, 'http://www.tongshijia.com/') === 0
                            ) {
                                $refer_by_state = $url;
                            }
                        }
                    }

                    $new_serviceAccount_binded_uid = $oauth['Oauthbinds']['user_id'];
                    if ($new_serviceAccount_binded_uid > 0) {
                    } else {
                        $this->User->save(array(
                            'username' => $oauth['Oauthbinds']['oauth_openid'],
                            'nickname' => '微信用户' . mb_substr($oauth['Oauthbinds']['oauth_openid'], 0, PROFILE_NICK_LEN - 4, 'UTF-8'),
                            'password' => '',
                            'uc_id' => 0
                        ));
                        $oauth['Oauthbinds']['user_id'] = $this->User->getLastInsertID();
                        $new_serviceAccount_binded_uid = $oauth['Oauthbinds']['user_id'];
                    }

                    $this->Oauthbinds->save($oauth['Oauthbinds']);
                    if ($need_transfer && isset($old_serviceAccount_binded_uid) && $old_serviceAccount_binded_uid != $new_serviceAccount_binded_uid) {
                        $this->transferUserInfo($old_serviceAccount_binded_uid, $new_serviceAccount_binded_uid);
                    }

                    $redirectUrl = '/users/login?source=' . $oauth['Oauthbinds']['source'] . '&openid=' . $oauth['Oauthbinds']['oauth_openid'];

                    if(!empty($refer_by_state)) {
                        $this->redirect($redirectUrl . '&referer=' . urlencode($refer_by_state));
                    } else if (!empty($param_referer)) {
                        $this->redirect($redirectUrl . '&referer=' . urlencode($param_referer));
                    }   else {
                        $this->redirect($redirectUrl);
                    }
                }
            } else {
                $this->log("error to get_access_token: code=" . $_REQUEST['code'] . ", return:" . var_export($rtn, true));
                //用户授权了，但是读取过程中失败
            }
        } else {
            //show error msgs
            //用户没有授权
            echo "canot get code:" . $_SERVER['QUERY_STRING'];
            exit;
        }
    }

    /**
     * @param $old_serviceAccount_bind_uid
     * @param $new_serviceAccount_bind_uid
     */
    private function transferUserInfo($old_serviceAccount_bind_uid, $new_serviceAccount_bind_uid) {
        $this->log("Merge WX Account from  $old_serviceAccount_bind_uid to " . $new_serviceAccount_bind_uid);
        //copy orders && address info
        $this->loadModel('Order');
        $this->loadModel('OrderConsignee');

        $orderUpdated = $this->Order->updateAll(array('creator' => $new_serviceAccount_bind_uid), array('creator' => $old_serviceAccount_bind_uid));
        $consigneeUpdated = $this->OrderConsignee->updateAll(array('creator' => $new_serviceAccount_bind_uid), array('creator' => $old_serviceAccount_bind_uid));
        $this->log("Merge WX Account from  $old_serviceAccount_bind_uid to " . $new_serviceAccount_bind_uid.": orderUpdated=".$orderUpdated.", consigneeUpdated=". $consigneeUpdated);
    }

    /**
     * @param $ref
     */
    private function _goto_wx_oauth($ref) {
        $return_uri = 'http://'.WX_HOST.'/users/wx_auth?';
        if (!empty($ref)) {
            $return_uri .= 'referer=' . urlencode($ref);
        }

        $return_uri = urlencode($return_uri);

        $this->redirect('https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . WX_APPID . '&redirect_uri=' . $return_uri . '&response_type=code&scope=snsapi_base&state=0#wechat_redirect');
    }

}

?>