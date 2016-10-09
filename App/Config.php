<?php

/**
 *
 * Configuration options for the Bingo Framework
 *
 * @package Bingo Framework
 * @author Lochemem Bruno Michael
 *
 */

namespace App;

class Config
{
	/**
	 *
	 * Database user-name parameter
	 *
	 * @var string DB_USER
	 * 
	 */

	const DB_USER = 'bots';

	/**
	 *
	 * Database database host parameter
	 *
	 * @var string DB_HOST
	 * 
	 */

	const DB_HOST = 'localhost';

	/**
	 *
	 * Database user-password parameter
	 *
	 * @var string DB_PASS
	 * 
	 */

	const DB_PASS = 'QW6XALwnpP6ZwNvG';

	/**
	 *
	 * Database name parameter
	 *
	 * @var string DB_NAME
	 * 
	 */

	const DB_NAME = 'dproname_hiddentreasures';
		
	/**
	 *
	 * Show errors or convert them into readable logs
	 * Set to false in production
	 *
	 * @var bool SHOW_ERRORS
	 * 
	 */

	const SHOW_ERRORS = true;

	 /**
	 *
	 * Dependency directory
	 * @see composer.json for the name of the root dependency folder
	 *
	 * @var string DEP_ROOT
	 * 
	 */

	const DEP_ROOT = 'packages';
}