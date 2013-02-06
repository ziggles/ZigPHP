<?php

Class testController extends baseController
{
	public function setup()
	{
		$this->template->setTitle('Form Test Page');
	}
	
	public function index($foo = '')
	{		
		$this->load->library('form');
		$this->form->addRule('f_name', $this->lang->get('form_names:first_name'), 'required');
		$this->form->addRule('l_name', $this->lang->get('form_names:last_name'), 'required');
		$this->form->addRule('comments', $this->lang->get('form_names:comments'), 'min_length:10|required');
				
		$this->form->setDelimiter('<li><font color="red">', '</font></li>');
		
		$form_config = array(
			'f_name' 		=> array(
				'size'		=> 	'35',
				'class'		=> 	'the_pro_class'
				),
			'l_name' 		=> array(
				'size' 		=> 	'35',
				'class' 	=> 	'the_pro_class'
				),
			'comments'	=> array(
				'cols' 		=> 	'50',
				'rows'		=> 	'5'
				)			
			);

		if (isset($this->post->submit))
		{
			if ($this->form->validate() == FALSE)
			{
				$this->template->set('form_config', $form_config);
				$this->template->render('form_test', $this->lang->active);	
			}
			else
			{
				$this->template->set('data', $this->post);
				$this->template->render('form_success');
			}
		}
		else
		{
			$this->template->set('form_config', $form_config);
			$this->template->render('form_test', $this->lang->active);	
		}

	}
	
	public function db()
	{
		$this->template->setTitle('Database Test');
		$this->template->render('db_test');
			
		$this->load->loadDB();
		
	 	$result = $this->db->select('users', array('password'));
				
		while ($row = $result->rows())
		{
			showVars($row);
		}
	}
}

