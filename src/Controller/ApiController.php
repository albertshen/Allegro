<?php

namespace App\Controller;

use Allegro\Controller\Controller;

class ApiController extends Controller
{
	public function reservationInfo()
	{
		$data = $this->get('reservation')->getReservationList();
		return $this->dataPrint($data);
	}

	public function gameSubmit()
	{
		$postStr = $this->getRequest()->getContent();
		$postObj = json_decode($postStr);
		if ($postObj) {
			if (!isset($postObj->duration)) {
				return $this->statusPrint(-1, 'parameters wrong');
			}
			$user = $this->get('user')->isLogin();
			if (!$user) {
				return $this->statusPrint(-2, 'user not login');
			}
			$this->get('game')->doGame($user->uid, $postObj->duration);
			return $this->statusPrint(200, 'success');
		}
		return $this->statusPrint(-1, 'parameters wrong');
	}

	public function reservationSubmit()
	{
		$postStr = $this->getRequest()->getContent();
		$postObj = json_decode($postStr);
		if ($postObj) {
			if (!isset($postObj->item_id)) {
				return $this->statusPrint(-1, 'parameters wrong');
			}
			$user = $this->get('user')->isLogin();
			if (!$user) {
				return $this->statusPrint(-2, 'user not login');
			}
			if ($this->get('reservation')->hasReserved($user->uid)) {
				return $this->statusPrint(210, 'has been reserved');
			}
			if (!$this->get('reservation')->hasQuota($postObj->item_id)) {
				return $this->statusPrint(211, 'no quota');
			}
			if ($this->get('reservation')->doReserve($user->uid, $postObj->item_id)) {
				$user = $this->get('user')->load();
				$reservation = $this->get('reservation')->reservationNormalize($user);
				$this->get('event_dispatcher')->addListener('kernel.terminate', function ($event) use ($reservation) {
					$url = $this->getRequest()->generateUrl('user/invitation', [], true);
	                $template = "恭喜你成功预约\n%s\n欧舒丹普罗旺斯探索之旅。请携带<a href='%s'>邀请函</a>前往。";
	                $content = sprintf($template, $reservation->time, $url);
	                $this->get('d1m.wechat')->serviceTextMessageSend($reservation->openid, $content);
				});
				return $this->dataPrint(['status' => 200, 'data' => ['nickname' => $reservation->nickname, 'headimgurl' => $reservation->headimgurl, 'time' => $reservation->time]]);
				// return $this->statusPrint(200, 'success');
			}
		}
		return $this->statusPrint(-1, 'parameters wrong');
	}

	public function reservationConsume()
	{
		$postStr = $this->getRequest()->getContent();
		$postObj = json_decode($postStr);
		$singleCode = '111';
		$coupleCode = '222';
		if ($postObj) {
			if (!isset($postObj->code)) {
				return $this->statusPrint(-1, 'parameters wrong');
			}
			$user = $this->get('user')->isLogin();
			if (!$user) {
				return $this->statusPrint(-2, 'user not login');
			}
			if (!in_array($postObj->code, [$singleCode, $coupleCode])) {
				return $this->statusPrint(220, 'wrong password');
			}
			$type = ($postObj->code == $singleCode) ? 1 : 2;
			if ($this->get('reservation')->isConsume($user->uid)) {
				return $this->statusPrint(221, 'has been consumed');
			}
			if ($this->get('reservation')->consume($user->uid, $type)) {
				return $this->statusPrint(200, 'success');
			}
		}
		return $this->statusPrint(-1, 'parameters wrong');
	}

	public function message()
	{
		$postStr = $this->getRequest()->getContent();
		$log = [
			'vendor' => 'SameSame',
			'type' => 'wechat_message',
			'url' => '/api/message',
			'request_data' => $postStr,
			'created' => '',
		];
		$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOERROR | LIBXML_NOWARNING);
		if ($postObj) {
			$postObj = json_decode(json_encode($postObj));
			$log['status'] = 'success';
			$this->get('watchdog')->save($log);
			return $this->statusPrint(200, 'success');
		}
		$log['status'] = 'failed';
		$this->get('watchdog')->save($log);
		return $this->statusPrint(-1, 'parameters wrong');
	}
}




