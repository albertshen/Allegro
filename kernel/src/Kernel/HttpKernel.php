<?php

namespace Allegro\Kernel;

// use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\Response;
use Allegro\Http\Request;
use Allegro\Http\Response;
use Allegro\Controller\ControllerResolver;
use Allegro\EventDispatcher\EventDispatcher;
use Allegro\Kernel\Event\KernelEvents;
use Allegro\Kernel\Event\RequestEvent;
use Allegro\Kernel\Event\ResponseEvent;
use Allegro\Kernel\Event\ControllerEvent;
use Allegro\Kernel\Event\TerminateEvent;


class HttpKernel implements TerminableInterface
{
	private $dispatcher;

	private $resolver;

    public function __construct(EventDispatcher $dispatcher, ControllerResolver $resolver)
    {
        $this->dispatcher = $dispatcher;
        $this->resolver = $resolver;
    }

	public function handle(Request $request)
	{
		try{
			return $this->handleRaw($request);
		} catch (\Exception $e) {
			return new Response($e->getMessage());
		}
	}

	public function handleRaw(Request $request)
	{

		//request
		$event = new RequestEvent($request);
		$this->dispatcher->dispatch(KernelEvents::REQUEST, $event);

		//resolve controller
		$resolver = $this->resolver->resolveController($request);
        $controller = $resolver->getController();
        $arguments = $resolver->getArguments();

        $event = new ControllerEvent($controller, $arguments);
		$this->dispatcher->dispatch(KernelEvents::CONTROLLER, $event);

        //call controller     
        $response = \call_user_func_array($controller, $arguments);

		//response
		$event = new ResponseEvent($request, $response);
		$this->dispatcher->dispatch(KernelEvents::RESPONSE, $event);

		return $response;
	}


    /**
     * {@inheritdoc}
     */
    public function terminate(Request $request, Response $response)
    {
        $this->dispatcher->dispatch(KernelEvents::TERMINATE, new TerminateEvent($request, $response));
    }

}






