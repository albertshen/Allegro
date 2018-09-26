<?php

namespace Allegro\Kernel;

/**
 * The Kernel is the heart of the Allegro system.
 *
 * It manages an environment made of application kernel.
 *
 * @author Albert Shen <albertshen1206@gmail.com>
 */
interface KernelInterface
{
    /**
     * Returns an array of container to register.
     *
     * @return array
     */
    public function registerService();

    /**
     * Returns an array of Event subscriber class.
     *
     * @return array
     */
    public function registerEventSubscriber();

    /**
     * Returns an array of Event listener class.
     *
     * @return array
     */
    public function registerEventListener();

    /**
     * Boots the current kernel.
     */
    public function boot();

    /**
     * Gets the application root dir (path of the project's Kernel class).
     *
     * @return string The Kernel root dir
     */
    public function getRootDir();

    /**
     * Gets the current container.
     *
     * @return ContainerInterface A ContainerInterface instance
     */
    public function getContainer();

    /**
     * Gets the cache directory.
     *
     * @return string The cache directory
     */
    public function getCacheDir();

    /**
     * Gets the log directory.
     *
     * @return string The log directory
     */
    public function getLogDir();

}
