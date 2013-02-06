<html>
<head>
	<title><?php echo template::getTitle(); ?></title>

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
		border: 1px solid #dedede;
		padding: 15px;
		width: 95%;
		position: relative;
		top: 20px;
		left: 20px;
	}

	#ZigPHP_container {
		padding-top: 30px;
		width: 1000px;
		top: 20px;
		left: 20px;
		margin: auto;
	}
	
	#ZigPHP_message {
		background: #efefee;
		border: 1px solid #c4c4c4;
		padding: 10px;
	}

	span.ZigPHP_head {
		color: #005168;
		font-size: 30px;
		font-weight: 700;
	}

	span.ZigPHP_title {
		color: #ff8a00;
		font-size: 24px;
		font-weight: 700;
	}

	span.ZigPHP_sub_title {
		color: #4b9f13;
		font-size: 20px;
		font-weight: 700;
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

	#zigPHP_container A:link {
		color: #139f9d;
		text-decoration: none;
	}

	#zigPHP_container A:visited {
		color: #139f9d;
		text-decoration: none;
	}

	#zigPHP_container A:hover {
		color: #139f9d;
		text-decoration: underline;
	}
	</style>
</head>

<body>
	<div id="zigPHP_container">
	<span class="zigPHP_head">Welcome to the ZigPHP Framework (1.0.0)</span>
	<p>
	If you are seeing this page, it means you have successfully installed ZigPHP. Congratulations! 
	</p>
	
	<!-- Editing this Page -->
	
	<span class="zigPHP_title">Editing this Page</span>
	<p>
		The controller for this page can be found at: 
	</p>
	<p>
		<div id="ZigPHP_message">
			root/application/controllers/welcomeController.php
		</div>
	</p>
	<p>
		The view file for this page can be found at: 
	</p>
	<p>
		<div id="ZigPHP_message">
			root/application/template/common/views/zigPHP_welcome.php
		</div>
	</p>
	
	<!-- Quick Setup -->
	
	<span class="zigPHP_title">Quick Setup</span>
	<p>
		Before you start developing your awesome application with ZigPHP, we need to change some settings. (Don't worry this will only take a minute).
	</p>
	<span class="ZigPHP_sub_title">Define your site URL</span><br> 
	Locate your <b>app.config.php</b> (root/application/config/) and change the site URL 
	<p>
	<div id="ZigPHP_message">
		<?php if (config::get('SITE_URL') == ''): ?>
			<font color="red">No Site URL defined</font>
		<?php else: ?>
			Current site URL: <b><?php echo config::get('SITE_URL'); ?></b>
		<?php endif; ?>
	</div>
	<p>
	<span class="ZigPHP_sub_title">Change Your Default Controller</span><br>
	Locate your <b>app.config.php</b> (root/application/config/) and change the default controller. By default, ZigPHP uses "welcome" as the main controller. 
	<p>
	<div id="ZigPHP_message">
		Current default controller: <b><?php echo config::get('DEFAULT_CONTROLLER'); ?></b>
	</div>
	</p>
		<span class="ZigPHP_sub_title">Enter your Database Information</span><br>
		Locate your <b>db.config.php</b> (root/application/config/) and enter your database information.
	<p>
		<div id="ZigPHP_message">
			<?php if ($connected == 1): ?>
				<font color="green">Successfully Connected to Database!</font>
			<?php else: ?>
				<font color="red">Could Not Connect to Database</font>
			<?php endif; ?>
		</div>
	</p>
	
	<p>
		Refresh this page to view your saved changes.
	<!-- That's It! -->
	
	<p>
		<span class="zigPHP_title">That's It!</span>
	</p>
	Thanks for choosing ZigPHP, we hope you enjoy it. Visit <a href="http://zigphp.com" target="_blank">ZigPHP.com</a> for documentation, tutorials, and more! 
	</div>
</body>
</html>