<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<!-- BEGIN HTML -->
<html lang="<?php echo $zig->lang->active; ?>">
<!-- BEGIN HEADER -->
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="<?php echo template::templatePath(); ?>js/app.js"></script>
	<script type="text/javascript">
	set_paths('<?php echo $zig->base_url; ?>');
	</script>
	<title><?php echo template::getTitle(); ?></title>
	<meta name="author" content="">
	<meta name="keywords" content="">
	<meta name="description" content="">
	<?php 
	echo template::mainCSS(); 
	?>

</head>
<!-- END HEADER -->
<body>
	<!-- BEGIN NAV -->
	
	<!-- END NAV -->
	<!-- BEGIN PAGE CONTENT -->

	<div id="container_body">
	<div id="container">
	<h2>My Website</h2>
	<hr>

	<?php template::renderPageContent(); ?>
	
	<hr>
	</div>
	</div>

	<!-- END PAGE CONTENT -->
	<!-- BEGIN FOOTER -->
	<div id='footer'>Copyright &copy Daniel Gorziglia 2012</div>

	<!-- END FOOTER -->

</body>
</html>
<!-- END HTML -->