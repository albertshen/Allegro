<?php

namespace App\EventListener;

use Allegro\ServiceContainer\ContainerInterface;
use App\Event\AppEvent;

class AppListener
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function execute(AppEvent $event)
    {
        echo 3;
    }

}