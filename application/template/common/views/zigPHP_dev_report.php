<html>
<head>
	<style>
	#ZigPHP_dev_container {
		width: 1000px;
		font-family: Arial, sans-serif;  
		font-size: 0.9em;
		background: #ffffff;
		border-top: 1px solid #dedede;
		padding: 15px;
		top: 20px;
		left: 20px;
		margin-top: 15px;
		margin-bottom: 15px;
		margin-left: auto;
		margin-right: auto;
	}

	span.ZigPHP_dev_head {
		color: #005168;
		font-size: 18px;
		font-weight: 700;
	}

	span.ZigPHP_green {
		color: #4b9f13;
	}

	span.ZigPHP_blue {
		color: #2a8abd;
	} 
	
	span.ZigPHP_dev_title {
		font-weight: 800;
		padding-right: 15px;
	}
	
	table.zigPHP_dev {
		font-size: 1em;
		font-family: Arial, sans-serif;  
	}
	
	td.zigPHP_dev, th.zigPHP_dev {
		text-align: left;
		padding-right: 15px;
		padding-left: 5px;
	}

	tr.zigPHP_dev {
		height: 20px;
	}
	
	tr.zigPHP_dev_post:nth-of-type(odd) {
		padding-left: 5px;
		background-color: #ebebeb;
		height: 30px;
		border-bottom: 1px solid #c6c6c6;
	}
	
	tr.zigPHP_dev_post:nth-of-type(even) {
		padding-left: 5px;
		background-color: #f9f9f9;
		height: 30px;
		border-bottom: 1px solid #c6c6c6;
	}
	
	tr.zigPHP_dev_post_head {
		padding-left: 5px;
		background-color: #15739f;
		color: #fff;
		height: 25px;
	}
	
	</style>
</head>

<body>
	<div id="ZigPHP_dev_container">
	<span class="ZigPHP_dev_head">Dev Report</span>
	<p>
		
		<!-- General Dev Information -->
		
		<table class="zigPHP_dev" width="60%">
			<tr class="zigPHP_dev">
				<td class="zigPHP_dev">
					<span class="ZigPHP_dev_title">Page Generation Time:</span> <?php echo $render_time; ?> seconds
				</td>
				<td class="zigPHP_dev">
					<span class="ZigPHP_dev_title">Memory Usage:</span> <?php echo $mem_usage; ?>
				</td>
			</tr>
			<tr class="zigPHP_dev">
				<td class="zigPHP_dev">
					<span class="ZigPHP_dev_title">Current Route:</span> <?php echo $zig->route; ?>
				</td>
				<td class="zigPHP_dev">
					<span class="ZigPHP_dev_title">Controller:</span> <?php echo $zig->router->controller; ?>
				</td>
			</tr>
			<tr>
				<td class="zigPHP_dev">
					<span class="ZigPHP_dev_title">Method:</span> <?php echo $zig->router->action; ?>
				</td>
			</tr>
		</table>
		
		<!-- Loaded Templates -->
		
		<p>
		<b>Loaded Templates</b>
		<br><br>
		<?php if ($zig->cache->cache_exists == TRUE): ?>
			This page is cached
		<?php elseif(count($templates) == 0): ?>
			No templates loaded
		<?php else: ?>
			<table class="zigPHP_dev" width="90%" cellspacing="0">
				<tr class="zigPHP_dev_post_head">
					<th class="zigPHP_dev">Template Name</th>
					<th class="zigPHP_dev">Template Path</th>
				</tr>
		 	<?php foreach ($templates as $key => $value): ?>
				<tr class="zigPHP_dev_post">
					<td class="zigPHP_dev" style="border-bottom: 1px solid #c6c6c6;"><?php echo $key; ?></td>
					<td class="zigPHP_dev" style="border-bottom: 1px solid #c6c6c6;"><?php echo $value; ?></td>
				</tr>
			<?php endforeach; ?>
			</table>
		<?php endif; ?>
		</p>
		
		<!-- POST Data -->
		
		<p>
		<b>POST Data</b>
		<br><br>
		<?php if ($zig->post != NULL): ?> 
			<table class="zigPHP_dev" width="90%" cellspacing="0">
				<tr class="zigPHP_dev_post_head">
					<th class="zigPHP_dev">Key</th>
					<th class="zigPHP_dev">Value</th>
				</tr>
			<?php foreach ($zig->post as $key => $value): ?>
				<tr class="zigPHP_dev_post">
					<td class="zigPHP_dev" style="border-bottom: 1px solid #c6c6c6;"><?php echo $key; ?></td>
					<td class="zigPHP_dev" style="border-bottom: 1px solid #c6c6c6;"><?php if ($value == '' || $value == NULL) { echo 'null'; } else { showVars($value); } ?></td>
				</tr>
			<?php endforeach; ?>
			</table>
		<?php else: ?>
			No POST Data
		<?php endif; ?>
		</p>
		
		<!-- Database Queries -->
		
		<p>
		<b>Database Debug Information</b>
		<br><br>
		<?php if (count($db_queries) > 0): ?> 
			<table class="zigPHP_dev" width="90%" cellspacing="0">
				<tr class="zigPHP_dev_post_head">
					<th class="zigPHP_dev">Key</th>
					<th class="zigPHP_dev">Query</th>
					<th class="zigPHP_dev">Exec. Time</th>
				</tr>
			<?php foreach ($db_queries as $key => $value): ?>
				<tr class="zigPHP_dev_post">
					<td class="zigPHP_dev" style="border-bottom: 1px solid #c6c6c6;"><?php echo $key; ?></td>
					<td class="zigPHP_dev" style="border-bottom: 1px solid #c6c6c6;"><?php echo $value['query']; ?></td>
					<td class="zigPHP_dev" style="border-bottom: 1px solid #c6c6c6;"><?php echo $value['time']; ?> sec.</td>
				</tr>
			<?php endforeach; ?>
			</table>
		<?php else: ?>
			No Queries
		<?php endif; ?>
		</p>
		
		<!-- ZigPHP Global variable (registry) -->
		<span style="font-size: 1em;">
		<?php
		
		/* Uncomment out the line of code below if you wish to view the zigPHP Global variable.
		showVars($zig);
		*/
		
		?>
		</span>
		
	</p>
	</div>
</body>
</html>