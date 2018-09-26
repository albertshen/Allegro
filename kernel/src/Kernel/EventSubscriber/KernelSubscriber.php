<?php

namespace Allegro\Kernel\EventSubscriber;

use Allegro\EventDispatcher\EventSubscriberInterface;
use Allegro\ServiceContainer\ContainerInterface;
use Allegro\Kernel\Event\KernelEvents;
use Allegro\Kernel\Event\RequestEvent;

class KernelSubscriber implements EventSubscriberInterface
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array(
                array('request', 10),
            ),
        );
    }

    public function request(RequestEvent $event)
    {

    }

}

