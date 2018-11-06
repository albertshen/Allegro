<?php

namespace App\Lib\WeChat;

use Allegro\Http\Request;
use Allegro\Http\Response;

class WeChatAPI
{
	private $container;

	public function __construct($container)
	{
		$this->container = $container;
	}

	public function authorize() 
	{
		$wechatVendor = $this->container->get($this->container->getParameter('wechat.vendor'));
		$response = new Response();
		$response->redirect($wechatVendor->getAutorizedUrl());
	}	
	public function retrieveUserInfo(Request $request)
	{
		$wechatVendor = $this->container->get($this->container->getParameter('wechat.vendor'));
		return $wechatVendor->getWeChatUserInfo($request);
	}

	public function setEntranceUrl($request)
	{
		if ($this->container->getParameter('entrance.storage') == 'COOKIE') {
			setcookie("entrance_url", $request->getUrl(), time() + 3600 * 8, '/', $request->getDomain());
		}
	}

	public function getEntranceUrl()
	{
		if ($this->container->getParameter('entrance.storage') == 'COOKIE') {
			if (isset($_COOKIE['entrance_url']))
				return $_COOKIE['entrance_url'];
			return '/';
		} 
	}
}






