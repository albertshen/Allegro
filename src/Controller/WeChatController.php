<?php

namespace App\Controller;

use Allegro\Controller\Controller;

class WeChatController extends Controller
{
	public function oauthCallback()
	{
		$userinfo = $this->get('wechat')->retrieveUserInfo($this->getRequest());
		if ($userinfo) {
			$user = $this->get('user');
			if (!$user->isLogin()) {
				if ($user->load($userinfo['openid'])) {
					$user->updateUser($userinfo);
					$user->loginByOpenid($userinfo['openid']);
				} else {
					if (!$user->register($userinfo)) {
						return $this->statusPrint(-1, 'authorized failed');
					}					
				}
			}
			$this->redirect($this->get('wechat')->getEntranceUrl());		
		}
	}

	public function jssdkShortInternal()
	{
		$debug = $this->getRequest()->query->get('debug') ? true : false;
		header("Content-type: application/json");
		$data = $this->get('internal.wechat')->jssdkShortConfig($debug);
       	return  $this->response->setContent($data);
	}
}




