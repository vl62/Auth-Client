<div class="container">
	<div class="row">  
		<div class="span6">  
			<ul class="breadcrumb">  
				<li class="active">Messaging Dashboard</li>  
			</ul>  
		</div>  
	</div>
		
	<?php if ( isset($success_message) ) { echo "<div class='row'><div class='span6 offset2 pagination-centered'><div id='success-alert' class='alert alert-info'><button type='button' class='close' data-dismiss='alert'>&times;</button><p>Message was successfully sent!</p></div></div></div>"; } ?>

	<div class="row">
		<?php echo br(2); ?>
		<div class="span6 pagination-centered"><a href="<?php echo base_url() . "messages/inbox";?>" class="btn btn-info btn-large" rel="popover" data-content="View your inbox and sent messages" data-original-title="Inbox"><img width="100" height="100" src="<?php echo base_url();?>resources/images/cafevariome/icon-inbox.png" /></a><br />Inbox<br /><br />
                    <span class="badge badge-info" id="msgCount">
                    </span>
                </div>
		<div class="span6 pagination-centered"><a href="<?php echo base_url() . "messages/send";?>" class="btn btn-info btn-large" rel="popover" data-content="Send a new message to another user" data-original-title="New Message"><img width="100" height="100" src="<?php echo base_url();?>resources/images/cafevariome/icon-new-message.png" /></a><br />New Message</div>
		<!--<div class="span4 pagination-centered"><a href="<?php // echo base_url() . "messages/settings";?>" class="btn btn-info btn-large" rel="popover" data-content="Configure the message settings" data-original-title="Message Settings"><img width="100" height="100" src="<?php // echo base_url();?>resources/images/cafevariome/icon-configuration.png" /></a><br />Message Settings</div>-->
	</div>
	</div>
	<br />
	<br />
        <input type="hidden" id="user_id" value="<?php echo $user_id; ?>">
</div>

<script>
    $(document).ready(function() {
        $.ajax({url: authurl + '/auth_messages/get_unread_message_count',
		data: {'user_id': $("#user_id").attr("value")},
		dataType: 'html',
		delay: 200,
		type: 'POST',
		success: function(response) {
                    if(response >0) {
                        $("#msgCount").html(response + " Unread Message(s)");
                        $("#msgUnreadCount").text(response)
                    } else {
                        $("#msgCount").html("No Unread Messages");
                    }
		},
                error: function (jqXHR, textStatus, errorThrown) {
                        $("#msgCount").html("Something went wrong. ");
                    }
	});
    });
</script>