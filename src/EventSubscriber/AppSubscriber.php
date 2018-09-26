<?php

namespace App\EventSubscriber;

use Allegro\EventDispatcher\EventSubscriberInterface;
use Allegro\ServiceContainer\ContainerInterface;
use App\Event\AppEvent;

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
            'event.subscribe' => array(
                array('subscribe', 10),
                array('subscribe2', 9),
            ),
        );
    }

    public function subscribe(AppEvent $event)
    {
        echo 'a';
    }

    public function subscribe2(AppEvent $event)
    {
        echo 'b';
    }
}