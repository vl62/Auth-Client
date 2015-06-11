<div class="container">
	<div class="row">  
		<div class="span6">  
			<ul class="breadcrumb">  
				<li>  
					<a href="<?php echo base_url() . "messages";?>">Messages Home</a> <span class="divider">></span>  
				</li>
				<li class="active">View Messages</li>
			</ul>  
		</div>  
	</div>
	
	
	<div class="tabbable">
		<ul class="nav nav-tabs" id="messagetabs">
			<li <?php if ( $this->session->userdata('inbox_tab') === "inbox" ) { echo "class='active'"; } ?>><a href="#inbox" data-toggle="tab">Inbox</a></li>
			<li <?php if ( $this->session->userdata('inbox_tab') === "sent" ) { echo "class='active'"; } ?>><a href="#sent" data-toggle="tab">Sent</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane<?php if ( $this->session->userdata('inbox_tab') === "inbox" ) { echo " active"; } ?>" id="inbox">
	
				<div class="row-fluid">
					<div id="main">
<!--					<table id="example-basic-expandable" class="table table-bordered table-striped table-hover">
							<tr data-tt-id="0">
								<td>app</td>
							</tr>
							<tr data-tt-id="1" data-tt-parent-id="0">
								<td>controllers</td>
							</tr>
							<tr data-tt-id="5" data-tt-parent-id="1">
								<td>application_controller.rb</td>
							</tr>
							<tr data-tt-id="2" data-tt-parent-id="0">
								<td>helpers</td>
							</tr>
							<tr data-tt-id="3" data-tt-parent-id="0">
								<td>models</td>
							</tr>
							<tr data-tt-id="4" data-tt-parent-id="0">
								<td>views</td>
							</tr>
						</table>-->
					</div>
					<!--<table id="example-basic-expandable" class="table table-bordered table-striped table-hover example-basic-expandable">-->
					<table id="inboxtable" class="table table-bordered table-striped table-hover">
						<thead>
							<tr>
								<th><input type="checkbox" class="selectallmessages" /></th>
								<th>From</th>
								<th>Subject</th>
								<th>Body</th>
								<th>Date</th>
								<th>Action</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $messages as $key => $message ): ?>
							<tr data-tt-id="<?php echo $message['message_id']; ?>" <?php if ( $message['message_id'] != $message['thread_id'] ): ?>data-tt-parent-id="<?php echo $message['thread_id']; ?>" <?php endif; ?>>
								<td><input type="checkbox" name="case" value="inbox_<?php echo $message['message_id']; ?>" class="case" id="inbox_<?php echo $message['message_id']; ?>" style="margin:10px"  /></td>
								<td><?php echo $message['username']; ?></td>
								<td><a href="<?php echo base_url() . "messages/view/" . $message['message_id'] . "/" . "inbox";?>"><?php echo  $message['subject']; ?></a></td>
								<td>
									<?php
									if ( strlen($message['body']) > 50 ) {
										echo substr($message['body'], 0, 50) . anchor(base_url() . "messages/view/" . $message['message_id'] . "/" . "inbox", ' ...', 'title="Link to full message"');
									}
									else {
										echo $message['body'];
									}
									?>
								</td>
								<td><?php echo  $message['sent_date']; ?></td>
								<td><a href="<?php echo base_url() . "messages/reply/" . $message['message_id'];?>" rel="popover" data-content="Click to reply to this message" data-original-title="Reply"><i class="icon-repeat"></i></a></td>
								<td>
									<?php if ( $message['status'] == 1 ):?>
									<?php echo img(array('src'=> base_url('resources/images/cafevariome/icon-unread-mail.png'), 'alt'=> 'Unread', 'title' => 'Unread')); ?>
									<?php else: ?>
									<?php echo img(array('src'=> base_url('resources/images/cafevariome/icon-read-mail.png'), 'alt'=> 'Read', 'title' => 'Read')); ?>
									<?php endif; ?>
								</td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
					<br />
					<?php $number_of_inbox_messages = count($messages); ?>
					<?php if ( $number_of_inbox_messages > 0 ): ?>
					<button onclick="markSelectedMessagesAsRead();" class="btn btn-info btn-small" ><i class="icon-question-sign"></i>  Mark Selected As Unread</button><?php echo nbs(3); ?>
                                        <button onclick="if(confirm('Are you sure you want to delete the selected messages?')) deleteSelectedMessagesInbox();return false;" class="btn btn-danger btn-small" ><i class="icon-remove icon-white"></i>  Delete Selected</button>
					<br />
					<br />
					<?php endif; ?>
					<a href="<?php echo base_url() . "messages/send";?>" class="btn btn-info btn-small" rel="popover" data-content="Send a new message to another user" data-original-title="New Message"><i class="icon-file"></i>  New Message</a>
				</div>
			</div>

			<div class="tab-pane<?php if ( $this->session->userdata('inbox_tab') === "sent" ) { echo " active"; } ?>" id="sent">
				<div class="row-fluid">
					<!--<table class="table table-bordered table-striped table-hover example-basic-expandable">-->
					<table id="sentmailtable" class="table table-bordered table-striped table-hover">
						<thead>
							<tr>
								<th><input type="checkbox" class="selectallmessages" /></th>
								<th>To</th>
								<th>Subject</th>
								<th>Body</th>
								<th>Date</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $sent_messages as $key => $message ): ?>
							<tr>
								<td><input type="checkbox" name="case" value="outbox_<?php echo $message['message_id']; ?>" class="case" id="outbox_<?php echo $message['message_id']; ?>" style="margin:10px"  /></td>
								<td><?php echo  $message['username']; ?></td>
								<td><a href="<?php echo base_url() . "messages/view/" . $message['message_id'] . "/" . "sent";?>"><?php echo  $message['subject']; ?></a></td>
								<td>
									<?php
									if ( strlen($message['body']) > 50 ) {
										echo substr($message['body'], 0, 50) . anchor(base_url() . "messages/view/" . $message['message_id'] . "/" . "sent", ' ...', 'title="Link to full message"');
									}
									else {
										echo $message['body'];
									}
									?>
								</td>
								<td><?php echo  $message['sent_date']; ?></td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
					<br />
					<?php $number_of_sent_messages = count($sent_messages); ?>
					<?php if ( $number_of_sent_messages > 0 ): ?>
					<button onclick="if(confirm('Are you sure you want to delete the selected messages?')) deleteSelectedMessagesOutbox();return false;" class="btn btn-danger btn-small" ><i class="icon-remove icon-white"></i>  Delete Selected</button>
					<?php endif; ?>
				</div>
			</div>
			<hr>
			<a href="<?php echo base_url() . "messages";?>" class="btn" ><i class="icon-step-backward"></i> Go back</a>

		</div>
	</div>
    <input type="hidden" id="user_id" value="<?php echo $user_id; ?>">
</div>
<?php echo form_close(); ?>
