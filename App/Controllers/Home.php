<?php

/**
 *
 * @package Bingo Framework
 * @author Lochemem Bruno Michael
 *
 * Home Controller
 *
 *
 */
	
namespace App\Controllers;

use \Core\Views;	

class Home extends \Core\Controller
{
	public function indexAction()
	{
		$view = new Views();
		$view->render('Home/index.php', [
			'title' => 'Bingo Framework',
			'short_desc' => 'You might enjoy...',
			'header' => Views::getPath() . 'Raw/base_header.php',
			'footer' => Views::getPath() . 'Raw/base_footer.php',
			'stylesheet' => Views::returnURL(true, 'style') . 'main.css',
			'font' => $view->returnURL(true, 'font') . 'Ubuntu.css',
			'js' => $view->returnURL(true, 'js') . 'controller.js',
			'plans' => ['Reusable templates', 'MVC', 'Design simplicity'],
			'links' => [
				['http://localhost:8080/home/index', 'Home'], 
				['http://localhost:8080/home/about', 'About'], 
				['https://github.com/ace411/Bingo-Framework', 'Documentation'], 
				['https://github.com/ace411/Bingo-Framework', 'GitHub']
			]
		]);
	}

	public function aboutAction()
	{
		$view = new Views;
		$view->render('Home/about.php', [
			'title' => 'Bingo Framework',
			'header' => Views::getPath() . 'Raw/base_header.php',
			'footer' => Views::getPath() . 'Raw/base_footer.php',
			'stylesheet' => Views::returnURL(true, 'style') . 'main.css',
			'font' => $view->returnURL(true, 'font') . 'Ubuntu.css',
			'js' => $view->returnURL(true, 'js') . 'controller.js',
			'title_one' => 'Bingo is easy to understand',
			'bloc_one' => "
				Bingo is built in accordance with MVC standards. If you decide to
				use the framework, you will interact with Bingo's controllers, views,
				and models to simplify the website creation process. 
			",
			'title_two' => 'Bingo offers flexibility',
			'bloc_two' => "
				Bingo will grant whoever uses it the ability 
				to chose the template engine that best suits their needs.
				Mustache syntax and customizable, 'raw-PHP' templates are both 
				available. 
			",
			'title_three' => 'Bingo is my digital Frankenstein',
			'bloc_three' => "
				Bingo is the brain-child of Lochemem Bruno Michael; a college student
				motivated by the need to solve problems.
			",
			'links' => [
				['http://localhost:8080/home/index', 'Home'], 
				['http://localhost:8080/home/about', 'About'], 
				['https://github.com/ace411/Bingo-Framework', 'Documentation'], 
				['https://github.com/ace411/Bingo-Framework', 'GitHub']
			]
		]);
	}
}