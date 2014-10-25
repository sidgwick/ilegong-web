<?php
App::uses('Model', 'Model');
/**
 * Created by IntelliJ IDEA.
 * User: liuzhr
 * Date: 9/28/14
 * Time: 6:06 PM
 */
class WxOauth extends Model {
    public $useDbConfig = 'WxOauth';
    public $useTable = false;

    private $wx_curl_option_defaults = array(
        CURLOPT_HEADER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30
    );

    const MD_KEY_WX_BASE_ACCESS_TOKEN = "wx_base_access_token";
    public function getUserInfo($openid, $token, $lang = 'zh_CN') {
        return $this->find('all', array(
            'method' => 'get_user_info',
            'token' => $token,
            'openid' => $openid,
            'lang' => $lang
        ));
    }

    public function get_base_access_token() {

        $key = self::MD_KEY_WX_BASE_ACCESS_TOKEN;
        $token_data = Cache::read($key);
        if (empty($token_data) || $token_data['expire'] < time() || empty($token_data['token'])) {
//        40014, 40001, 41001 access_token 有关的错误？
            $rtn = $this->find('all', array('method' => 'get_base_access_token'));
            $token = $rtn['WxOauth']['access_token'];
            if (!empty($rtn) && $token) {
                Cache::write($key, array('token' => $token, 'expire'=> mktime() + 3600));
                return $token;
            }
            return '';
        }  else {
            return $token_data['token'];
        }
    }

    public function get_user_info_by_base_token($openid) {
        if (empty($openid)) {
            return null;
        }
        $accessToken = $this->get_base_access_token();
        if (!empty($accessToken)) {
            $rtn = $this->find('all', array('method' => 'get_user_info_by_base_token', 'base_token' => $accessToken, 'openid' => $openid));
            if (!empty($rtn) && $rtn['errcode'] == 0) {
                return $rtn['WxOauth'];
            } else {
                $errcode = $rtn['errcode'];
                $this->clearBaseTokenCacheIfRequired($errcode);
                return null;
            }
        }
    }

    public function should_retry_for_failed_token($output) {
        return ($this->clearBaseTokenCacheIfRequired($output['errcode']));
    }

    public function get_all_users($next_openid) {
        $accessToken = $this->get_base_access_token();
        if (!empty($accessToken)) {
            $params = array('access_token' => $accessToken);
            if ($next_openid) {
                $params['next_openid'] = $next_openid;
            }
            return $this->do_curl(WX_API_PREFIX . "/cgi-bin/user/get?", $params, true);
        } else return false;
    }

    /**
     * clear Base Token Cache If should.
     * @param $errcode
     * @return failed_by_base_token whether the code is cleared and should
     */
    protected function clearBaseTokenCacheIfRequired($errcode) {
        $failed_by_base_token = false;
        if ($errcode == 40014 || $errcode == 40001 || $errcode == 41001) {
            Cache::delete(self::MD_KEY_WX_BASE_ACCESS_TOKEN);
            $failed_by_base_token = true;
        }
        return $failed_by_base_token;
    }

    /**
     * @param $url
     * @param array $params
     * @param bool $used_base_token_and_check
     * @internal param bool $use_base_token
     * @return mixed
     */
    protected function do_curl($url, $params = array(), $used_base_token_and_check = false) {
        if (strpos($url, '?') !== strlen($url) - 1  && !empty($params)) {
            $url .= '?';
        }
        foreach($params as $key=>$value) {
            $url = "&$key=$value";
        }
        $curl = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => 'POST', // GET POST PUT PATCH DELETE HEAD OPTIONS
            CURLOPT_POSTFIELDS => '',
        );
        curl_setopt_array($curl, ($options + $this->wx_curl_option_defaults));
        $this->log("WXOauth-curl:".$url);
        $time_start = mktime();
        $rtn = curl_exec($curl);
        curl_close($curl);
        $this->log('resp ('.(mktime() - $time_start).'s) result:'. $rtn);

        $res = json_decode($rtn, true);
        if (is_null($res)) {
            $error = json_last_error();
            throw new CakeException($error);
        }

        if ($used_base_token_and_check) {
            if ($this->clearBaseTokenCacheIfRequired($res['errcode'])){
                return $this->do_curl($url, $params, false);
            }
        }

        return $res;
    }
}