<?php

namespace Allegro\ServiceContainer;

interface ServiceInterface
{
    
    /**
     * Load a service.
     *
     * @param object $container The service container instance
     */
    public function load(ContainerInterface $container);

}