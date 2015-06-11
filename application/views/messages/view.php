<div class="container">
	<div class="row">  
		<div class="span6">  
			<ul class="breadcrumb">  
				<li>  
					<a href="<?php echo base_url() . "messages";?>">Messages Home</a> <span class="divider">></span>  
				</li>
				<li>  
					<a href="<?php echo base_url() . "messages/inbox";?>">View Messages</a> <span class="divider">></span>  
				</li>
				<li class="active">View Message</li>
			</ul>  
		</div>  
	</div>	
	<div class="row">
		<div class="span10">
			<h3>View Message</h3>
			<?php $unique_participants = array_unique($participants, SORT_REGULAR); ?>
			<div class="well">
				<table border="0">
					<tr><td><h4>From:</h4></td><td><?php echo $message['username']; ?></td></tr>
					<tr>
						<td style="text-align:left;vertical-align:top"><h4>To:</h4></td>
						<td>
							<?php
							if ( is_array($unique_participants) ) {
								$count = count($unique_participants);
								$c = 0;
								foreach ( $unique_participants as $participant ) {
									$c++;
									if ( $c != $count ) {
										echo $participant['username'] . ",&nbsp;";
									}
									else {
										echo $participant['username'];
									}
								}
							}
							?>
						</td>
					</tr>
					<tr><td><h4>Sent Date:<?php echo nbs(5); ?></h4></td><td><?php echo $message['sent_date']; ?></td></tr>
					<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
					<tr><td style="text-align:left;vertical-align:top"><h4>Subject:<?php echo nbs(5); ?></h4></td><td><?php echo nl2br($message['subject']); ?></td></tr>
					<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
					<tr><td style="text-align:left;vertical-align:top"><h4>Body:</h4></td><td><?php echo nl2br($message['body']); ?></td></tr>
				</table>
				<br /><br /><br />
				<p><a href="<?php echo base_url() . "messages/reply/" . $message['message_id'];?>" class="btn btn-primary"><i class="icon-repeat icon-white"></i>  Reply</a><?php echo nbs(6); ?>
                                    <a href="<?php echo base_url() . "messages/delete/" . $message['message_id'] . "/" . $type;?>" class="btn btn-danger"><i class="icon-remove icon-white"></i>  Delete</a></p>
			</div>
		</div>
	</div>
	<hr>
	<a href="<?php echo base_url() . "messages/inbox";?>" class="btn" ><i class="icon-step-backward"></i> Go back</a>
</div>
<?php echo form_close(); ?>
