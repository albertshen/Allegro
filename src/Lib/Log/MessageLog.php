<?php

namespace App\Lib\Log;

class MessageLog
{
	private $pdo;

	public function __construct($pdo)
	{
		$this->pdo = $pdo;
	}

}