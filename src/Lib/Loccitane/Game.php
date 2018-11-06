<?php

namespace App\Lib\Loccitane;

class Game
{
	private $pdo;

	public function __construct($pdo)
	{
		$this->pdo = $pdo;
	}

	public function doGame($uid, $duration)
	{
        $sql = "INSERT INTO `game` SET `uid` = :uid, `duration` = :duration, `created` = :created";
        $created = date('Y-m-d H:i:s');
        $query = $this->pdo->prepare($sql);   
        $res = $query->execute([':uid' => $uid, ':duration' => $duration, ':created' => $created]);
        if ($res) {
          return $this->pdo->lastinsertid();
        }
        return null;		
	}

}