<?php

namespace Allegro\ServiceContainer;

class ContainerBuilder implements ContainerInterface
{
	private $services = array();

    private $arguments = array();

    private $definitions = array();

    private $parameters = array();

    public function register($id, $class)
    {
        return $this->setDefinition($id, new Definition($class));
    }

    public function setDefinition($id, Definition $definition)
    {
        return $this->definitions[$id] = $definition;
    }


    public function set($id, $service)
    {
    	return $this->services[$id] = $service;
    }

    public function get($id)
    {
    	if (isset($this->services[$id])) {
    		return $this->services[$id];
    	}

    	if (isset($this->definitions[$id])) {
    		$service = $this->createService($this->definitions[$id]);
    		return $this->set($id, $service);
    	}

    	return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getParameter($name)
    {
        $name = (string) $name;

        return $this->parameters[$name];
    }

    /**
     * Sets a service container parameter.
     *
     * @param string $name  The parameter name
     * @param mixed  $value The parameter value
     */
    public function setParameter($name, $value)
    {
        $this->parameters[(string) $name] = $value;
    }

    /**
     * Adds parameters to the service container parameters.
     *
     * @param array $parameters An array of parameters
     */
    public function addParameters(array $parameters)
    {
        foreach ($parameters as $key => $value) {
            $this->setParameter($key, $value);
        }
    }

    private function createService(Definition $definition)
    {
        $arguments = $this->resolveValue($definition->getArguments());

        $r = new \ReflectionClass($definition->getClass());

        $service = null === $r->getConstructor() ? $r->newInstance() : $r->newInstanceArgs($arguments);

        foreach ($definition->getMethodCalls() as $call) {
            $this->callMethod($service, $call);
        }

        return $service;
    }

    private function callMethod($service, $call)
    {
        call_user_func_array(array($service, $call[0]), $this->resolveValue($call[1]));
    }

    public function resolveValue($value)
    {
        if (\is_array($value)) {
            $args = array();
            foreach ($value as $k => $v) {
                $args[$k] = $this->resolveValue($v);
            }

            return $args;
        }

        if (!\is_string($value)) {
            return $value;
        }

        return $this->resolveString($value);
    }

    public function resolveString($value)
    {
        if (preg_match('/^%([^%\s]+)%$/', $value, $match)) {
            return $this->parameters[$match[1]];
        }

        if (preg_match('/^@([^%\s]+)/', $value, $match)) {
            return $this->get($match[1]);
        }
        return $value;

    }

}