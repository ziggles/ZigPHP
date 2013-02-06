<?php

Class welcomeController extends baseController
{
	public function setup()
	{
		$this->template->hideLayout();
		$this->template->setTitle('Welcome to ZigPHP');
	}

	public function index()
	{
		$this->load->db(1);
		
		if ($this->db->is_connected()) 
		{
			$connected = 1;
		}
		else
		{
			$connected = 0;
		}
				
		$this->template->set('connected', $connected);
		$this->template->render('zigPHP_welcome');	
	}
	
	public function reset()
	{
		$this->session->delete();
		header('Location: ' . config::get('SITE_URL'));
	}
}

// END of ZigPHP Welcome Controller
// Filename: welcomeController.php 
// Location: root/application/controllers/