<style>
	textarea#styled {
		width: 600px;
		height: 120px;
		border: 3px solid #cccccc;
		padding: 5px;
		font-family: Tahoma, sans-serif;
		background-image: url('<?php echo base_url() . "resources/images/backgrounds/cream_dust.png";?>');
		background-position: bottom right;
		/*background-repeat: no-repeat;*/
	}
</style>

<?php if ( isset($participant_data)): ?>
<script>
$(document).ready(function() {
	<?php $unique_participants = array_unique($participant_data, SORT_REGULAR); ?>
	<?php foreach ( $unique_participants as $participant ): ?>
		<?php // if ( $participant['recipient_id'] != $user_id ): ?>
		<?php error_log("username -> " . $participant['username'] . " -> " . $participant['recipient_id']); ?>
			$('#messaging-user-input').tokenInput('add', {id: <?php echo $participant['recipient_id']; ?>, name: "<?php echo $participant['username']; ?>"});
		<?php // endif; ?>	
	<?php endforeach; ?>
});
</script>
<?php endif; ?>

<div class="container">
	<!--<div class="well">-->
		<div class="row">  
			<div class="span6">  
				<ul class="breadcrumb">  
					<li>  
						<a href="<?php echo base_url() . "messages";?>">Messages Home</a> <span class="divider">></span>  
					</li>
					<li class="active">Send Message</li>
				</ul>  
			</div>  
		</div>	
		<div class="row">
			<div class="span10">
				<h3>Send Message</h3><hr>
				<div id="sendMessage" class="hide" style="color: red;"></div>
				<h4>To:</h4><!-- TODO add a modal here and populate with all the users, then when one is clicked (maybe with a switch box then user the selector function from the jquery plugin to add it to the box-->
				<?php echo form_open("messages/send", array('name' => 'sendMessage')); ?>
					<div class="input-append">
						<?php echo form_input($recipients); ?>
						<!--<input type="text" id="messaging-user-input" name="message-recipients" />-->
						<span class="add-on"><a data-toggle="modal" href="#user_list" target="_blank" data-backdrop="false" rel="popover" data-content="Start typing a username above or click to view available users." data-original-title="Add Recipients"><i class="icon-plus"></i></a></span>
					</div>
				<h4>Subject:</h4>
				<!--<input type="text" id="message-subject" name="message-subject" size="60" placeholder="Enter your subject..."/>-->
				<?php echo form_input($subject); ?>
				<h4>Message Body:</h4>
				<textarea cols="50" rows="5" id="message-body" name="message-body" id="styled" onfocus="setbg('#e5fff3')" onblur="setbg('white')" placeholder="Enter body..." ><?php echo set_value('message-body'); ?></textarea>
				<br />
			</div>
		</div>
		<div class="row">
			<div class="span4 offset2">
                            <p><button type="submit" name="submit" onclick="send_message();" class="btn btn-primary"><i class="icon-file icon-white"></i>  Send Message</button></p>
			</div>
		</div>
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
	<!--</div>-->
	<br />
	<hr>
	<a href="<?php echo base_url() . "messages/inbox";?>" class="btn" ><i class="icon-step-backward"></i> Go back</a>
	<div id="user_list" class="modal hide fade in" style="display: none; ">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">×</a>
			<h3>Add/remove recipients</h3>
		</div>
		<div class="modal-body">
			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th>User Name</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($users as $k => $user): ?>
					<tr>
						<td><?php echo $user->username; ?></td>
						<?php if ( $user->username ): ?>
						<td>
							<div class="slider messages_add_users_slider" >
								<input type="checkbox" data-id="<?php echo $user->id; ?>" data-username="<?php echo $user->username; ?>" id="<?php echo $user->id; ?>" name="add-remove" class="add-remove" />
							</div>
						</td>
						<?php endif; ?>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<div class="modal-footer">
			<a href="#" class="btn" data-dismiss="modal">Close</a>
		</div>
	</div>
</div>
<?php echo form_close(); ?>
