<?php

/**
 *
 * Router class controls the actions to be taken and views to be rendered
 * Matches routes to controllers and actions
 *
 * @package Bingo Framework
 * @author Lochemem Bruno Michael
 *
 */

namespace Core;

class Router
{
    use Injector;

	/**
	 *
	 * Routes for routing table
	 * Route is the key and the parameters are the controller and respective views
	 *
	 * @access protected
	 * @var array $routes
	 *
	 */

	protected $routes = [];

	/**
	 *
	 * Controller and view properties as defined by the user
	 *
	 * @access protected
	 * @var array $params
	 *
	 */

	protected $params = [];

	/**
 	 *
 	 * Default controller namespace
 	 *
 	 * @access protected
 	 * @var string $namespace
 	 *
	 */

	protected $namespace = 'App\Controllers\\';

    /**
     * Injector class constructor
     *
     * @access public
     *
     */

	public function addRoute($route, $params = [])
	{
		//replace all the forward slashes
		$route = preg_replace('/\//', '\\/', $route);

		//convert controller and router variables
		$route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);

		$route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);

		$route = '/^' . $route . '$/i';

		$this->routes[$route] = $params;
	}

	/**
	 *
	 * Get all the routes from the routing table
	 *
	 * @return array $routes
	 *
	 */

	public function getRoute()
	{
		return $this->routes;
	}

	/**
	 *
	 * Match the route to the specified URL
	 *
	 * @param string $url URL to be matched to a controller-method
	 *
	 * @return bool True if route exists and False if it does not
	 *
	 */

	public function matchRoute($url)
	{
		foreach ($this->routes as $route => $params) {
			if (preg_match($route, $url, $matches)) {
				foreach ($matches as $key => $match) {
					if (is_string($key)) {
						$params[$key] = $match;
					}
				}
				$this->params = $params;
				return true;
			}
		}
		return false;
	}

	/**
	 *
	 * Dispatch the routes: instantiate the objects and call the methods
	 * controller: class (object is created from class)
	 * method: action (callable from within class)
	 *
	 * @param string $url
	 *
	 * @return void
	 *
	 */

	public function dispatch($url)
	{
		$url = $this->removeQueryString($url);
		if ($this->matchRoute($url)) {
			//handle the controller
			$controller = $this->params['controller'];
			$controller = $this->convertToStudlyCaps($controller);
			$controller = $this->addNamespace() . "{$controller}";
			if (class_exists($controller)) {
                if (isset($this->container)) {
                    $this->params['container'] = $this->container;
                }
                //instantiate the object of the class
                $controller_object = new $controller($this->params);
                //handle the action
				$action = $this->params['action'];
				$action = $this->convertToCamelCase($action);
				if (is_callable([$controller_object, $action])) {
					$method = $controller_object->$action();
				} else {
					throw new \Exception("{$method} not found in {$controller}");
				}
			} else {
				throw new \Exception("{$controller} not found");
			}
		} else {
			throw new \Exception("Page content not found", 404);
		}
	}

	/**
	 *
	 * Convert dispatched controller names to studly-caps
	 * StudlyCaps: PSR-1 standard for naming classes
	 *
	 * @link http://www.php-fig.org/psr/psr-1/
	 *
	 * @access protected
	 *
	 * @param string $controller
	 *
	 * @return string StudlyCaps($controller)
	 *
	 */

	protected function convertToStudlyCaps($controller)
	{
		return str_replace(' ', '', ucwords(str_replace('-', ' ', $controller)));
	}

	/**
	 *
	 * Convert dispatched method names to camel-case
	 * camelCase: PSR-1 standard for naming methods
	 *
	 * @link http://www.php-fig.org/psr/psr-1/
	 *
	 * @access protected
	 *
	 * @param string $method
	 *
	 * @return string camelCase($method)
	 *
	*/

	protected function convertToCamelCase($method)
	{
		return lcfirst($this->convertToStudlyCaps($method));
	}

	/**
	 *
	 * Add a namespace to ease directory navigation
	 *
	 * @return string $namespace
	 *
	 */

	public function addNamespace()
	{
		if (array_key_exists('namespace', $this->params)) {
			$this->namespace .= $this->params['namespace'] . '\\';
		}

		return $this->namespace;
	}

	/**
	 *
	 * Remove the query string values from the url
	 *
	 * @param string $url URL to be changed
	 *
	 * @return string $url Query-string free URL
	 *
	 */

	public function removeQueryString($url)
	{
		if (isset($url) || $url !== ' ') {
			$components = explode('&', $url);
			if (!preg_match('/=+/', $components[0])) {
				$url = $components[0];
			} else {
				$url = ' ';
			}
		}
		return $url;
	}

	/**
	 *
	 * Get all the parameters of the match: controllers and actions
	 *
	 * @return array $params
	 *
	 */

	public function getParams()
	{
		return $this->params;
	}
}
