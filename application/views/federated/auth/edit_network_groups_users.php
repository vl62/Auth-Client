<div class="container">
	<!--<div class="container-fluid">-->
	<div class="row">  
		<div class="span6">  
			<ul class="breadcrumb">  
				<li>  
					<a href="<?php echo base_url() . "admin";?>">Dashboard Home</a> <span class="divider">></span>  
				</li>
				<li>
					<a href="<?php echo base_url() . "groups";?>">Groups</a> <span class="divider">></span>
				</li>
				<li class="active">Edit User Network Groups</li>
			</ul>  
		</div>  
	</div>
	<div class="row-fluid">
		<div class="span10 offset1 pagination-centered">
			<div class="well">
				<h2>Edit Users for Network Group</h2>

				<?php echo form_open("auth_federated/edit_user_network_groups", array('name' => 'editUser')); ?>
	
				<?php echo form_hidden(array('installation_key' => $this->config->item('installation_key'))); ?>	

                    <?php if(isset($users) || isset($group_users)): ?>
                                
                        <div class="row-fluid">
                            <div class="span5 pagination-centered">
                                <select size="10" multiple id="mng_left">
                                </select>
                            </div>
                            <div class="span2 pagination-centered">
                                <br><input type="button" value="&gt;&gt;"/><br><br>
                                <input type="button" value="&lt;&lt;"/>
                            </div>
                            <div class="span5 pagination-centered">
                                <select size="10" multiple id="mng_right" name="groups[]" class="groupsSelected">
                                </select>
                            </div>
                        </div>

                        <?php if(isset($users)) : foreach ($users as $user): ?>
                                <script type="text/javascript">
                                    $("#mng_left").append($("<option></option>")
                                    .attr("value",'<?php echo $user['id']; ?>')
                                    .text('<?php echo $user['username']; ?>')); 
                                </script>
                        <?php endforeach; endif; ?>

                        <?php if(isset($group_users)) : foreach ($group_users as $group_user): ?>
                                <script type="text/javascript">
                                    $("#mng_right").append($("<option></option>")
                                    .attr("value",'<?php echo $group_user['id']; ?>')
                                    .text('<?php echo $group_user['username']; ?>')); 
                                </script>
                        <?php endforeach; endif;?>

                    <?php else: ?>
                        <p><span class="label label-important">There are no users present in the master network group.</span></p>
                    <?php endif; ?>
            
                        <br /><br /><br />	

                <?php echo form_hidden('isMaster', $isMaster); ?>                
				<?php echo form_hidden('id', $user_id); ?>
				<?php echo form_hidden($csrf); ?>
				<p><button type="submit" onclick="edit_user_network_groups();" name="submit" class="btn btn-primary"><i class="icon-user"></i>  Save</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo base_url() . "groups"; ?>" class="btn" ><i class="icon-step-backward"></i> Go back</a></p>
			</div>
		</div>
	</div>
</div>



<?php echo form_close(); ?>