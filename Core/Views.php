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

		if (is_readable($file)) {
			require $file;
		} else {
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
		if ($dir === null) {
			$path = $view->createPath('App/Views/');
		} else {
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

	public function setPath($scheme)
    {
        if (!is_bool($scheme)) {
            throw new \Exception("{$http}: value does not exist");
        }

        $hostPath = $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'];

        if ($scheme === true) {
            return 'http://'. $hostPath;
        } else {
            return 'https://' . $hostPath;
        }
    }

	/**
	 *
	 * get the URL's for the client-side dependencies
	 *
	 * @param bool $scheme
	 * @param string $path
	 *
	 * @return string $url
	 *
	 */

	public static function returnURL($scheme, $path)
	{
        $views = new Views;
        switch ($path) {
            case 'font':
                $url = $views->setPath($scheme) . '/fonts/';
                break;

            case 'style':
                $url = $views->setPath($scheme) . '/styles/';
                break;

            case 'img':
                $url = $views->setPath($scheme) . '/img/';
                break;

            case 'js':
                $url = $views->setPath($scheme) . '/js/';
                break;

            default:
                throw new \Exception("{$path} does not exist");
                break;
        }
        return $url;
	}

	/**
	 *
	 * Use default view rendering dependencies(.js, .css, .php) for Mustache templates
	 *
	 * @return array  Default client-side dependencies
	 *
	 */

    public function renderMustacheDefaults($scheme, $title = null)
    {
        return [
            'title' => !is_null($title) ? $title : 'Bingo Framework',
            'stylesheet' => $this->setPath($scheme) . '/css/main.css',
            'font' => $this->setPath($scheme) . '/fonts/Ubuntu.css'
        ];
    }

    /**
	 *
	 * Use default view rendering dependencies(.js, .css, .php) for Raw PHP templates
	 *
	 * @return array  Default client-side dependencies
	 *
	 */

    public function renderRawDefaults($scheme, $title = null)
	{
		return array_merge([
            'header' => $this->createPath('App/Views/Raw/base_header.php'),
            'footer' => $this->createPath('App/Views/Raw/base_footer.php')
        ], $this->renderMustacheDefaults($scheme, $title));
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
		switch ($input) {
			case is_string($input):
				if (preg_match('/(?:http|https)?(?:\:\/\/)?(?:www.)?(([A-Za-z0-9-]+\.)*[A-Za-z0-9-]+\.[A-Za-z]+)(?:\/.*)?/im', $input)) {
					$data = filter_var($input, FILTER_SANITIZE_URL);
				} else {
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
		if (!is_array($text)) {
			throw new Exception("Please provide an array of strings");
		} else {
			for ($x=0; $x<=count($text)-1; $x++) {
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
		$options = ['extension' => '.html'];
		$mustache = new \Mustache_Engine([
			'loader' => new \Mustache_Loader_FilesystemLoader(dirname(__DIR__) . '/App/Views/Mustache', $options),
			'cache' => $this->createPath(Config::CACHE_DIR . '/mustache'),
            'escape' => function ($value){
				return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
			}
		]);
		$tmp = $mustache->loadTemplate($template);
		return $tmp->render($values);
	}

    /**
     *
     * Transform markdown into HTML
     *
     * @param string $markdown The markdown to be converted
     * @param array $options An array with arbitrary options for the markdown-to-HTML conversion
     *
     * @return $parser->transform($markdown) The HTML produced from the markdown provided
     *
     */

    public function transformMarkdown($markdown, $options = null)
    {
        $parser = new \Michelf\Markdown;
        $parser->no_markup = false;
        if (!is_null($options) && is_array($options)) {
            $match = function ($key, $array) {
                if (array_key_exists($key, $array)) {
                    return $array[$key];
                }
            };
            if (isset($options['urls']) && is_array($options['urls'])) {
                $parser->predef_urls = $options['urls'];
            }
            $parser->tab_width = $match('tab_width', $options);
            $parser->empty_element_suffix = $match('suffix', $options);
        }
        return $parser->transform($markdown);
    }
}
