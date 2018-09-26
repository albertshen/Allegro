<?php

namespace Allegro\Console;

use Allegro\Kernel\KernelInterface;
use Allegro\Kernel\Kernel;
use Allegro\Console\InputInterface;
use Allegro\Console\Output;

/**
 * @author Fabien Potencier <fabien@symfony.com>
 */
class Application
{
    private $kernel;

    private $commands = array();

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * Gets the Kernel associated with this Console.
     *
     * @return KernelInterface A KernelInterface instance
     */
    public function getKernel()
    {
        return $this->kernel;
    }

    public function run($input)
    {
        $this->doRun($input);
    }

    /**
     * Runs the current application.
     *
     * @return int 0 if everything went fine, or an error code
     */
    public function doRun(InputInterface $input)
    {
        $this->kernel->boot();

        $this->registerCommands();

        if (!$input->getName()) {
            foreach ($this->commands as $name => $command) {
                echo "\033[32m {$name} \033[0m {$command->getDescription()}\n";
            }
            return;
        }

        if (isset($this->commands[$input->getName()])) {
            $command = $this->commands[$input->getName()];
            $command->setApplication($this);
            $command->execute($input, new Output());
            echo "\n";
            return;
        }

        throw new \InvalidArgumentException(sprintf('The command is not exist'));
    }
    

    private function registerCommands()
    {
        $command_map = $this->createMap($this->kernel->getProjectDir().'/src/Command/');
        foreach ($command_map as $class => $file) {
            $command = new $class();
            $this->commands[$command->getName()] = $command;
        }
    }

    /**
     * {@inheritdoc}
     */
    private function doRunCommand(Command $command, InputInterface $input, OutputInterface $output)
    {

    }

    /**
     * Iterate over all files in the given directory searching for classes.
     *
     * @param \Iterator|string $dir The directory to search in or an iterator
     *
     * @return array A class map array
     */
    private function createMap($dir)
    {
        if (is_string($dir)) {
            $dir = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));
        }
        $map = array();
        foreach ($dir as $file) {
            if (!$file->isFile()) {
                continue;
            }
            $path = $file->getRealPath() ?: $file->getPathname();
            if ('php' !== pathinfo($path, PATHINFO_EXTENSION)) {
                continue;
            }
            $classes = $this->findClasses($path);
            if (\PHP_VERSION_ID >= 70000) {
                // PHP 7 memory manager will not release after token_get_all(), see https://bugs.php.net/70098
                gc_mem_caches();
            }
            foreach ($classes as $class) {
                $map[$class] = $path;
            }
        }
        return $map;
    }
    /**
     * Extract the classes in the given file.
     *
     * @param string $path The file to check
     *
     * @return array The found classes
     */
    private function findClasses($path)
    {
        $contents = file_get_contents($path);
        $tokens = token_get_all($contents);
        $classes = array();
        $namespace = '';
        for ($i = 0; isset($tokens[$i]); ++$i) {
            $token = $tokens[$i];
            if (!isset($token[1])) {
                continue;
            }
            $class = '';
            switch ($token[0]) {
                case T_NAMESPACE:
                    $namespace = '';
                    // If there is a namespace, extract it
                    while (isset($tokens[++$i][1])) {
                        if (in_array($tokens[$i][0], array(T_STRING, T_NS_SEPARATOR))) {
                            $namespace .= $tokens[$i][1];
                        }
                    }
                    $namespace .= '\\';
                    break;
                case T_CLASS:
                case T_INTERFACE:
                case T_TRAIT:
                    // Skip usage of ::class constant
                    $isClassConstant = false;
                    for ($j = $i - 1; $j > 0; --$j) {
                        if (!isset($tokens[$j][1])) {
                            break;
                        }
                        if (T_DOUBLE_COLON === $tokens[$j][0]) {
                            $isClassConstant = true;
                            break;
                        } elseif (!in_array($tokens[$j][0], array(T_WHITESPACE, T_DOC_COMMENT, T_COMMENT))) {
                            break;
                        }
                    }
                    if ($isClassConstant) {
                        break;
                    }
                    // Find the classname
                    while (isset($tokens[++$i][1])) {
                        $t = $tokens[$i];
                        if (T_STRING === $t[0]) {
                            $class .= $t[1];
                        } elseif ('' !== $class && T_WHITESPACE === $t[0]) {
                            break;
                        }
                    }
                    $classes[] = ltrim($namespace.$class, '\\');
                    break;
                default:
                    break;
            }
        }
        return $classes;
    }

}
