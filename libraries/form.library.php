<?php

class form
{
	/*
	|--------------------------------------------------------------------------
	| Form Library V1.0 - ZigPHP Framework
	|--------------------------------------------------------------------------
	|
	| This class was designed to make form creation and validation a lot easier. 
	| The class is separated in to two parts, the first one for form validation, and the second for creating form elements. 
	|
	| The form validation functions allows you to set validation rules with custom error messages. 
	| Using the validate() function will validate the form's POST data based off the validation rules
	| set in the controller and return TRUE if all parameters/rules are met.  
	|
	| The form create functions will take in an array of attributes you set and return an HTML form element.
	| These functions have pre-built support for showing POST data in the fields if any errors occur. 
	*/

	public $requires_config;
	private $zig;

	public $rules = array();
	public $messages = array();
	public $errors = array();
	public $error_delimiters = array();
	
	public function __construct()
	{
		$this->zig =& get_instance();
	}

	/*
	|--------------------------------------------------------------------------
	| Form Validation
	|--------------------------------------------------------------------------
	|
	| addRule =			This function adds a validation rule(s) for one form element. 
	|					@string field_name = The name of the form element to validate.
	|					@string human_name = A human version of the form element name. i.e. First Name instead of f_name.
	|					@string val_rule = The validation rules separated by '|'. Refer to documentation for examples and accepted values. 
	|
	| addMessage =		This function adds a custom error message to one of the form elements. 
	|					@string start_delim = Starting delimiter
	|					@string end_delim = Ending delimiter. 
	|					optional @string field_name = Enter the field name f you want to add a seperate delimiter for one field. 
	|
	| setDelimiter =	This function sets the delimiters for the error messages. 
	|					@string field_name = The name of the form element to show an error for.
	|					@string err_message = The custom error message. 
	|
	| validate =		This function validates the POST data against the validation rules. 
	|					Returns FALSE if validation failed and returns TRUE if validation is a success. 
	|
	*/
	
	public function addRule($field_name='', $human_name='', $val_rule='')
	{
		//Let's check to see if all the parameters are there. 
		$errors = array();
		if (empty($field_name) || $field_name == '')
		{
			$errors[] = 'Missing parameter 1 for addRule. No input name was given.';
		}
		if (empty($human_name))
		{
			$errors[] = 'Missing parameter 2 for addRule. No human name was given.';
		}
		if (empty($val_rule) || $val_rule == '')
		{
			$errors[] = 'Missing parameter 3 for addRule. No rule was set.';
		}
		
		//If we are missing some parameters, we'll spit out an error. 
		if (count($errors) >= 1)
		{
			foreach ($errors as $error)
			{
				$err_mes .= $error . ' ';
			}
			showError($err_mes, 'addRule');
		}

		//Let's organize all of our gathered information and put it into one array to represent a rule. 
		$rule = array();

		$rule['f_name'] = $field_name;
		$rule['h_name'] = $human_name;
		$rule['v_rule'] = array();

		//If more than one validation rules is given, let's take it apart. 
		if (strpos($val_rule, '|') !== FALSE)
		{
			$rules = explode('|', $val_rule);
			foreach($rules as $a_rule)
			{
				$rule['v_rule'][] = $a_rule;
			}
		}
		else
		{
			$rule['v_rule'][] = $val_rule;
		}

		//Let's add this new rule into our master rules array.
		$this->rules[] = $rule;
	}

	public function addMessage($field_name='', $err_message = '')
	{
		//Let's check to see if all the parameters are there. 
		$errors = array();
		if (empty($field_name) || $field_name == '')
		{
			$errors[] = 'Missing parameter 1 for addMessage. No field name was given.';
		}
		if (empty($err_message) || $err_message == '')
		{
			$errors[] = 'Missing parameter 2 for addMessage. No message was given.';
		}
		//If we are missing some parameters, we'll spit out an error. 
		if (count($errors) >= 1)
		{
			$err_mes = '<p>';
			foreach ($errors as $error)
			{
				$err_mes .= $error.'<br>';
			}
			showError($err_mes, 'addRule');
		}

		$message = array();
		$message['message'] = $err_message;

		if (strpos($field_name, ':') !== FALSE)
		{
			$parts = explode(':', $field_name);
			$message['field'] = $parts[0];
			$message['rule'] = $parts[1];
		}
		else
		{
			$message['field'] = $field_name;
		}

		$this->messages[] = $message; 
	}

	public function setDelimiter($start_delim = '', $end_delim = '', $field_name = '')
	{
		if ($field_name != '')
		{
			$delimiters = array();
			$delimiters['start'] = $start_delim;
			$delimiters['end'] = $end_delim;
			$this->error_delimiters[$field_name] = $delimiters;
			return;
		}

		$delimiters = array();
		$delimiters['start'] = $start_delim;
		$delimiters['end'] = $end_delim;
		$this->error_delimiters['global'] = $delimiters;
	}

	public function validate()
	{
		log_debug('Validating Form...');
		
		//If our master rules array isn't an array, let's return FALSE.
		if(!is_array($this->rules))
		{
			log_debug('Form validation failed. Rules isn\'t array');
			return FALSE;
		}

		//Let's gather and set some information. Get rule, get POST data, and set errors to 0. 
		$rules = $this->rules;
		$post_data = $this->zig->post;
				
		//If our POST data is empty, return FALSE. 
		if (empty($post_data))
		{
			log_debug('Form validation failed: no POST data');
			return FALSE;
		}
		
		$errors = array();
				
		if ($post_data->csrf_token != $this->zig->session->csrf_token)
		{
			$errors['csrf_token'] = array(
				'field' => 'csrf_token',
				'message' => config::get('FORM_EROMES_GENERAL')
			);
			log_debug('Validation failed. Invalid csrf_form token. From: ' . $this->zig->session->IP);
		}

		// Let's break down our rules array into separate rules and work form there. 
		foreach($rules as $rule)
		{
			// Break down the validation rules. 
			foreach($rule['v_rule'] as $v_rule)
			{
				$error_count = 0;
				$temp_error = array();
				
				// Let's get our POST data that applies to this form element and do some validation! 
				$post = $post_data->$rule['f_name'];
				
				if ($v_rule == 'required')
				{
					if (empty($post) || $post == '')
					{
						$error_count++;
						$temp_error['field'] = $rule['f_name'];
						$temp_error['message'] = $this->setMessage($rule['f_name'], 'required', $rule['h_name']);
					}
				}
				if (strpos($v_rule, 'min_length') !== FALSE)
				{
					$parts = explode(':', $v_rule);
					if (strlen($post) < $parts[1])
					{
						$error_count++;
						$temp_error['field'] = $rule['f_name'];
						$temp_error['message'] = $this->setMessage($rule['f_name'], 'min_length', $rule['h_name'], $parts[1]);
					}
				}
				if (strpos($v_rule, 'max_length') !== FALSE)
				{
					$parts = explode(':', $v_rule);
					if (strlen($post) > $parts[1])
					{
						$error_count++;
						$temp_error['field'] = $rule['f_name'];
						$temp_error['message'] = $this->setMessage($rule['f_name'], 'max_length', $rule['h_name'], $parts[1]);
					}
				}
				if (strpos($v_rule, 'exact_length') !== FALSE)
				{
					$parts = explode(':', $v_rule);
					if (strlen($post) != $parts[1])
					{
						$error_count++;
						$temp_error['field'] = $rule['f_name'];
						$temp_error['message'] = $this->setMessage($rule['f_name'], 'exact_length', $rule['h_name'], $parts[1]);
					}
				}
				if (strpos($v_rule, 'inbetween') !== FALSE)
				{
					$parts = explode(':', $v_rule);
					if (strlen($post) < $parts[1] || strlen($post) > $parts[2])
					{
						$error_count++;
						$temp_error['field'] = $rule['f_name'];
						$temp_error['message'] = $this->setMessage($rule['f_name'], 'inbetween', $rule['h_name'], $parts[1], $parts[2]);
					}
				}
				if (strpos($v_rule, 'email') !== FALSE)
				{
					if(!preg_match("/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i", $post))
					{
						$error_count++;
						$temp_error['field'] = $rule['f_name'];
						$temp_error['message'] = $this->setMessage($rule['f_name'], 'email', $rule['h_name']);
					}  
				}
				if (strpos($v_rule, 'must_match') !== FALSE)
				{
					$parts = explode(':', $v_rule);
					$comparison = baseController::getPostData($parts[1]);

					if ($post != $comparison)
					{
						$error_count++;
						$temp_error['field'] = $rule['f_name'];
						$temp_error['message'] = $this->setMessage($rule['f_name'], 'must_match', $rule['h_name']);
					}
				}

				if ($error_count > 0)
				{
					$errors[$rule['f_name']] = $temp_error;
				}
			}
		}

		$this->errors = $errors;

		if (count($this->errors) > 0)
		{
			$this->setErrors();
			log_debug('Form validation failed');
			return FALSE;
		}
		else
		{
			log_debug('Form validation successful');
			$this->setErrors(1);
			return TRUE;
		}
	}

	private function setMessage($field_name, $type, $h_name='', $value='', $second_value='')
	{
		foreach ($this->messages as $message)
		{
			if ($field_name == $message['field'] && array_key_exists('rule', $message))
			{
				if ($message['rule'] == $type)
				{
					return $message['message'];
				}
			}
			elseif ($field_name == $message['field'])
			{
				return $message['message'];
			}
		}

		$type = strtoupper($type);
		$message = config::get('FORM_EROMES_'.$type);
		
		$search = array('&name', '&value', '&second_value');
		$replace = array($h_name, $value, $second_value);

		$message = str_replace($search, $replace, $message); 
		return $message;
	}

	private function setErrors($none = '')
	{
		$post_data = $this->zig->post;
				
		//If our POST data is empty, return FALSE. 
		if (empty($post_data))
		{
			return FALSE;
		}

		$errors = $this->errors;
		$delimiters = $this->error_delimiters;
		$view_errors = array();

		if ($none == 1)
		{
			foreach ($errors as $key => $error)
			{
				$view_errors[$key] = '';
				$this->zig->template->set('form_errors', $view_errors);
				return;
			}
		}

		foreach ($errors as $key => $error)
		{
			if (array_key_exists($key, $delimiters))
			{
				$view_error = $delimiters[$key]['start'];
				$view_error .= $error['message'];
				$view_error .= $delimiters[$key]['end'];
			}
			elseif (array_key_exists('global', $delimiters))
			{
				$view_error = $delimiters['global']['start'];
				$view_error .= $error['message'];
				$view_error .= $delimiters['global']['end'];
			}
			else
			{
				$view_error = $error['message'];
			}
			$view_errors[$key] = $view_error;
		}

		$this->zig->template->set('form_errors', $view_errors);
	}

	/*
	|--------------------------------------------------------------------------
	| Create Form Inputs 
	|--------------------------------------------------------------------------
	|
	| The functions below create a HTML input fields. 
	| By default all the functions have enable_post set to 1, except createInputPassword
	|
	*/
	
	public function create($action, $name = '')
	{
		echo '<form name="' . $name . '" action="' . $action . '" method="POST">
';
		echo '<input type="hidden" name="csrf_token" value="' . $this->zig->session->csrf_token . '">
';
	}

	public function text($field_name='', $attributes='', $enable_post=1)
	{
		if (empty($field_name))
		{
			showError('Missing parameter 1 for createInputText. No input name was given.', 'createInputText');
		}

		$form_input = '<input type="text" ';
		$form_input .= 'name="'.$field_name.'" ';

		$post_data = $this->zig->post;

		if ($attributes != '')
		{
			if(!is_array($attributes))
			{
				showError('Attributes parameter must be array in createInputText.', 'createInputText');
			}
			else
			{
				if (array_key_exists('value', $attributes))
				{
					$default_value = $attributes['value'];
					if (isset($post_data->$field_name))
					{
						unset($attributes['value']);					
					}
					else
					{
						$form_input .= 'value="'.$default_value.'" ';
					}
				}

				foreach ($attributes as $key => $value)
				{
					$form_input .= $key.'="'.$value.'" ';
				}
			}
		}

		if ($enable_post == 1 && isset($post_data->$field_name))
		{
			$form_input .= 'value="'.$post_data->$field_name.'"';
		}

		$form_input .= '>
';
		echo $form_input;
	}

	public function password($field_name='', $attributes='', $enable_post=0)
	{
		if (empty($field_name))
		{
			showError('Missing parameter 1 for createInputPassword. No input name was given.', 'createInputPassword');
		}

		$form_input = '<input type="password" ';
		$form_input .= 'name="'.$field_name.'" ';

		if ($attributes != '')
		{
			if(!is_array($attributes))
			{
				showError('Attributes parameter must be array in createInputPassword.', 'createInputPassword');
			}
			else
			{
				foreach ($attributes as $key => $value)
				{
					$form_input .= $key.'="'.$value.'" ';
				}
			}
		}

		if ($enable_post == 1 && isset($post_data->$field_name))
		{
			$post_data = $this->zig->post;
			$form_input .= 'value="'.$post_data->$field_name.'"';
		}

		$form_input .= '>
';
		echo $form_input;
	}

	public function textarea($field_name='', $attributes='', $enable_post=1)
	{
		if (empty($field_name))
		{
			showError('Missing parameter 1 for createInputTextarea. No input name was given.', 'createInputTextarea');
		}

		$form_input = '<textarea ';
		$form_input .= 'name="'.$field_name.'" ';

		$post_data = $this->zig->post;

		if ($attributes != '')
		{
			if(!is_array($attributes))
			{
				showError('Attributes parameter must be array in createInputTextarea.', 'createInputTextarea');
			}
			else
			{
				if (array_key_exists('value', $attributes))
				{
					if (isset($post_data->$field_name))
					{
						unset($attributes['value']);
					}
					else
					{
						$textarea_value = $attributes['value'];
					}
				}

				foreach ($attributes as $key => $value)
				{
					$form_input .= $key.'="'.$value.'" ';
				}
			}
		}

		$form_input .= '>';

		if (isset($textarea_value))
		{
			if (isset($post_data->$field_name))
			{
			}
			else
			{
				$form_input .= $textarea_value;
			}
		}

		if ($enable_post == 1 && isset($post_data->$field_name))
		{
			$form_input .= $post_data->$field_name;
		}

		$form_input .= '</textarea>
';
		echo $form_input;
	}

	public function submit($field_name='', $attributes='')
	{
		if ($field_name == '')
		{
			$field_name = 'Submit';
		}

		$form_input = '<input type="submit" name="submit" ';
		$form_input .= 'value="'.$field_name.'" ';

		if ($attributes != '')
		{
			if(!is_array($attributes))
			{
				showError('Attributes parameter must be array in createInputSubmit.', 'createInputSubmit');
			}
			else
			{
				foreach ($attributes as $key => $value)
				{
					$form_input .= $key.'="'.$value.'" ';
				}
			}
		}

		$form_input .= '>
';
		echo $form_input;
	}

}

?>