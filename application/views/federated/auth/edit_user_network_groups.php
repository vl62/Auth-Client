<div class="container">
	<!--<div class="container-fluid">-->
	<div class="row">  
		<div class="span6">  
			<ul class="breadcrumb">  
				<li>  
					<a href="<?php echo base_url() . "admin";?>">Dashboard Home</a> <span class="divider">></span>  
				</li>
				<li>
					<a href="<?php echo base_url() . "auth_federated/users";?>">Users</a> <span class="divider">></span>
				</li>
				<li class="active">Edit User Network Groups</li>
			</ul>  
		</div>  
	</div>
	<div class="row-fluid">
		<div class="span10 offset1 pagination-centered">
			<div class="well">
				<h2>Edit User Network Groups</h2>

				<p>Please enter the users information below.</p>
                                
				<strong id="editUserError" class="hide" style="color: red;"></strong>
				<?php echo form_open("auth_federated/edit_user_network_groups", array('name' => 'editUser')); ?>
	
				<?php echo form_hidden(array('installation_key' => $this->config->item('installation_key'))); ?>	
				<p>
					<?php if (isset($groups)): ?>
					<?php error_log(print_r($groups, 1)); ?>
					Assign user to groups that given them openAccess to restrictedAccess records<br />(control click to select multiple): <br />
                                
                                <?php 
						$group_count = count($groups) + 1;
//						$curator_count = count($users) + 1;
					?>
					<select size="<?php echo $group_count; ?>" name="groups[]"  multiple="multiple">
						<?php foreach ($groups as $group ): ?>
							<option value="<?php echo $group['id'] . "," . $group['network_key'] . ""; ?>" <?php if (isset($selected_groups)) { if (array_key_exists($group['id'], $selected_groups)) { echo 'selected="selected"'; }} ?>><?php echo $group['description'] . " (Network:" . $group['network_name'] . ")"; ?></option>
						<?php endforeach; ?>
					</select>
                                        
                                <h3>Master Network Groups</h3>
                                <div class="row-fluid">
                                    <div class="span5 pagination-centered">
                                        <select size="5" multiple id="mng_left">
                                        </select>
                                    </div>
                                    <div class="span2 pagination-centered">
                                        <br><input type="button" value="&gt;&gt;"/><br><br>
                                        <input type="button" value="&lt;&lt;"/>
                                    </div>
                                    <!--<h5>SELECTED</h5>-->
                                    <div class="span5 pagination-centered">
                                        <select size="5" multiple id="mng_right">
                                        </select>
                                    </div>
                                </div>
                                
                                <h3>Source Display Groups</h3>
                                <div class="row-fluid">
                                    <div class="span5 pagination-centered">
                                        <select size="5" multiple id="sdg_left">
                                        </select>
                                    </div>
                                    <div class="span2 pagination-centered">
                                        <br><input type="button" value="&gt;&gt;"/><br><br>
                                        <input type="button" value="&lt;&lt;"/>
                                    </div>
                                    <div class="span5 pagination-centered">
                                        <select size="5" multiple id="sdg_right">
                                        </select>
                                    </div>
                                </div>
                                
                                <h3>Count Display Groups</h3>
                                <div class="row-fluid">
                                    <div class="span5 pagination-centered">
                                        <select size="5" multiple id="cdg_left">
                                        </select>
                                    </div>
                                    <div class="span2 pagination-centered">
                                        <br><input type="button" value="&gt;&gt;"/><br><br>
                                        <input type="button" value="&lt;&lt;"/>
                                    </div>
                                    <div class="span5 pagination-centered">
                                        <select size="5" multiple id="cdg_left">
                                        </select>
                                    </div>
                                </div>
                                
                                <script>
                                <?php foreach ($groups as $group ): ?>
                                        <?php if($group['group_type'] === "master"):?>
                                            <?php if(array_key_exists($group['id'], $selected_groups)): ?> 
                                                $("#mng_right").append($("<option></option>")
                                                .attr("value",'<?php echo $group['id'] . "," . $group['network_key'] . ""; ?>')
                                                .text('<?php echo $group['description'] . " (Network:" . $group['network_name'] . ")"; ?>')); 
                                            <?php else: ?>
                                                $("#mng_left").append($("<option></option>")
                                                .attr("value",'<?php echo $group['id'] . "," . $group['network_key'] . ""; ?>')
                                                .text('<?php echo $group['description'] . " (Network:" . $group['network_name'] . ")"; ?>')); 
                                            <?php endif; ?>
                                        <?php elseif ($group['group_type'] === "source_display"): ?>
                                            <?php if(array_key_exists($group['id'], $selected_groups)): ?> 
                                                $("#sdg_right").append($("<option></option>")
                                                .attr("value",'<?php echo $group['id'] . "," . $group['network_key'] . ""; ?>')
                                                .text('<?php echo $group['description'] . " (Network:" . $group['network_name'] . ")"; ?>')); 
                                            <?php else: ?>
                                                $("#sdg_left").append($("<option></option>")
                                                .attr("value",'<?php echo $group['id'] . "," . $group['network_key'] . ""; ?>')
                                                .text('<?php echo $group['description'] . " (Network:" . $group['network_name'] . ")"; ?>')); 
                                            <?php endif; ?>
                                        <?php elseif ($group['group_type'] === "count_display"): ?>    
                                            <?php if(array_key_exists($group['id'], $selected_groups)): ?> 
                                                $("#cdg_right").append($("<option></option>")
                                                .attr("value",'<?php echo $group['id'] . "," . $group['network_key'] . ""; ?>')
                                                .text('<?php echo $group['description'] . " (Network:" . $group['network_name'] . ")"; ?>')); 
                                            <?php else: ?>
                                                $("#cdg_left").append($("<option></option>")
                                                .attr("value",'<?php echo $group['id'] . "," . $group['network_key'] . ""; ?>')
                                                .text('<?php echo $group['description'] . " (Network:" . $group['network_name'] . ")"; ?>')); 
                                            <?php endif; ?>
                                        <?php endif; ?>
                                <?php endforeach; ?>
                                    </script>
                                
					<?php else: ?>
					<p><span class="label label-important">There are no network groups available to this installation. <br />A user will not be able to log in until they been assigned to at least one group.</span></p>
					<?php endif; ?>
				</p>
                <br /><br /><br />	
				<?php echo form_hidden('id', $user_id); ?>
				<?php echo form_hidden($csrf); ?>
				<p><button type="submit" onclick="edit_user_network_groups();" name="submit" class="btn btn-primary"><i class="icon-user"></i>  Save</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo base_url() . "auth_federated/users"; ?>" class="btn" ><i class="icon-step-backward"></i> Go back</a></p>
			</div>
		</div>
	</div>
</div>



<?php echo form_close(); ?>