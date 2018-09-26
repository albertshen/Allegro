<?php

namespace Allegro\Kernel\Event;

use Allegro\EventDispatcher\Event;
// use Symfony\Component\HttpFoundation\Request;
use Allegro\Http\Request;
use Allegro\Http\Response;

class ResponseEvent extends Event
{
	private $response;
	
    public function __construct(Request $request, Response $response)
    {
        $this->response = $response;
    }

    public function getResponse()
    {
    	return $this->response;
    }
}