<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends CI_Controller {

	/**
	 * /logout
	 * Cierra sesión y redirige a la página de inicio
	 */
	public function index()
	{
		$this->user->logout();
	}

}
