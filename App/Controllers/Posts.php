<?php

namespace App\Controllers;

use App\Models\Posts;
use \Core\Views;

class Posts extends \Core\Controller
{
	public function articlesAction()
	{
		$views = new Views();
		$values = array_merge([
			'firstname' => 'Bingo'
		], $views->renderMustacheDefaults(true, 'Articles | Bingo Framework'));
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

	public function editAction()
	{
		$views = new Views;
        $values = array_merge([
            'firstname' => 'Bingo'
        ], $views->renderMustacheDefaults(true, 'Edit | Bingo Framework'));
        echo $view->mustacheRender('base', $values);
	}

}