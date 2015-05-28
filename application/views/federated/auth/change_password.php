<h1>Change Password</h1>

<div id="infoMessage"><b><?php echo $message; ?></b></div>
<?php echo form_open("auth/change_password");?>

      <p>Old Password:<br />
      <?php echo form_input($old_password);?>
      </p>
      
      <p>New Password (at least <?php echo $min_password_length;?> characters long):<br />
      <?php echo form_input($new_password);?>
      </p>
      
      <p>Confirm New Password:<br />
      <?php echo form_input($new_password_confirm);?>
      </p>
      
      <?php echo form_input($user_id);?>
      <p><button type="submit" name="submit" class="btn btn-large"><i class="icon-edit"></i>  Change</button></p>
	  <p><?php echo form_submit('submit', 'Change');?></p>
      
<?php echo form_close();?>
