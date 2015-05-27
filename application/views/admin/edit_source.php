<div class="container">
	<div class="row">  
		<div class="span6">  
			<ul class="breadcrumb">  
				<li>  
					<a href="<?php echo base_url() . "admin";?>">Dashboard Home</a> <span class="divider">></span>  
				</li>
				<li>
					<a href="<?php echo base_url() . "admin/sources";?>">Sources</a> <span class="divider">></span>
				</li>
				<li class="active">Edit Source</li>
			</ul>  
		</div>  
	</div>
	<div class="row-fluid">
		<div class="span10 offset1 pagination-centered">
			<div class="well">
			<h2>Edit Source</h2>
			<p>Please enter the source information below.</p>
			<div id="infoMessage"><strong><?php echo $message; ?></strong></div>
			<?php
				$hidden = array('source_id' => $source_id);
				echo form_open("admin/edit_source", '', $hidden);
			?>
			<p>
				Source Name: <br />
				<?php echo form_input($name); ?>
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
				Owner Email: <br />
				<?php echo form_input($email); ?>
			</p>
			<p>
				Source URI: <br />
				<?php echo form_input($uri); ?>
			</p>
			<p>
				Source Type: <br />
				<?php
				$options = array(
					'varioml' => 'VarioML',
					'mysql' => 'MySQL',
					'vcf' => 'Variant Call Format',
				);
				echo form_dropdown('type', $options, $source_data['type'], 'disabled="disabled"');
				?>
			</p>

			<p>
				Status: <br />
				<?php
				$options = array(
					'online' => 'Online',
					'offline' => 'Offline',
				);
				echo form_dropdown('status', $options, $source_data['status']);
				?>
			</p>

			<p>
				Edit Groups Allowed to Access restrictedAccess Variants<br />(control click to select multiple): <br />
				<?php 
					$group_count = count($groups) + 1;
					$curator_count = count($users) + 1;
				?>
				<select size="<?php echo $group_count; ?>" name="groups[]"  multiple="multiple">
					<?php foreach ( $groups as $group_id => $group_description ): ?>
					<option value="<?php echo $group_id; ?>" <?php if (array_key_exists($group_id, $selected_groups)) { echo 'selected="selected"'; } ?>><?php echo $group_description; ?></option>
					<?php endforeach; ?>
				</select>						   
			</p>
			
			<p>
				Edit Curators (control click to select multiple): <br />
				<select size="<?php echo $curator_count; ?>" name="curators[]"  multiple="multiple">
					<?php foreach ($users as $k => $user): ?>
						<option value="<?php echo $user->id; ?>" <?php if (array_key_exists($user->id, $selected_curators)) { echo 'selected="selected"'; } ?>><?php echo $user->username; ?></option>
					<?php endforeach; ?>
				</select>						   
			</p>
			
			<p><button type="submit" name="submit" class="btn btn-primary"><i class="icon-file"></i>  Save Source</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo base_url() . "admin/sources";?>" class="btn" ><i class="icon-step-backward"></i> Go back</a></p>
		
			</div>
		</div>
	</div>
</div>
<?php echo form_close(); ?>