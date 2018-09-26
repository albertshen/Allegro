<?php

namespace Allegro\Kernel\Service;

use Allegro\ServiceContainer\ServiceInterface;
use Allegro\ServiceContainer\ContainerInterface;
use Allegro\EventDispatcher\EventDispatcher;
use Allegro\Kernel\HttpKernel;
use Allegro\ServiceContainer\ContainerBuilder;
use Allegro\Controller\ControllerResolver;

class KernelService implements ServiceInterface
{
	public function load(ContainerInterface $container)
	{
		$container->register('event_dispatcher', EventDispatcher::class);

		$container->register('controller_resolver', ControllerResolver::class)
			->addArgument('@container');

		$container->register('http_kernel', HttpKernel::class)
			->addArgument('@event_dispatcher')
			->addArgument('@controller_resolver');
	}
}