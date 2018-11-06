<?php

namespace App\EventSubscriber;

use Allegro\EventDispatcher\EventSubscriberInterface;
use Allegro\ServiceContainer\ContainerInterface;
use Allegro\Kernel\Event\RequestEvent;
use Allegro\Http\Request;
use App\Event\MessageEvent;
use Allegro\Kernel\Event\TerminateEvent;

class AppSubscriber implements EventSubscriberInterface
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'kernel.request' => [
                ['authorize', 10],
            ],
            'wechat.message.receieve' => [
                ['reservationStatus', 20],
                ['wechatQRScan', 10],
                ['doReserve', 9]
            ]
        );
    }

    public function authorize(RequestEvent $event)
    {
        $request = new Request();
        $params = $this->container->getParameter('authorized.router');
        if (in_array($request->getRouter(), $params)) {
            if (!$this->container->get('user')->isLogin()) {
                if ($this->container->getParameter('kernel.environment') == 'dev') {
                    $this->container->get('user')->testUserLogin();
                } else {
                    $this->container->get('wechat')->setEntranceUrl($request);
                    $this->container->get('wechat')->authorize();
                }
            }
        }
    }

    public function terminate(TerminateEvent $event)
    {
        $request = $event->getRequest();
        if ($request->getRouter() == '/api/message') {
            $response = $event->getResponse();
            $ret = json_decode($response->getContent());
            if ($ret->status == '200') {
                $postStr = $request->getContent();
                $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOERROR | LIBXML_NOWARNING);
                $postObj = json_decode(json_encode($postObj));
                $event = new MessageEvent($postObj);
                $this->container->get('event_dispatcher')->dispatch('wechat.message.receieve', $event);
            }            
        }
    }

    public function reservationStatus(MessageEvent $event)
    {
        //$event->stopPropagation();
        //$this->container->get('d1m.wechat')->serviceTextMessageSend($message->FromUserName, $text);
    }

    public function wechatQRScan(MessageEvent $event)
    {
        $message = $event->getMessage();
        $openid = $message->FromUserName;
        $reservation = $this->container->get('reservation');

        if ($message->MsgType == 'event') {
            if ($message->Event == 'SCAN' || $message->Event == 'subscribe') {
                // file_put_contents('/vagrant/event.txt', json_encode($message));
                if ($reservation->hasReserved($openid)) {
                    $text = '您已经预约过了<a href="http://mp.loccitane.samesamechina.com/user/invitation">点击</a>';
                } else {
                    $text = "欢迎您来到魔都普罗旺斯，自动回复数字，预约您的场次。\n10月13日，11:00，回复1\n10月13日，13:00，回复2\n10月13日，15:00，回复3\n10月13日，17:00，回复4\n10月13日，19:00，回复5\n10月14日，11:00，回复6\n10月14日，13:00，回复7\n10月14日，15:00，回复8\n10月14日，17:00，回复9\n10月14日，19:00，回复10";
                    $reservation->saveScanORCodeRecord($openid, $message->Event);
                }
                $this->container->get('d1m.wechat')->serviceTextMessageSend($message->FromUserName, $text);
            }            
        }
    }

    public function doReserve(MessageEvent $event)
    {
        $message = $event->getMessage();
        $openid = $message->FromUserName;
        $reservation = $this->container->get('reservation');

        if ($message->MsgType == 'text') {
            file_put_contents('/vagrant/text.txt', json_encode($message));
            $redis = $this->container->get('redis');
            $key = "reservation:lock";
            if ($reservation->hasScanORCode($openid)) {
                if (!$redis->sIsmember($key, $openid)) {
                    $redis->sAdd($key, $openid);
                    if ($reservation->hasReserved($openid)) {
                        $text = '您已经预约过了';
                    } else {
                        if ($reservation->hasQuota($id)) {
                            $id = trim($message->Content);
                            $reservation->doReserve($openid, $id);
                            $text = '成功xxx<a href="http://mp.loccitane.samesamechina.com/user/invitation">点击</a>';
                        } else {
                            $text = '场次已满请选择其他场次';
                        }
                    }
                    $this->container->get('d1m.wechat')->serviceTextMessageSend($message->FromUserName, $text);
                    $redis->sRem($key, $openid); 
                }
            }
        }        
    }
}


