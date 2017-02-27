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

	const DB_USER = 'root';

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

	const DB_PASS = '';

	/**
	 *
	 * Database name parameter
	 *
	 * @var string DB_NAME
	 * 
	 */

	const DB_NAME = '';
		
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
    
    /**
	 *
	 * Cache directory for
	 *
	 * @var string CACHE_DIR
	 *
	 */
    
    const CACHE_DIR = 'cache';
    
    /**
     *
     * Get all the constants defined
     *
     * @return array $reflectionClass->getConstants()
     *
     */
    
    //const LUIS_APP_ID = '0f7e60ad-4450-4cb1-9a2e-bdc04a31e217';
    
    //const LUIS_SUB_KEY = 'c82c42c839c34e3589fd059e445464c7';
    
    public static function getConstants()
    {
        $reflectionClass = new \ReflectionClass(__CLASS__);
        return $reflectionClass->getconstants();
    }
}
