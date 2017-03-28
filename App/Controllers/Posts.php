<?php

namespace App\Controllers;

class Posts
{
	use \Core\Controller;

	/**
     * Renders the posts/articles page
     */

	public function articlesAction()
	{
		$values = array_merge([
			'firstname' => 'Bingo: Posts|Articles'
		], $this->container['Views']->renderMustacheDefaults(true, 'Articles | Bingo Framework'));
		echo $this->container['Views']->mustacheRender('base', $values);
	}

	/**
     * Renders the posts/edit page
     */

	public function editAction()
	{
        $values = array_merge([
            'firstname' => 'Bingo: Posts|Edit'
        ], $this->container['Views']->renderMustacheDefaults(true, 'Edit | Bingo Framework'));
        echo $this->container['Views']->mustacheRender('base', $values);
	}
}
