<?php
/**
 * 腾讯问问自助问答
 * @author arlonzou
 * @2012-11-2下午3:30:18
 */
define("WEIXIN_TOKEN", "sUrjPDH8xus2d4JT");
define('FROM_WX_SERVICE', 1);
define('FROM_WX_SUB', 2);

class WeixinController extends AppController {

	var $name = 'Weixin';

    var $uses = array('Oauthbinds');
	
	public function beforeFilter(){
		parent::beforeFilter();
		$this->layout = false;
	}
	
	public function msg(){
		if(!empty($_GET["echostr"])){
			$this->valid();
		}
		else{
			$this->responseMsg(FROM_WX_SUB);
		}
		exit;
	}

	public function msg_service(){
		if(!empty($_GET["echostr"])){
			$this->valid();
		}
		else{
			$this->responseMsg(FROM_WX_SERVICE);
		}
		exit;
	}
	
	public function valid()
	{
		$echoStr = $_GET["echostr"];
		if($this->checkSignature()){
			echo $echoStr;
		}else{
			echo "invalid request: echo=$echoStr";
			CakeLog::error("invalid request: echo=$echoStr");
		}
	}

	private function responseMsg($from = '')
	{
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		if (!empty($postStr))
		{
			$ret = xml_to_array($postStr);
			$req = $ret["xml"];

			$me = $req["ToUserName"];
			$user = $req["FromUserName"];
			//$type = $req["MsgType"];

            $this->log($req);

			$input = "";
			if(!empty($req['Event'])){
				if($req['Event']=='subscribe'){ //订阅
                    if ($from == FROM_WX_SERVICE) {
                        $content = array(
                            array('title' => '朋友说是什么？看完你就懂了！', 'description' => '',
                                'picUrl' => 'https://mmbiz.qlogo.cn/mmbiz/qpxHrxLKdR0A6F8hWz04wVpntT9Jiao8XZn7as5FuHch5zFzFnvibjUGYU3J4ibxRyLicytfdd9qDQoqV1ODOp3Rjg/0',
                                'url' => 'http://mp.weixin.qq.com/s?__biz=MjM5MjY5ODAyOA==&mid=201694178&idx=1&sn=8dea494e02c96dc21e51931604771748#rd'),
                            array('title' => '朋友说上真实、纯朴、平凡的商家故事', 'description' => '',
                                'picUrl' => 'https://mmbiz.qlogo.cn/mmbiz/qpxHrxLKdR0A6F8hWz04wVpntT9Jiao8X6T44E6S6eq8j4ZKjneD7QTQSWosTpePqnU37LSATvTp1icyotZ614ibA/0',
                                'url' => 'http://mp.weixin.qq.com/s?__biz=MjM5MjY5ODAyOA==&mid=201694178&idx=2&sn=fea08fff581b496c70448505e6c32ccc#rd'),
                            array('title' => '朋友说优质产品一览表', 'description' => '',
                                'picUrl' => 'https://mmbiz.qlogo.cn/mmbiz/qpxHrxLKdR0A6F8hWz04wVpntT9Jiao8XYT9A69hTUYIomNtyJMbLnMibbSHO3NO5UaEics7OwEo9qLHfqmHas8zQ/0',
                                'url' => 'http://mp.weixin.qq.com/s?__biz=MjM5MjY5ODAyOA==&mid=201694178&idx=3&sn=75c4b8f32c29e1c088c7de4ee2e22719#rd')
                        );

                        $uid = $this->Oauthbinds->findUidByWx(trim($req['FromUserName']));
                        if ($uid) {
                            Cache::write(key_cache_sub($uid), WX_STATUS_SUBSCRIBED);
                        }
                    } else {
                        $content = array(
                            array('title' => '朋友说是什么？看完你就懂了！', 'description' => '',
                                'picUrl' => 'https://mmbiz.qlogo.cn/mmbiz/qpxHrxLKdR0A6F8hWz04wVpntT9Jiao8XZn7as5FuHch5zFzFnvibjUGYU3J4ibxRyLicytfdd9qDQoqV1ODOp3Rjg/0',
                                'url' => 'http://mp.weixin.qq.com/s?__biz=MjM5NzQ3NTkxNA==&mid=203424483&idx=1&sn=e281fc56834fb0c2942f887d2edd8d48#rd'),
                            array('title' => '朋友说上真实、纯朴、平凡的商家故事', 'description' => '',
                                'picUrl' => 'https://mmbiz.qlogo.cn/mmbiz/qpxHrxLKdR0A6F8hWz04wVpntT9Jiao8X6T44E6S6eq8j4ZKjneD7QTQSWosTpePqnU37LSATvTp1icyotZ614ibA/0',
                                'url' => 'http://mp.weixin.qq.com/s?__biz=MjM5NzQ3NTkxNA==&mid=203424483&idx=2&sn=31229814ba277288dbf1ce98ac497115#rd'),
                            array('title' => '朋友说优质产品一览表', 'description' => '',
                                'picUrl' => 'https://mmbiz.qlogo.cn/mmbiz/qpxHrxLKdR0A6F8hWz04wVpntT9Jiao8XYT9A69hTUYIomNtyJMbLnMibbSHO3NO5UaEics7OwEo9qLHfqmHas8zQ/0',
                                'url' => 'http://mp.weixin.qq.com/s?__biz=MjM5NzQ3NTkxNA==&mid=203424483&idx=3&sn=6331c07da8078c579126950bbfa2a71e#rd')
                        );
                    }
					echo $this->newArticleMsg($user, $me, $content);
					exit;
				} else if ($req['Event'] == 'CLICK') {
                    $input = $req['EventKey'];
                } else if ($req['Event'] == 'unsubscribe') {

                    if ($from == FROM_WX_SERVICE) {
                        $uid = $this->Oauthbinds->findUidByWx(trim($req['FromUserName']));
                        if ($uid) {
                            Cache::write(key_cache_sub($uid), WX_STATUS_UNSUBSCRIBED);
                        }
                    }
                }
			}


			$msg = "";


			if( isset($req["Content"])) { //text message
				$input = $req["Content"];
				$msg = $msg.$req["Content"];
			}

			if( isset($req["PicUrl"])) { //image message
				$msg = $msg."*图片消息*";
				$picUrl = $req["PicUrl"];
			}

			if( isset($req["Url"])){ //link message
				$msg = $msg."*超链接信息：{$req['Title']}.({$req['Description']})*";
				$url = $req["Url"];
			}

			if( isset($req["Location_X"])){ //location message
				$msg = $msg."*位置信息,我在{$req["Label"]}({$req["Location_X"]}, {$req["Location_Y"]})*";
			}
			
			if( isset($req["Recognition"])){ //location message
				$msg = $msg."*语音信息：{$req['Recognition']}*";
			}

            $host3g = (WX_HOST);

            $special = $this->getSpecialTitle($from, $input);
            if (!empty($special)) {
                echo $this->newArticleMsg($user, $me, array('url' => $special['url'], 'title' => $special['title'], 'picUrl' => $special['pic'], 'description' => '点击查看详情，获得你的前世吃货身份'));
                return;
            }

			$user_code = urlencode(authcode($user,'ENCODE'));
			//判断输入内容
			switch($input)
			{
				case "下单":
				case "预订":
				case "预定":
				case "1":
                case "１":
                case "CLICK_URL_TECHAN":
					echo $this->newTextMsg($user, $me, '点击进入<a href="'.$this->loginServiceIfNeed($from, $user, oauth_wx_goto('CLICK_URL_TECHAN', $host3g)).'">预定</a>');
					break;
				case "查看订单":
				case "订单":
                case "CLICK_URL_MINE":
				case "2":
					echo $this->newTextMsg($user, $me, '点击进入<a href="'.$this->loginServiceIfNeed($from, $user, oauth_wx_goto('CLICK_URL_MINE', $host3g)).'">我的订单</a>');
					break;
                case "3":
					echo $this->newTextMsg($user, $me, "点击进入<a href=\"http://wx.wsq.qq.com/177650290\" >51daifan微社区</a>");
					break;
                case "CLICK_URL_SALE_AFTER_SAIL":
                    echo $this->newTextMsg($user, $me, '点击进入<a href="'.$this->loginServiceIfNeed($from, $user, oauth_wx_goto('CLICK_URL_SALE_AFTER_SAIL', $host3g)).'">售后服务</a>');
                    break;
                case "CLICK_URL_SHICHITUAN":
                    echo $this->newTextMsg($user, $me, '点击进入<a href="'.$this->loginServiceIfNeed($from, $user, oauth_wx_goto('CLICK_URL_SHICHITUAN', $host3g)).'">试吃评价</a>');
                    break;
                case '6':
                case '６':
                case 'CLICK_GUANZHU_FUWUHAO':
                    if($from == FROM_WX_SERVICE) {
                        echo "您正在使用我们的服务号！谢谢！(::)";
                    } else {
                        echo $this->newTextMsg($user, $me, '安卓系统请点击关注<a href="' . $this->loginServiceIfNeed($from, $user, "weixin://contacts/profile/gh_b860367e62a5")
                            . '">朋友说服务号</a>；苹果系统请点击<a href="'.WX_SERVICE_ID_GOTO.'">查看如何关注</a>。');
                    }
                    break;
                case "CLICK_URL_BINDING":
                    if ($from == FROM_WX_SUB) {
                        list($oauth, $hasAccountWithSubOpenId) = $this->hasAccountWithSubOpenId($user);
                        if (!$hasAccountWithSubOpenId) {
                            echo $this->newTextMsg($user, $me, '您没有历史账号信息');
                        } else if ($this->whetherBinded($oauth['Oauthbinds']['user_id'])) {
                            echo $this->newTextMsg($user, $me, '您的历史账号信息已经合并');
                        } else {
                            echo $this->newTextMsg($user, $me, '您有历史账号信息未绑定，点击<a href="' . $this->loginServiceIfNeed($from, $user, "http://$host3g/users/after_bind_relogin.html?wx_openid=$user_code", true) . '">绑定账号</a>');
                        }
                    }
                    break;
                case "5151":
                case "ordersadmin":
                case "Ordersadmin":
                    echo $this->newTextMsg($user, $me, '点击进入<a href="'.$this->loginServiceIfNeed($from, $user, "http://$host3g/brands/brands_admin?wx_openid=$user_code").'">商家订单管理</a>');
                    break;
                case '5152':
                    echo $this->newTextMsg($user, $me, '点击进入<a href="'.$this->loginServiceIfNeed($from, $user, "http://$host3g/apple_201410/index.html").'">苹果游戏Demo</a>');
                    break;
				//default :
				//	echo $this->newTextMsg($user, $me, "回复“预定”进入预定页\n回复“订单”查看我的订单");
			}
		}
	}

    private function loginServiceIfNeed($from, $subOpenId, $url, $do_bind = false) {
        if ($do_bind) {
            $return_uri = urlencode('http://'.WX_HOST.'/users/wx_auth');
            $state = base64_encode(authcode($subOpenId, 'ENCODE') . "redirect_url_" . $url);
            return 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . WX_APPID . '&redirect_uri=' . $return_uri . '&response_type=code&scope=snsapi_base&state=' . $state . '#wechat_redirect';
        }  else {
            return $url;
        }
    }

	private function newTextMsg($toUser, $sender, $cont){
    	$time = time();
    	return "<xml>"
				."<ToUserName><![CDATA[$toUser]]></ToUserName>"
				."<FromUserName><![CDATA[$sender]]></FromUserName>"
				."<CreateTime>$time</CreateTime>"
				."<MsgType><![CDATA[text]]></MsgType>"
				."<Content><![CDATA[$cont]]></Content>"
				."</xml>";
    }

    // array = title => '', description => '', picUrl => '', url => ''
    private function newArticleMsg($toUser, $sender, $array){
    	$time = time();
    	$len = count($array);
    	$items = "";

    	foreach($array as $it){
    		$items =$items."<item>"
				."<Title><![CDATA[{$it['title']}]]></Title> "
				."<Description><![CDATA[{$it['description']}]]></Description>"
				."<PicUrl><![CDATA[{$it['picUrl']}]]></PicUrl>"
				."<Url><![CDATA[{$it['url']}]]></Url>"
				."</item>";

    	}

    	return "<xml>"
				."<ToUserName><![CDATA[$toUser]]></ToUserName>"
				."<FromUserName><![CDATA[$sender]]></FromUserName>"
				."<CreateTime>$time</CreateTime>"
				."<MsgType><![CDATA[news]]></MsgType>"
				."<ArticleCount>$len</ArticleCount>"
				."<Articles>"
				.$items
				."</Articles>"
				."</xml>";
    }


	private function checkSignature()
	{
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];	
	        		
		$token = WEIXIN_TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}

    /**
     * @param $user_id
     * @return bool  whether is bind (and if user has never been created for the specified openid, return false).
     */
    private function whetherBinded($user_id) {
        if ($user_id) {
            $r = $this->Oauthbinds->find('first', array('conditions' => array('user_id' => $user_id, 'source' => oauth_wx_source(),)));
            if (!empty($r)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $subOpenId
     * @return array :  list($oauth, $hasAccountWithSubOpenId)
     */
    private function hasAccountWithSubOpenId($subOpenId) {
        $oauth = $this->Oauthbinds->find('first', array('conditions' => array('oauth_openid' => $subOpenId, 'source' => 'weixin',)));
        $hasAccountWithSubOpenId = !empty($oauth) && !empty($oauth['Oauthbinds']['user_id']);
        return array($oauth, $hasAccountWithSubOpenId);
    }


    private function getSpecialTitle($type, $val) {
        $elements = array(FROM_WX_SERVICE =>
          array(
                '打' => array('url' => 'http://mp.weixin.qq.com/s?__biz=MjM5NzQ3NTkxNA==&mid=204166400&idx=1&sn=6f250ac6c2e5c677af859317ac2842a5#rd', 'pic' => 'https://mmbiz.qlogo.cn/mmbiz/UuGM2hE8WNGMCRo9uNFuJAluvfc1iaia357phWhKGa8jUUC94zLOEXE4ND3PDMx4sWe0yPY7jdltD6Riasa0SceWA/0', 'title'=>'【你前世的吃货身份：武大郎】'),
                '死' => array('url' => 'http://mp.weixin.qq.com/s?__biz=MjM5NzQ3NTkxNA==&mid=204166086&idx=1&sn=fc8251ab2d5cda2af825895b9e0e228d#rd', 'pic' => 'https://mmbiz.qlogo.cn/mmbiz/UuGM2hE8WNGMCRo9uNFuJAluvfc1iaia35xd3hZfWYK2kEmCRtqZiaUzATl1XVdpKZh9HfE113oJTuqqk0G0y6PVQ/0', 'title'=>'【你前世的吃货身份：武则天】'),
                '都' => array('url' => 'http://mp.weixin.qq.com/s?__biz=MjM5NzQ3NTkxNA==&mid=204165678&idx=1&sn=b7ed9f3d1263675456d7431a912305f4#rd', 'pic' => 'https://mmbiz.qlogo.cn/mmbiz/UuGM2hE8WNGMCRo9uNFuJAluvfc1iaia35bz312xCVICquYMn9UY5K5ibf8d540CQicImR9TxcAic9Kfk12Dj5dYN4Q/0', 'title'=>'【你前世的吃货身份：唐伯虎】'),
                '不' => array('url' => 'http://mp.weixin.qq.com/s?__biz=MjM5NzQ3NTkxNA==&mid=204165330&idx=1&sn=c11bcc24f5083550082c30991f14233d#rd', 'pic' => 'https://mmbiz.qlogo.cn/mmbiz/UuGM2hE8WNGMCRo9uNFuJAluvfc1iaia35kvYSljfzYjhuYicjOAfapMib3eCnDY19CVaIkY3qEpBbjJFvc5EANYicQ/0', 'title'=>'【你前世的吃货身份：如花】'),
                '是' => array('url' => 'http://mp.weixin.qq.com/s?__biz=MjM5NzQ3NTkxNA==&mid=204165084&idx=1&sn=09fb2503326549242af067b269df40b4#rd', 'pic' => 'https://mmbiz.qlogo.cn/mmbiz/UuGM2hE8WNGMCRo9uNFuJAluvfc1iaia357vzbHyBriamXdwsQq7SAXpc5p93cHSk9icEATWzxkyO6nYqSvLp1FdHg/0', 'title'=>'【你前世的吃货身份：包租婆】'),
                '猪' => array('url' => 'http://mp.weixin.qq.com/s?__biz=MjM5NzQ3NTkxNA==&mid=204164747&idx=1&sn=9b9478886ec864dda9e71fe4fc8d1ede#rd', 'pic' => 'https://mmbiz.qlogo.cn/mmbiz/UuGM2hE8WNGMCRo9uNFuJAluvfc1iaia350WwImJ0QEBk45RnjIqq8mqem4KdgicGVaSRWmOY8tJ1PHdGvgZIgFdg/0', 'title'=>'【你前世的吃货身份：猪八戒】'),
                '八' => array('url' => 'http://mp.weixin.qq.com/s?__biz=MjM5NzQ3NTkxNA==&mid=204164184&idx=1&sn=16188fdda3594bdeee968fd84928cb96#rd', 'pic' => 'https://mmbiz.qlogo.cn/mmbiz/UuGM2hE8WNGMCRo9uNFuJAluvfc1iaia35bgswj4J7wCt0amx6s6vpUZLgJX2Nq0NvWfV2xEggFqBEmIE8kBIvdQ/0', 'title'=>'【你前世的吃货身份：林黛玉】'),
                '戒' => array('url' => 'http://mp.weixin.qq.com/s?__biz=MjM5NzQ3NTkxNA==&mid=204162688&idx=1&sn=959c8132480eabd4cdb6bb3a14be649b#rd', 'pic' => 'https://mmbiz.qlogo.cn/mmbiz/UuGM2hE8WNGMCRo9uNFuJAluvfc1iaia35KENKL8pQcdolSp59YgeHfqyt7YWBMfvarXkmHsrz3eCicpI7xZX6qag/0', 'title'=>'【你前世的吃货身份：肥仔】')
          )
        , FROM_WX_SUB =>
      array(
            '打' => array('url' => 'http://mp.weixin.qq.com/s?__biz=MjM5MjY5ODAyOA==&mid=202247008&idx=1&sn=62fc1d3cdc6fc03dc0455edae5e5ccbe#rd', 'pic' => 'https://mmbiz.qlogo.cn/mmbiz/UuGM2hE8WNGMCRo9uNFuJAluvfc1iaia357phWhKGa8jUUC94zLOEXE4ND3PDMx4sWe0yPY7jdltD6Riasa0SceWA/0', 'title' => '【你前世的吃货身份：武大郎】'),
            '死' => array('url' => 'http://mp.weixin.qq.com/s?__biz=MjM5MjY5ODAyOA==&mid=202247055&idx=1&sn=891b24b7d1c57199096cc135290f34f3#rd', 'pic' => 'https://mmbiz.qlogo.cn/mmbiz/UuGM2hE8WNGMCRo9uNFuJAluvfc1iaia35xd3hZfWYK2kEmCRtqZiaUzATl1XVdpKZh9HfE113oJTuqqk0G0y6PVQ/0', 'title' => '【你前世的吃货身份：武则天】'),
            '都' => array('url' => 'http://mp.weixin.qq.com/s?__biz=MjM5MjY5ODAyOA==&mid=202247108&idx=1&sn=538860cfe732e97e7f5472df1720e831#rd', 'pic' => 'https://mmbiz.qlogo.cn/mmbiz/UuGM2hE8WNGMCRo9uNFuJAluvfc1iaia35bz312xCVICquYMn9UY5K5ibf8d540CQicImR9TxcAic9Kfk12Dj5dYN4Q/0', 'title' => '【你前世的吃货身份：唐伯虎】'),
            '不' => array('url' => 'http://mp.weixin.qq.com/s?__biz=MjM5MjY5ODAyOA==&mid=202247151&idx=1&sn=6591069bc0d7618c7840065012431dfe#rd', 'pic' => 'https://mmbiz.qlogo.cn/mmbiz/UuGM2hE8WNGMCRo9uNFuJAluvfc1iaia35kvYSljfzYjhuYicjOAfapMib3eCnDY19CVaIkY3qEpBbjJFvc5EANYicQ/0', 'title' => '【你前世的吃货身份：如花】'),
            '是' => array('url' => 'http://mp.weixin.qq.com/s?__biz=MjM5MjY5ODAyOA==&mid=202247205&idx=1&sn=3a81fab29a02f03b8c06ccf5ca591bbc#rd', 'pic' => 'https://mmbiz.qlogo.cn/mmbiz/UuGM2hE8WNGMCRo9uNFuJAluvfc1iaia357vzbHyBriamXdwsQq7SAXpc5p93cHSk9icEATWzxkyO6nYqSvLp1FdHg/0', 'title' => '【你前世的吃货身份：包租婆】'),
            '猪' => array('url' => 'http://mp.weixin.qq.com/s?__biz=MjM5MjY5ODAyOA==&mid=202247235&idx=1&sn=cb4024d719503c8ba7def775d7a789f4#rd', 'pic' => 'https://mmbiz.qlogo.cn/mmbiz/UuGM2hE8WNGMCRo9uNFuJAluvfc1iaia350WwImJ0QEBk45RnjIqq8mqem4KdgicGVaSRWmOY8tJ1PHdGvgZIgFdg/0', 'title' => '【你前世的吃货身份：猪八戒】'),
            '八' => array('url' => 'http://mp.weixin.qq.com/s?__biz=MjM5MjY5ODAyOA==&mid=202247289&idx=1&sn=140e36756bf287074ec206d90b856921#rd', 'pic' => 'https://mmbiz.qlogo.cn/mmbiz/UuGM2hE8WNGMCRo9uNFuJAluvfc1iaia35bgswj4J7wCt0amx6s6vpUZLgJX2Nq0NvWfV2xEggFqBEmIE8kBIvdQ/0', 'title' => '【你前世的吃货身份：林黛玉】'),
            '戒' => array('url' => 'http://mp.weixin.qq.com/s?__biz=MjM5MjY5ODAyOA==&mid=202247377&idx=1&sn=67296ad1022e3d9afeb61944e4fed480#rd', 'pic' => 'https://mmbiz.qlogo.cn/mmbiz/UuGM2hE8WNGMCRo9uNFuJAluvfc1iaia35KENKL8pQcdolSp59YgeHfqyt7YWBMfvarXkmHsrz3eCicpI7xZX6qag/0', 'title' => '【你前世的吃货身份：肥仔】'),
      )
        );

        return $elements[$type][$val];
    }
}
?>