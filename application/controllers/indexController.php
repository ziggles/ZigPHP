<?php

Class indexController extends baseController
{
	public function index()
	{
		$this->template->setTitle('Real Time Notifications');
		$this->template->render('notification_test');
	}
}

// END of Index Controller
// Filename: indexController.php 
// Location: root/application/controllers/