<?php

namespace Allegro\Kernel\Event;

use Allegro\EventDispatcher\Event;
// use Symfony\Component\HttpFoundation\Request;
use Allegro\Http\Request;
use Allegro\Http\Response;

class TerminateEvent extends Event
{
    private $request;

	private $response;
	
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;

        $this->response = $response;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getResponse()
    {
    	return $this->response;
    }
}