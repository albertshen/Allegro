<?php

namespace Allegro\Console;

use Allegro\Console\InputInterface;
use Allegro\Console\OutputInterface;
use Allegro\Kernel\Exception\InvalidArgumentException;

class Command
{

	private $name;
	private $description;
	private $application;

    public function __construct(string $name = null)
    {
        $this->configure();
    }

    public function configure()
    {

    }

    public function execute(InputInterface $input, OutputInterface $output)
    {

    }

    public function setApplication(Application $application = null)
    {
        $this->application = $application;
    }

    /**
     * Gets the application instance for this command.
     *
     * @return Application An Application instance
     */
    public function getApplication()
    {
        return $this->application;
    }
    
    /**
     * Sets the name of the command.
     *
     * This method can set both the namespace and the name if
     * you separate them by a colon (:)
     *
     *     $command->setName('foo:bar');
     *
     * @param string $name The command name
     *
     * @return $this
     *
     * @throws InvalidArgumentException When the name is invalid
     */
    public function setName($name)
    {
        $this->validateName($name);

        $this->name = $name;

        return $this;
    }

    /**
     * Returns the command name.
     *
     * @return string The command name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the description for the command.
     *
     * @param string $description The description for the command
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Returns the description for the command.
     *
     * @return string The description for the command
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Validates a command name.
     *
     * It must be non-empty and parts can optionally be separated by ":".
     *
     * @throws InvalidArgumentException When the name is invalid
     */
    private function validateName(string $name)
    {
        if (!preg_match('/^[^\:]++(\:[^\:]++)*$/', $name)) {
            throw new InvalidArgumentException(sprintf('Command name "%s" is invalid.', $name));
        }
    }
}
