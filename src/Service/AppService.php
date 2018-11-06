<?php

namespace App\Service;

use Allegro\ServiceContainer\ServiceInterface;
use Allegro\ServiceContainer\ContainerInterface;
use App\Template\Template;
use App\Lib\Log\WatchDog;
use App\Lib\Loccitane\Reservation;
use App\Lib\Loccitane\Game;
use App\User\User;

class AppService implements ServiceInterface
{
	public function load(ContainerInterface $container)
	{
		$container->register('template', Template::class);

		$container->register('user', User::class)
			->addArgument('@pdo')
			->addArgument('%user.storage%');

		$container->register('internal.wechat', \App\Lib\WeChat\Internal\WeChatAPI::class)
			->addArgument('@container');

		$container->register('d1m.wechat', \App\Lib\D1M\WeChatAPI::class)
			->addArgument('@container');

		$container->register('wechat', \App\Lib\WeChat\WeChatAPI::class)
			->addArgument('@container');

		$container->register('watchdog', WatchDog::class)
			->addArgument('@pdo')
			->addArgument('%watchdog%');

		$container->register('reservation', Reservation::class)
			->addArgument('@pdo');

		$container->register('game', Game::class)
			->addArgument('@pdo');		
	}
}