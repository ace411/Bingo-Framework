<?php

/**
 * Dependency injector for Bingo Framework
 * Handles dependency injection using Pimple
 *
 * @see http://pimple.sensiolabs.org/
 * @package Bingo Framework
 * @author Lochemem Bruno Michael
 *
 */

namespace Core;

use \Pimple\Container;

trait Injector
{
    /**
     * Container for the dependencies
     *
     * @access protected
     * @var object $container
     *
     */

    protected $container;

    /**
     * Injector trait constructor
     *
     * @access public
     */

    public function __construct()
    {
        $this->container = new Container;
    }

    /**
     * Inject dependencies into the container
     *
     * @param string $class The arbitrary name of the container index
     * @param closure $definition Service definition function
     *
     */

    public function inject($class, $definition)
    {
        $this->container[$class] = $this->container->factory($definition);
    }

    /**
     * Extend the dependency container
     *
     * @param string $class
     * @param closure $definition
     *
     */

    public function extend($class, $definition)
    {
        $this->container[$class] = $this->container->extend($class, $definition);
    }

    /**
     * Get the container object
     * @return object $container The container object
     *
     */

    public function getContainer()
    {
        return $this->container;
    }
}
