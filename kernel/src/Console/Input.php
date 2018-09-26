<?php

namespace Allegro\Console;

use Allegro\Console\InputInterface;

class Input implements InputInterface
{
	private $name;

	private $arguments;

    public function __construct(array $argv = null, InputDefinition $definition = null)
    {
        if (null === $argv) {
            $argv = $_SERVER['argv'];
        }

        // strip the application name
        array_shift($argv);

        if ($argv) {
        	$name = array_shift($argv);
        	$this->name = $name;
        }
        $this->arguments = $argv;
    }

    public function getName()
    {
    	return $this->name;
    }

    public function getArguments()
    {
    	return $this->arguments;
    }
}
