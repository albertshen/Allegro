<?php

namespace Allegro\Kernel\Event;

use Allegro\EventDispatcher\Event;

class ControllerEvent extends Event
{
	private $controller;

	private $arguments;
	
    public function __construct(array $controller, array $arguments)
    {
        $this->controller = $controller;

        $this->arguments = $arguments;
    }

    public function getController()
    {
    	return $this->controller;
    }

    public function getArguments()
    {
    	return $this->arguments;
    }
}