<div class="container">
	<div class="row">  
		<div class="span6">  
			<ul class="breadcrumb">  
				<li>  
					<a href="<?php echo base_url() . "admin";?>">Dashboard Home</a> <span class="divider">></span>  
				</li>
				<li>
					<a href="<?php echo base_url() . "sources";?>">Sources</a> <span class="divider">></span>
				</li>
				<li class="active">Add Source</li>
			</ul>  
		</div>  
	</div>
	<div class="row-fluid">
		<div class="span9 offset2 pagination-centered">
			<div class="well">
				<h2>Add Source</h2>
				<p>Please enter the source information below.</p>
				<b><strong><?php echo validation_errors(); ?></strong></b>
				<?php echo form_open("sources/add_source"); ?>
				<p>
					Source Name:<br />
					<?php echo form_input($name); ?><br />
					<small>(no spaces allowed but underscores and dashes are accepted, <br />uppercase characters will be converted to lowercase)</small>
				</p>
				<p></p>
				<p>
					Owner Name: <br />
					<?php echo form_input($owner_name); ?>
				</p>

				<p>
					Owner Email: <br />
					<?php echo form_input($email); ?>
				</p>

				<p>
					Source URI: <br />
					<?php echo form_input($uri); ?>
				</p>

				<p>
					Source Description: <br />
					<?php echo form_input($desc); ?>
				</p>

				<p>
					Long Source Description: <br />
					<?php echo form_textarea($long_description); ?>
				</p>

				<p>
					Status: <br />
					<?php
					$options = array(
						'online' => 'Online',
						'offline' => 'Offline',
					);
					echo form_dropdown('status', $options, 'mysql');
					?>
				</p>

				<p>
					<!--Source Type: <br />-->
					<?php
//					$options = array(
//						'varioml' => 'VarioML',
//						'mysql' => 'MySQL',
//						'vcf' => 'Variant Call Format',
//					);
//					echo form_dropdown('type', $options, 'mysql', 'disabled="disabled"');
					?>
				</p>
				<?php if (array_key_exists('error', $groups)): ?>
				<p><span class="label label-important">There are no network groups available to this installation. <br /></span></p>
				<?php else: ?>
				<p>
					Select groups that can access restrictedAccess variants in this source (control click to select multiple): <br />
					<?php
						$group_count = count($groups) + 1;
//						$curator_count = count($users) + 1;
					?>
					<select size="<?php echo $group_count; ?>" name="groups[]"  multiple="multiple">
						<?php foreach ($groups as $group ): ?>
							<?php
								// Skip if it's a master group as we don't want users assigning master groups to sources (only users)
								if ($group['group_type'] == 'master') {
									continue;
								}
							?>
							<option value="<?php echo $group['id'] . "," . $group['network_key']; ?>"><?php echo $group['description'] . " (Network:" . $group['network_name'] . ")"; ?></option>
						<?php endforeach; ?>
					</select>						   
				</p>
				<?php endif; ?>
<!--				<p>
					Edit Curators (control click to select multiple): <br />
					<select size="<?php // echo $curator_count; ?>" name="curators[]"  multiple="multiple">
						<?php // foreach ($users as $k => $user): ?>
							<option value="<?php // echo $user->id; ?>" ><?php // echo $user->username; ?></option>
						<?php // endforeach; ?>
					</select>						   
				</p>-->
				<p><button type="submit" name="submit" class="btn btn-primary"><i class="icon-file icon-white"></i>  Add Source</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo base_url() . "admin"; ?>" class="btn" ><i class="icon-step-backward"></i> Go back</a></p>
				<?php 
				if (isset($result)) {
					echo "<p>Source was successfully added</p>";
				}
				?>
			</div>
		</div>
	</div>
</div>
<?php echo form_close(); ?>
