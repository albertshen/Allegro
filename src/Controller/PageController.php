<?php

namespace App\Controller;

use Allegro\Controller\Controller;

class PageController extends Controller
{
	
	public function landingPage()
	{
		return $this->render('landing_page', array());
	}

	public function userLogout()
	{
		$this->get('user')->logout();
		return $this->statusPrint(200, 'logout success');
	}

	public function userInvitation()
	{
		// if ($this->getParameter('kernel.environment') == 'prod') {
		// 	return $this->statusPrint(200, 'not ready');
		// }
		$user = $this->get('user')->load();
		$reservation = $this->get('reservation')->reservationNormalize($user);
		if ($reservation) {
			if ($this->get('reservation')->isConsume($user->uid)) {
				return $this->render('has_consume', ['data' => $reservation]);
			}
			return $this->render('has_invitation', ['data' => $reservation]);	
		}
		return $this->render('invitation');

	}

	public function checkQuota()
	{
        $sql = "SELECT `date`, `title`, `used`, `quota` FROM items WHERE `status` = :status";
        $query = $this->get('pdo')->prepare($sql);
        $query->execute([':status' => 1]);
        $data = $query->fetchAll(\PDO::FETCH_ASSOC);	

        $sql1 = "SELECT count(*) as count FROM reservation";
        $query1 = $this->get('pdo')->prepare($sql1);
        $query1->execute();
        $data1 = $query1->fetch(\PDO::FETCH_ASSOC);

        echo "Total:" . $data1['count'] . "<br>";
        echo "date time used quota<br>";
        foreach ($data as $item) {
        	echo $item['date'] . ' ' . $item['title'] . ' ' . $item['used'] . ' ' . $item['quota'] . ' ' ."<br>";
        }	
        exit;
		//return $this->render('check_quota.tpl', $data);
	}
}