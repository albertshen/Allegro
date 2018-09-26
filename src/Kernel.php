<?php

namespace App;

use Allegro\Kernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{

    public function getCacheDir()
    {
        return $this->getProjectDir().'/var/cache';
    }

    public function getLogDir()
    {
        return $this->getProjectDir().'/var/log';
    }

    public function registerService()
    {
        return [
            \App\Service\DatabaseService::class,
            \App\Service\AppService::class,
        ];
    }

    public function registerEventSubscriber()
    {
        return [
            \App\EventSubscriber\AppSubscriber::class,
        ];
    }

    public function registerEventListener()
    {
        return [
            \App\EventListener\AppListener::class => ['listener.test', 'execute', 9],
        ];
    }

    protected function getConfig()
    {
        $config = require $this->getProjectDir().'/config/config.php';
        return $config;
    }

    protected function getRoutes()
    {
        $routes = require $this->getProjectDir().'/config/routes.php';
        return ['routes' => $routes];
    }
}
