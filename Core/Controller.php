<?php

/**
 *
 * Controller for Bingo Framework
 * Handles route parameters in the query string
 * Contains action filters
 *
 * @package Bingo Framework
 * @author Lochemem Bruno Michael
 *
 */

namespace Core;

trait Controller
{
	/**
	 *
	 * Route parameters parsed via the Router class
	 *
	 * @access protected
	 * @var array $route_params
	 *
	 */

	protected $route_params = [];

	/**
	 * Dependency container
	 *
	 * @access protected
	 * @var array $container
	 *
	 */

	protected $container;

	/**
	 * Controller constructor
	 *
	 * @param array $params An array of routing options
	 *
	 */

	public function __construct($params)
	{
		$this->route_params = $params;
		$this->container = $params['container'];
	}

	/**
	 * Magic method to make before and after functions callable from other classes
	 *
	 * @method __call
	 *
	 */

	public function __call($name, $args)
	{
		$method = $name . 'Action';

		if (method_exists($this, $method)) {
			if($this->before() !== false){
				call_user_func_array([$this, $method], $args);
				$this->after();
			}
		} else{
			throw new \Exception("View {$method} not found in controller " . get_class($this));
		}
	}

	/**
	 * Action filter before
	 *
	 * @access protected
	 * @return void
	 *
	 */

	protected function before()
	{

	}

	/**
	 * Action filter after
	 *
	 * @access protected
	 * @return void
	 *
	 */

	protected function after()
	{

	}
}
