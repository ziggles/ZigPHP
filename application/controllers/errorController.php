<?php

Class errorController extends baseController
{
	public function index()
	{
		$this->template->hideLayout();
		$this->template->setTitle('An unexpected error has occurred');
		$this->template->set('err_msg', 'Unexpected error. If this problem continues to persist, contact the website administrator.');
		$this->template->render('error');
	}

	public function error404()
	{
		$this->template->setTitle('Error 404');
  		$this->template->render('error404');
	}
}

// END of ZigPHP Error Controller
// Filename: errorController.php 
// Location: root/application/controllers/