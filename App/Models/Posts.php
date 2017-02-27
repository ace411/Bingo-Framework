<?php

namespace App\Models;

use \Core\Model;

class Posts 
{
	public function getPosts()
	{
		$connect = Model::connectTo();
		$connect->sqlQuery("
			SELECT * 
			FROM blog
		");
		$connect->executeQuery();
		$posts = $connect->resultSet();
		$posts = $connect->printJson($posts);
		return $posts;
	}
}