<html>
<head>
	<title>An Unexpected Error has Occurred</title>
	<style>
	html, body {
	height: 100%; 
	margin: 0px;
	padding: 0px;
	width: 100%;
	font-family: Arial, sans-serif;  
	font-size: 0.9em;
	}

	#ZigPHP_error_container {
		margin: auto;
		width: 900px;
		border: 1px solid #dedede;
		padding: 15px;
		top: 20px;
		left: 20px;
		margin-top: 15px;
		margin-bottom: 15px;
	}

	span.ZigPHP_error_title {
		color: #e91414;
		font-size: 24px;
		font-weight: 700;
	}

	span.ZigPHP_green {
		color: #4b9f13;
	}

	span.ZigPHP_blue {
		color: #2a8abd;
	}
	</style>
</head>

<body>
	<div id="zigPHP_error_container">
	<span class="zigPHP_error_title">An Unexpected Error has Occurred</span>
	<p>
		<?php if (config::get('APP_ENV') == 'PROD'): ?>
			An unexpected error has occurred that has prevented the page from loading. 
			<?php die(); ?>
		<?php else: ?>
			An unexpected error has occurred that has prevented the page from loading. 
			<?php foreach($errors as $error): ?>
				<?php if ($error['file'] == '' && $error['line'] == ''): ?>
					<p>
						The system says: <span class="zigPHP_green"><?php echo $error['err_msg']; ?></span>   
				<?php else: ?>
					<p>
						The system says: <span class="zigPHP_green"><?php echo $error['err_msg']; ?></span>   
					<?php
					echo '<p> Error in: <span class="zigPHP_blue">'.$error['file'].' at line '.$error['line'].'.</span>'; 
				endif;
				if (config::get('LOG_ERRORS') == TRUE)
				{
					$level = config::get('LOG_LEVEL');
					if ($error['log_type'] == $level OR $level == 3)
					{
						echo '<p>Error has been logged</p>';
					}
				}
			endforeach;
		endif;
		?>
	<p>
	</div>
</body>
</html>