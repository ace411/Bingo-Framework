<?php

/**
 *
 * Vendor class to handle third-party packages installed via Composer
 * @link https://getcomposer.org/doc/04-schema.md
 *
 * @package Bingo Framework
 * @author Lochemem Bruno Michael
 *
 */

namespace Core;

use App\Config;

class Vendor 
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

	protected function generatePath($file)
	{
		return str_replace(DIRECTORY_SEPARATOR, '/', dirname(__DIR__)) . '/' . $file;
	}
    
	/**
	 *
	 * Require the autoload file in the Composer dependency directory
	 *
	 */

	public function loadPackage()
	{
		$autoloader = $this->generatePath(Config::DEP_ROOT . '/autoload.php');

		if (is_readable($autoloader) || is_file($autoloader)) {
			require $autoloader;
		}
	}
}