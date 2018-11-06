<?php

namespace App\Lib\Loccitane;

class Reservation
{
	private $pdo;

	public function __construct($pdo)
	{
		$this->pdo = $pdo;
	}

    public function getReservationList()
    {
        $date = date('Y-m-d');
        $sql = "SELECT `date`, `title`, `used`, `quota`, `id`, `start`, `end` FROM items WHERE `status` = :status ORDER BY date, start ASC";
        $query = $this->pdo->prepare($sql);
        $query->execute([':status' => 1]);
        $data = $query->fetchAll(\PDO::FETCH_ASSOC);
        if($data) {
        	$reservationData = [];
        	$tmpData = [];
        	foreach ($data as $value) {
                $start = $value['date'] . ' ' . $value['start'];
                $end = $value['date'] . ' ' . $value['end'];
                $now = date('Y-m-d H:i:s');
                if($now > $end || $value['quota'] <= $value['used']) {
                    $status =  false;
                } else {
                    $status =  true;
                }
        		$tmpData[$value['date']]['date'] = $value['date'];
        		$tmpData[$value['date']]['time'][] = ['title' => $value['title'], 'has_quota' => $status, 'item_id' => $value['id']];
        	}
        	foreach ($tmpData as $value)
        	{
        		$reservationData[] = $value;
        	}
            return $reservationData;
        }
        return [];    	
    }

    public function hasScanORCode($openid)
    {

    }

    public function saveScanORCodeRecord($openid, $event)
    {

    }

    public function addScanORCodeRecord($openid, $event)
    {

    }

    public function consume($uid, $type)
    {
        $sql = "UPDATE `reservation` SET `consume` = :type, `consume_at` = :consume_at WHERE `uid` = :uid";
        $query = $this->pdo->prepare($sql);
        $consume_at = date('Y-m-d H:i:s');
        return $query->execute([':uid' => $uid, ':type' => $type, ':consume_at' => $consume_at]);
    }

    public function isConsume($uid)
    {
        $sql = "SELECT `id`, `consume` FROM `reservation` WHERE `uid` = :uid";
        $query = $this->pdo->prepare($sql);    
        $query->execute(array(':uid' => $uid));
        $row = $query->fetch(\PDO::FETCH_ASSOC);
        if ($row && $row['consume'] >= 1) {
          return  true;
        }
        return false; 
    }

    public function getReservationByUid($uid)
    {
        $sql = "SELECT `item_id` FROM `reservation` WHERE `uid` = :uid";
        $query = $this->pdo->prepare($sql);    
        $query->execute(array(':uid' => $uid));
        $row = $query->fetch(\PDO::FETCH_ASSOC);
        if ($row) {
          return $this->getReservationByItemId($row['item_id']);
        }
        return NULL;        
    }

    public function getReservationByItemId($item_id)
    {
        $sql = "SELECT `date`, `title`, `used`, `quota`, `id`, `start`, `end` FROM items WHERE `id` = :item_id";
        $query = $this->pdo->prepare($sql);  
        $query->execute([':item_id' => $item_id]);
        $row = $query->fetch(\PDO::FETCH_ASSOC);
        if ($row) {
          return  (Object) $row;
        }
        return NULL;        
    }

	public function hasReserved($uid)
	{
        $sql = "SELECT `uid` FROM `reservation` WHERE `uid` = :uid";
        $query = $this->pdo->prepare($sql);    
        $query->execute(array(':uid' => $uid));
        $row = $query->fetch(\PDO::FETCH_ASSOC);
        if ($row) {
          return  (Object) $row;
        }
        return NULL;        
	}

	public function doReserve($uid, $item_id)
	{
        $sql = "INSERT INTO `reservation` SET `uid` = :uid, `item_id` = :item_id, `created` = :created";
        $created = date('Y-m-d H:i:s');
        $query = $this->pdo->prepare($sql);   
        $res = $query->execute([':uid' => $uid, ':item_id' => $item_id, ':created' => $created]);
        if ($res) {
            return $this->addUsed($item_id);
          //return $this->pdo->lastinsertid();
        }
        return null;		
	}

    public function addUsed($item_id)
    {
        $sql = "UPDATE `items` SET `used` = used + 1 WHERE `id` = :id";
        $query = $this->pdo->prepare($sql);    
        return $query->execute([':id' => $item_id]);
    }

	public function hasQuota($item_id)
	{
        $sql = "SELECT `used`, `quota` FROM items WHERE `id` = :id";
        $query = $this->pdo->prepare($sql);
        $query->execute([':id' => $item_id]);
        $row = $query->fetch(\PDO::FETCH_ASSOC);
        if ($row) {
          if ($row['used'] < $row['quota']) {
            return true;
          }
        }
        return false;		
	}

    public function reservationNormalize($user)
    {
        if ($reservation = $this->getReservationByUid($user->uid)) {
            $data = new \stdClass();
            $data->openid = $user->openid;
            $data->nickname = $user->nickname;
            $data->headimgurl = $user->headimgurl;
            $date = date('m月d日', strtotime($reservation->date));
            $startTime = date('H:i', strtotime($reservation->start));
            $endTime = date('H:i', strtotime($reservation->end));
            $data->time = "{$date} {$startTime} - {$endTime}"; 
            return $data;           
        } 
        return false;

    }

}