<?php

/**
 * Users Controller
 *
 * @package Bingo Framework
 * @author Lochemem Bruno Michael
 *
 */

namespace App\Controllers\Admin;

class Users
{
	use \Core\Controller;

	/**
	 * Renders admin/users/index page
	 */

	public function indexAction()
	{
		echo 'Hello from the admin index page';
	}
}
