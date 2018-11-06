<?php

namespace App\Event;

use Allegro\EventDispatcher\Event;

class MessageEvent extends Event
{
	private $message;

	public function __construct($message)
	{
		$this->message = $message;
	}

	public function getMessage()
	{
		return $this->message;
	}

}
