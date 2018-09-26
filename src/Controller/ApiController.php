<?php

namespace App\Controller;

use Allegro\Controller\Controller;
use App\Event\AppEvent;

class ApiController extends Controller
{
	public function callback($a, $b)
	{//$this->get('template')->render('html', array('a'=>$a))
		//echo ($a.$b);exit;
		$redis = $this->container->get('redis');
		//$redis->set('albert', 'shen');
		echo ($redis->get('albert'));exit;
		//$this->get('event_dispatcher')->dispatch('event.subscribe', new AppEvent());
		return $this->dataPrint(['a' => $a]);
	}
}