<?php

namespace App\Lib\D1M;

use Allegro\Http\Request;
use App\Lib\WeChat\WeChatAPIInterface;

class WeChatAPI implements  WeChatAPIInterface
{
	//const D1M_ACCESS_TOKEN_URL = 'http://loccitane.wechat.d1m.cn/api/wechat/access-token/wx85461b6145d00c8a/63eadaa9a4e5e20373c9c8e74af0f3c0';

	const D1M_ACCESS_TOKEN_URL = 'http://loccitane.wechat.d1mgroup.com/api/wechat/access-token/wx4a63496668c5dac4/d85c4a89462526d3d2daadcad6ee6fa5';

	const D1M_AES_KEY = 'aes_key_d1m_2018';

	const WECHAT_CUSTOM_SEND_URL = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=%s';

	const WECHAT_TEMPLATE_SEND_URL = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=%s';

	//const WECHAT_AUTH_URL = 'http://loccitane.wechat.d1m.cn/api/v2/oauth/u/v1/loccitane-balllons';

	const WECHAT_AUTH_URL = 'http://loccitane.wechat.d1mgroup.com/api/v2/oauth/u/v1/loccitane-balllons';

	private $container;

	public function __construct($container)
	{
		$this->container = $container;
	}

	public function getWeChatUserInfo() 
	{
		$request = $this->container->get('http_kernel')->getRequest();
		$authDataStr = $request->query->get('auth_data');
		if ($authDataStr) {
			$authDataStr = $this->decryptUserInfo($authDataStr);
			parse_str($authDataStr, $userinfo);
			return $userinfo;
		}
		return null;
	}	

	public function getAutorizedUrl()
	{
		return self::WECHAT_AUTH_URL;
	}

	public function getAccessToken()
	{
		return $this->getAccessTokenFromD1M();
	}

	public function getAccessTokenFromD1M() 
	{
		$response = file_get_contents(self::D1M_ACCESS_TOKEN_URL);
		$data = json_decode($response);
		$log = [
			'vendor' => 'D1M',
			'type' => 'access_token',
			'url' => self::D1M_ACCESS_TOKEN_URL,
			'response_data' => $response,
			'created' => '',
		];
		if ($data && $data->success == true && $data->data) {
			$log['status'] = 'success';
			$this->container->get('watchdog')->save($log);
			return $data->data;
		} else {
			$log['status'] = 'failed';
			$this->container->get('watchdog')->save($log);
			return false;
		}
	}

	public function decryptUserInfo($value)
	{
		return Security::decrypt($value, self::D1M_AES_KEY);
	}

	public function serviceTextMessageSend($openid, $text)
	{
		$postData = [
			'touser' => $openid,
			'msgtype' => 'text',
			'text' => ['content' => $text],
		];
		$postJson = json_encode($postData, JSON_UNESCAPED_UNICODE);
		$url = sprintf(self::WECHAT_CUSTOM_SEND_URL, $this->getAccessToken());
		$res = $this->postData($url, $postJson);
		$log = [
			'vendor' => 'WeChat',
			'type' => 'custom_service',
			'url' => self::WECHAT_CUSTOM_SEND_URL,
			'request_data' => $postJson,
			'response_data' => json_encode($res),
			'created' => '',
		];
		if ($res->errcode == 0) {
			$log['status'] = 'success';
		} else {
			$log['status'] = 'failed';
		}
		$this->container->get('watchdog')->save($log);
	}

	public function sendTemplateMsg($postData)
	{
		$postJson = json_encode($postData, JSON_UNESCAPED_UNICODE);
		$url = sprintf(self::WECHAT_TEMPLATE_SEND_URL, $this->getAccessToken());
		$res = $this->postData($url, $postJson);
		$log = [
			'vendor' => 'WeChat',
			'type' => 'template_message',
			'url' => self::WECHAT_TEMPLATE_SEND_URL,
			'request_data' => $postJson,
			'response_data' => json_encode($res),
			'created' => '',
		];
		if ($res->errcode == 0) {
			$log['status'] = 'success';
		} else {
			$log['status'] = 'failed';
		}
		$this->container->get('watchdog')->save($log);
	}

	private function postData($url, $postJson) 
	{
		// post data to wechat
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json; charset=utf-8"));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postJson);
		$data = curl_exec($ch);
		curl_close($ch);
		return json_decode($data);
	}	

}






