<?php

namespace Allegro\Kernel;

// use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\Response;
use Allegro\Http\Request;
use Allegro\Http\Response;
use Allegro\ServiceContainer\ContainerBuilder;

abstract class Kernel implements KernelInterface, TerminableInterface
{

	private $container;

	private $booted;

    /**
     * {@inheritdoc}
     */
    public function handle(Request $request)
    {
        $this->boot();

        return $this->getHttpKernel()->handle($request);
    }

    public function getHttpKernel()
    {
    	return $this->container->get('http_kernel');
    }

	public function boot()
	{
		$this->initializeServiceContainer();

		$this->initializeEventDispatcher();	

		$this->booted = true;
	}

    /**
     * {@inheritdoc}
     */
    public function getRootDir()
    {
        return realpath(__DIR__.'/../../');

    }

    /**
     * Gets the application root dir (path of the project's composer file).
     *
     * @return string The project root dir
     */
    public function getProjectDir()
    {
        return realpath(__DIR__.'/../../../');
    }

    /**
     * {@inheritdoc}
     */
    public function terminate(Request $request, Response $response)
    {
        if (false === $this->booted) {
            return;
        }

        if ($this->getHttpKernel() instanceof TerminableInterface) {
            $this->getHttpKernel()->terminate($request, $response);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Returns the kernel parameters.
     *
     * @return array An array of kernel parameters
     */
    protected function getKernelParameters()
    {
        return array(
            'kernel.root_dir' => $this->getRootDir(),
            'kernel.project_dir' => $this->getProjectDir(),
            'kernel.cache_dir' => $this->getCacheDir(),
            'kernel.logs_dir' => $this->getLogDir(),
        );
    }

	public function initializeServiceContainer()
	{
		$container = new ContainerBuilder();
        $container->set('container', $container);
        //$container->addParameters($this->getKernelParameters());
        $container->addParameters($this->getConfig());
        $container->addParameters($this->getRoutes());

		$kernel_services = [\Allegro\Kernel\Service\KernelService::class];
		$app_services = $this->registerService();
		$services = array_merge($kernel_services, $app_services);
		foreach ($services as $service_class) {
			$service = new $service_class();
			$service->load($container);
		}
		$this->container = $container;
	}

	public function initializeEventDispatcher()
	{
		$dispatcher = $this->container->get('event_dispatcher');

		$kernel_subscriber = [\Allegro\Kernel\EventSubscriber\KernelSubscriber::class];
		$app_subscriber = $this->registerEventSubscriber();
		$subscribers = array_merge($kernel_subscriber, $app_subscriber);
		foreach ($subscribers as $subscriber_class) {
			$subscriber = new $subscriber_class($this->container);
			$dispatcher->addSubscriber($subscriber);
		}

		$app_listeners = $this->registerEventListener();
		foreach ($app_listeners as $listener_class => $v) {
			$listener = new $listener_class($this->container);
			$dispatcher->addListener($v[0], array($listener, $v[1]), $v[2]);
		}
	}

}






