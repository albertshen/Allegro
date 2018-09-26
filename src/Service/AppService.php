<?php

namespace App\Service;

use Allegro\ServiceContainer\ServiceInterface;
use Allegro\ServiceContainer\ContainerInterface;
use App\Coach\TemplateMsgSender;

class AppService implements ServiceInterface
{
	public function load(ContainerInterface $container)
	{
		$container->register('tplmsg.sender', TemplateMsgSender::class)
			->addArgument('@pdo')
			->addArgument('%coach.birthday.tpl%');
	}
}