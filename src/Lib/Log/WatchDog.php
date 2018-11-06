<?php

namespace App\Lib\Log;

use App\Lib\DB\PDOHelper;

class WatchDog
{
	private $pdo;

	private $pdoHelper;

	private $watchdog;

	public function __construct($pdo, $watchdog)
	{
		$this->pdo = $pdo;
		$this->pdoHelper = new PDOHelper($pdo);
		$this->watchdog = $watchdog;
	}

	public function save(array $data)
	{
		$watchdog = $this->watchdog;
		if (isset($watchdog[$data['vendor']][$data['type']])) {
			if(($watchdog[$data['vendor']][$data['type']] == 'all') || ($watchdog[$data['vendor']][$data['type']] == $data['status']))
			return $this->pdoHelper->insertTable('watchdog', $data);
		}
		return '';
	}
}