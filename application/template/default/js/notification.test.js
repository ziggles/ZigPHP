var socket = io.connect('http://localhost:8080');

var notification_count = 0;

socket.on('connect', function () {
    socket.emit('setName', 1);
});

socket.on('message', function (data) {
	$('#message_box').append(data.message + '<br>');
});

socket.on('receiveNotification', function(data) {
	notification_count = notification_count + data.length;
	if (notification_count == 1)
	{
		$('.notification_counter').html(notification_count + ' new notification').removeClass('notification_none').addClass('notification_has');
	}
	else
	{
		$('.notification_counter').html(notification_count + ' new notifications').removeClass('notification_none').addClass('notification_has');
	}
	console.log(data);

	$.each(data, function(i, notification) {
		$('.notification_container').prepend(notification.message + '<br><span class="gray_text">From: ' + notification.from + '</span><p>');
	});

});

$(document).ready(function () {
	$('.notification_container').hide();
	
	$('.notification_counter, .notification_container').click( function(event) {
		event.stopPropagation();
	});
	
	$('.notification_counter').click( function() {		
		$('.notification_counter').html('No New Notifications').removeClass('notification_has').addClass('notification_active');
		
		$('.notification_container').slideDown(100);
		
		notification_count = 0;
	});
	
	$('html').click(function() {
		$('.notification_container').hide();
		$('.notification_counter').removeClass('notification_active').addClass('notification_none');
	});
});

/*
var timestamp = 0;
var ajaxRequest;

function startNotificationPolling() {	
	ajaxRequest = $.ajax(
	{
		type: "POST",
		url: return_base_url() + "index/poll_for_notifications",
		dataType: 'json',
		data: { user_id: 1, timestamp: timestamp },
		success: function(response, status)
		{
			timestamp = response.timestamp;

			setTimeout("startNotificationPolling()", 500);
		},
		error: function(response, status)
		{
			if (status == 'abort')
			{
				
			}
		}
	});
}


*/
