<?php

/**
 *
 * Template inheritance
 * Rendering output based on views
 *
 * @package Bingo Framework
 * @author Lochemem Bruno Michael
 *
 */

namespace Core;

use App\Config;
use Core\Vendor;

class Views
{
	/**
	 *
	 * Generate absolute paths to a specified file
	 *
	 * @param string $file
	 *
	 * @return string absolute path to file
	 *
	 */

	public function createPath($file)
	{
		return str_replace(DIRECTORY_SEPARATOR, '/', dirname(__DIR__)) . '/' . $file;
	}

	/**
	 *
	 * @param string $view Absolute path to view file
	 * @param array $args Details to appear in the views
	 *
	 * @return void
	 *
	 */

	public function render($view, $args = [])
	{
		//variables to be displayed in the views
		extract($args, EXTR_SKIP);

		$file = $this->getPath() . $view;

		if(is_readable($file)){
			require $file;
		}else{
			throw new \Exception("{$file} not found");
		}
	}

	/**
	 *
	 * Get the path of the PHP raw templates
	 *
	 * @param string $dir
	 *
	 * @return string $path
	 *
	 */

	public static function getPath($dir = null)
	{
		$view = new Views();
		if($dir === null){
			$path = $view->createPath('App/Views/');
		}else {
			$path = $view->createPath($dir);			
		}
		return $path;
	}

	/**
	 *
	 * get the URL's for the client-side dependencies
	 *
	 * @param bool $http
	 * @param string $path
	 *
	 * @return string $url
	 *
	 */

	public static function returnURL($http, $path)
	{
		if(!is_bool($http)){
			echo "{$http} value does not exist";
		}else {
			if($http === true){
				$http_val = htmlspecialchars('http://');
			}else {
				$http_val = htmlspecialchars('https://');
			}
			switch($path){
				case 'font':
					$url = str_replace(substr(self::getPath(), 0, 15), $http_val . $_SERVER['SERVER_NAME'], self::getPath('public/fonts/'));
					break;

				case 'style':
					$url = str_replace(substr(self::getPath(), 0, 15), $http_val . $_SERVER['SERVER_NAME'], self::getPath('public/styles/'));
					break;

				case 'img':
					$url = str_replace(substr(self::getPath(), 0, 15), $http_val . $_SERVER['SERVER_NAME'], self::getPath('public/img/'));
					break;

				case 'js':
					$url = 	str_replace(substr(self::getPath(), 0, 15), $http_val . $_SERVER['SERVER_NAME'], self::getPath('public/js/'));	
					break;
			}		
			return $url;	
		}		
	}

	/**
	 *
	 * Use default view rendering dependencies (.js, .css, .php)
	 *
	 * @return array  Default client-side dependencies
	 *
	 */

	public static function renderDefaults()
	{
		return [
			'header' => self::sanitize('template/header.php'),
			'footer' => self::sanitize('template/footer.php'),
			'stylesheet' => self::sanitize('http://localhost/template/style.css'),
			'js' => self::sanitize('http://localhost/template/main.js'),
			'font' => self::sanitize('http://localhost/template/font.css')
		];
	}

	/**
 	 *
 	 * Sanitize the values parsed to the template rendered
 	 *
 	 * @param string $input
 	 * @param int $input
 	 *
 	 *
 	 * @return string $data
 	 * @return int $data
 	 *
	 */

	public static function sanitize($input)
	{
		switch($input){
			case is_string($input):
				if(preg_match('/(?:http|https)?(?:\:\/\/)?(?:www.)?(([A-Za-z0-9-]+\.)*[A-Za-z0-9-]+\.[A-Za-z]+)(?:\/.*)?/im', $input)){
					$data = filter_var($input, FILTER_SANITIZE_URL);
				}else {
					$data = htmlspecialchars(filter_var($input, FILTER_SANITIZE_STRING));
				}			
				break;

			case is_int($input):
				$data = filter_var($input, FILTER_VALIDATE_INT);
				echo "Integer";
				break;	
		}
		return $data;
	}

	public function filter($text)
	{
		$sanitized = [];
		if(!is_array($text)){
			throw new Exception("Please provide an array of strings");
		}else {
			for($x=0; $x<=count($text)-1; $x++){
				$sanitized[] = [self::sanitize($text[$x])];
			}
		}
		return $sanitized;
	}

	/**
  	 * 
  	 * Render a Mustache template in the Mustache directory
  	 *
  	 * @param string $template
  	 * @param array $values
  	 *
  	 * @return $tmp->render($template, $values)
  	 *
	 */

	public function mustacheRender($template, $values)
	{
		$vendor = new Vendor();
		$vendor->loadPackage();
		$options = ['extension' => '.html'];
		$mustache = new \Mustache_Engine([
			'loader' => new \Mustache_Loader_FilesystemLoader(dirname(__DIR__) . '/App/Views/Mustache', $options),
			'escape' => function ($value){
				return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
			}
		]);
		$tmp = $mustache->loadTemplate($template);
		return $tmp->render($values);
	}
}