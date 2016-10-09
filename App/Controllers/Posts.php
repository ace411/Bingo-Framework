<?php

namespace App\Controllers;

use App\Models\Posts;
use \Core\Views;

class Posts extends \Core\Controller
{
	public function articlesAction()
	{
		$view = new Views();
		$values = [
			'title' => 'Articles | Bingo Framework',
			'stylesheet' => Views::returnURL(true, 'style') . 'main.css',
			'font' => Views::returnURL(true, 'font') . 'Ubuntu.css',
			'firstname' => 'Bingo'
		];
		echo $view->mustacheRender('base', $values);
	}

	public function indexAction()
	{
		$posts = new Posts();
		$data = $posts->getPosts();
		$view = new Views();
		$view->render('Posts/index.php', [
			'title' => 'Posts | Bingo Framework',
			'header' => Views::getPath() . 'base_header.php',
			'footer' => Views::getPath() . 'base_footer.php',
			'stylesheet' => Views::returnURL(true, 'style') . 'main.css',
			'font' => $view->returnURL(true, 'font') . 'Ubuntu.css',
			'data' => $data
		]);	
	}

	public function addNewAction()
	{
		
	}

	public function editAction()
	{
		echo 'Hello from the edit page';
		echo '<pre>';
		var_dump($this->route_params);
		echo '</pre>';
	}

}