<?php

/**
 *
 * Home Controller
 *
 * @package Bingo Framework
 * @author Lochemem Bruno Michael
 *
 */

namespace App\Controllers;

class Home
{
    use \Core\Controller;

    /**
     * Renders the home/index page
     */

	public function indexAction()
	{
        $options = array_merge([
            'short_desc' => 'You might also enjoy...',
            'plans' => ['Reusable templates', 'MVC', 'Design Simplicity'],
            'links' => [
                ['http://localhost:' . $_SERVER['SERVER_PORT'] . '/home/index', 'Home'],
				['http://localhost:' . $_SERVER['SERVER_PORT'] . '/home/about', 'About'],
				['https://github.com/ace411/Bingo-Framework', 'Documentation'],
				['https://github.com/ace411/Bingo-Framework', 'GitHub']
            ]
        ], $this->container['Views']->renderRawDefaults(true));
		$this->container['Views']->render('Home/index.php', $options);
	}

    /**
     * Renders the home/about page
     */

	public function aboutAction()
	{
        $options = array_merge([
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
				['http://localhost:' . $_SERVER['SERVER_PORT'] . '/home/index', 'Home'],
				['http://localhost:' . $_SERVER['SERVER_PORT'] . '/home/about', 'About'],
				['https://github.com/ace411/Bingo-Framework', 'Documentation'],
				['https://github.com/ace411/Bingo-Framework', 'GitHub']
			]
        ], $this->container['Views']->renderRawDefaults(true));
		$this->container['Views']->render('Home/about.php', $options);
	}

    public function getPostsAction()
    {
        $this->container['BlogPosts']->getTwitterInfo();
    }
}
