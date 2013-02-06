<script src="http://localhost:8080/socket.io/socket.io.js"></script>
<script type="text/javascript" src="<?php echo template::templatePath(); ?>js/notification.test.js"></script>
<style>
.notification_wrapper {
	position: relative;
}

.notification_counter {
	font-weight: 700;
	padding: 5px;
	width: 200px;
	text-align: center;
}

.notification_none {
	color: gray;
}

.notification_has {
	color: red;
}

.notification_active {
	color: gray;
	background-color: #ebebeb;
}

.notification_counter:hover {
	background-color: #ebebeb;
	cursor: pointer;
}

.notification_container {
	position: fixed;
	background-color: white;
	padding: 5px;
	width: 198px;
	border: 1px solid #a6a6a6;
	border-color-top: white;
	height: 250px;
	overflow-y: auto;
}

span.gray_text {
	color: gray;
}
</style>
<div style="padding: 20px;">
	<div class="notification_wrapper">
		<div class="notification_counter notification_none">
			No New Notifications
		</div>
		<div class="notification_container">

		</div>
	</div>

	<div id="message_box">
		
	</div>
</div>