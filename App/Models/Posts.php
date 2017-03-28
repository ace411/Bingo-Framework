<?php

/**
 * Sample model
 *
 * @package Bingo Framework
 * @author Lochemem Bruno Michael
 *
 */

use \Core\Model;

namespace App\Models;

class Posts
{
    /**
     * Twitter instance
     *
     * @access protected
     * @var object $twitter
     *
     */

    protected $twitter;

    /**
     * SQL model instance
     *
     * @access protected
     * @var object $twitter
     *
     */

    protected $sql;

    /**
     * Posts Model constructor
     *
     * @param object $twitter The Twitter instance
     * @param object $sql The SQL instance
     *
     */

    public function __construct(Twitter $twitter, $sql)
    {
        $this->twitter = $twitter;
        $this->sql = $sql;
    }

    /**
     * Get posts from a database
     *
     * @return string $posts JSON string
     *
     */

    public function getPosts()
	{
		$this->sql->sqlQuery("
			SELECT *
			FROM blog
		");
		$this->sql->executeQuery();
		$posts = $this->sql->resultSet();
		$posts = $this->sql->printJson($posts);
		return $posts;
	}

    /**
     * Get JSON string from Twitter model
     *
     * @return string $twitter JSON string
     *
     */

    public function getTwitterInfo()
    {
        return $this->twitter->getInfo();
    }
}
