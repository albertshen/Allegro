<?php

namespace App\Service;

use Allegro\ServiceContainer\ServiceInterface;
use Allegro\ServiceContainer\ContainerInterface;

class DatabaseService implements ServiceInterface
{
	public function load(ContainerInterface $container)
	{
		$container->register('pdo', \PDO::class)
			->addArgument('%database.dsn%')
			->addArgument('%database.user%')
			->addArgument('%database.pass%')
			->addMethodCall('setAttribute', array(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION))
			->addMethodCall('exec', array('set names utf8'));

		// $container->register('mongodb', \stdClass::class);
		// $container->setParameter('host', '127.0.0.1');
		// $container->register('redis', \Redis::class)
		// 	->addMethodCall('connect', array('%redis.host%'));

		// $container->setParameter('abc', 'albertshen');
		// $container->setParameter('arg', 'nisa');
		// $container->register('test', \App\Test::class)
		// 	->addArgument('%abc%')
		// 	->addArgument('@mongodb')
		// 	->addMethodCall('call', array('%arg%', '@mongodb'));
	}
}