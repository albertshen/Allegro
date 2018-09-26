<?php

namespace Allegro\Controller;

// use Symfony\Component\HttpFoundation\Request;
use Allegro\Http\Request;
use Allegro\ServiceContainer\ContainerInterface;

/**
 * This implementation uses the '_controller' request attribute to determine
 * the controller to execute and uses the request attributes to determine
 * the controller method arguments.
 *
 * @author Albert Shen <albertshen1206@gmail.com>
 */
class ControllerResolver
{

    private $container;

    private $controller;

    private $arguments = array();
    
    public function __construct($container) 
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     *
     * This method looks for a '_controller' request attribute that represents
     * the controller name (a string like ClassName::MethodName).
     */
    public function resolveController(Request $request)
    {
        $routes = $this->container->getParameter('routes');
        $current_router = preg_replace('/\?.*/', '', $request->getRequestUri());

        if(isset($routes[$current_router])) {
            $this->controller = [new $routes[$current_router][0]($this->container, $request), $routes[$current_router][1]];
            return $this;
        }

        foreach($routes as $router => $controller) {
            $pattern = '/^' . preg_replace(array('/\//', '/%/'), array('\/', '(.*)'), $router) . '$/';
            if(preg_match($pattern, $current_router, $matches)) {
                array_shift($matches);
                $this->controller = [new $controller[0]($this->container, $request), $controller[1]];
                $this->arguments = $matches;
                return $this;
            }     
        } 

        throw new \InvalidArgumentException(sprintf('The controller for URI "%s" is not exist', $request->getRequestUri()));

    }

    public function getController()
    {
        return $this->controller;
    }

    public function getArguments()
    {
        return $this->arguments;
    }

}
