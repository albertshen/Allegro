<?php

namespace App\Service;

use Allegro\ServiceContainer\ServiceInterface;
use Allegro\ServiceContainer\ContainerInterface;

class DatabaseService implements ServiceInterface
{
	public function load(ContainerInterface $container)
	{
		$container->register('redis', \Redis::class)
			->addMethodCall('connect', array('%redis.host%'));

		$container->setParameter('abc', 'albertshen');
		$container->setParameter('arg', 'nisa');
		$container->register('pdo', \PDO::class)
			->addArgument('%mysql.dsn%')
			->addArgument('%mysql.user%')
			->addArgument('%mysql.pass%')
			->addMethodCall('setAttribute', array(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION))
			->addMethodCall('exec', array('set names utf8'));
	}
}