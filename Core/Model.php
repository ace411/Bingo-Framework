<?php

/**
 *
 * Core model class for database connection
 * 
 * @package Bingo Framework
 * @author Lochemem Bruno Michael
 *
 */

namespace Core;

use PDO;
use App\Config;

class Model
{
	/**
   	 * Singleton database access class
   	 *
   	 * @access private 
   	 * @var $instance
   	 *
	 */

	private static $instance = null;
	
	/**
	 *
	 * Database user-name
	 *
	 * @access private
	 * @var string $username
	 *
	 */

	private $username;
	
	/**
 	 *
 	 * Database password
 	 *
 	 * @access private
 	 * @var string $password
 	 *
	 */

	private $password;
	
	/**
 	 *
 	 * Database host-name
 	 *
 	 * @access private
 	 * @var string $host
 	 *
	 */

	private $host;

	/**
 	 *
 	 * Name of the database
 	 *
 	 * @access private
 	 * @var string $dbname
 	 *
	 */

	private $dbname;

	private function __construct()
	{      
		$this->username = Config::DB_USER;
		$this->password = Config::DB_PASS;
		$this->host = Config::DB_HOST;
		$this->dbname = Config::DB_NAME;		
	}

	private function establishConnection()
	{
		$options = array(
	        	PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', 
	        	PDO::ATTR_PERSISTENT => true
	    	);

		$this->db = new PDO(
			"mysql:host={$this->host};dbname={$this->dbname};charset=utf8", 
			$this->username, 
			$this->password, 
			$options
		);

		$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
	}

	/**
	 *
	 * Magic method to preempt cloning of model class
	 *
	 * @method __call
	 *
	 */

	public function __clone()
	{
		throw new \Exception('You cannot clone the object.');
	}

	/**
	 *
	 * Initialize the connection to the database
	 *
	 * @return static $instance
	 *
	 */

	public static function connectTo()
	{
		if (self::$instance == null) {
			self::$instance = new Model();
			self::$instance->establishConnection();
		}
		return self::$instance;
	}

	/**
	 *
	 * Prepare an SQL query to be executed
	 *
	 * @param string $query Prepared statement to be executed by SQL DB
	 *
	 * @return void
	 *
	 */

	public function sqlQuery($query)
	{
		$this->stmt = $this->db->prepare($query);
	}

	/**
 	 *
 	 * Bind the parameters specified in the SQL statement
 	 *
 	 * @param string $param
 	 * @param int $value
 	 * @param string $value
 	 * @param bool $value
 	 * @param null $value
 	 * @param null $type
 	 *
 	 * @return void
 	 *
	 */

	public function paramBind($param, $value, $type = null)
	{
		if (is_null($type)) {
			switch (true) {
			    case is_int($value):
			        $type = PDO::PARAM_INT;
			        break;
			    case is_bool($value):
			        $type = PDO::PARAM_BOOL;
			        break;
			    case is_null($value):
			        $type = PDO::PARAM_NULL;
			        break;
			    default:
			        $type = PDO::PARAM_STR;
			}
	    }
	    $this->stmt->bindValue($param, $value, $type);
	}

	/**
	 *
	 * Execute the SQL statement
	 *
	 * @return $this->stmt->execute()
	 *
	 */

	public function executeQuery()
	{
		return $this->stmt->execute();
	}

	/**
	 *
	 * Return an associative, multidimensional array of all the rows based on a particular query
	 *
	 * @return array $this->stmt->fetchAll(PDO::FETCH_ASSOC)
	 *
	 */

	public function resultSet()
	{
		$this->stmt->execute();
		return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 *
	 * Return an associative array of a single result
	 *
	 * @return array $this->stmt->fetch(PDO::FETCH_ASSOC)
	 *
	 */

	public function singleResult()
	{
		$this->stmt->execute();
		return $this->stmt->fetch(PDO::FETCH_ASSOC);
	}

	/**
	 *
	 * Return an associative array of a single result
	 *
	 * @return array $this->stmt->fetch()
	 *
	 */

	public function singleRow()
	{
		$this->stmt->execute();
		return $this->stmt->fetch();
	}

	/**
	 *
	 * Begin a database transaction
	 *
	 * @return $this->db->beginTransaction()
	 *
	 */
	
	public function beginTransaction()
	{    
	    return $this->db->beginTransaction();
	}

	/**
	 *
	 * Validate the database transaction
	 *
	 * @return bool $this->db->inTransaction()
	 *
	 */

	public function verifyTransaction()
	{
		return $this->db->inTransaction();
	}

	/**
	 *
	 * End a database transaction and commit changes made
	 *
	 * @return $this->db->endTransaction()
	 *
	 */

	public function endTransaction()
	{	    
	    return $this->db->commit();
	}

	/**
	 *
	 * Cancel a database transaction and rollback to a save-point
	 *
	 * @return $this->db->rollBack()
	 *
	 */

	public function cancelTransaction()
	{	    
	    return $this->db->rollBack();
	}

	/**
	 *
	 * Dumps the information contained in a prepared statement
	 * @link http://php.net/manual/en/pdostatement.debugdumpparams.php
	 *
	 * @return $this->stmt->debugDumpParams()
	 *
	 */

	public function debugDumpParams()
	{	    
	    return $this->stmt->debugDumpParams();
	}

	/**
	 *
	 * Return the total number of rows as defined in a query
	 *
	 * @return int $this->stmt->rowCount()
	 *
	 */

	public function totalRows()
	{
		return $this->stmt->rowCount();
	}

	/**
	 *
	 * Return an array of values from a single column in an SQL database
	 *
	 * @return array $this->stmt->fetchColumn()
	 *
	 */

	public function singleColumn()
	{
		return $this->stmt->fetchColumn();
	}

	/**
	 *
	 * Print information in an array as JSON
	 *
	 * @param array $data
	 *
	 * @return json_encode($data)
	 *
	 */

	public function printJson($data){
		if (!is_array($data)) {
			throw new \Exception("{$data} is not an array");
		} else {
			return json_encode($data);
		}
	}
}