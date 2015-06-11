<div class="container">
	<div class="row">  
		<div class="span6">  
			<ul class="breadcrumb">  
				<li>  
					<a href="<?php echo base_url() . "curate";?>">Curator Dashboard</a> <span class="divider">></span>  
				</li>
				<li>
					<a href="<?php echo base_url() . "curate/sources";?>">Sources</a> <span class="divider">></span>
				</li>
				<li class="active">Edit Source</li>
			</ul>  
		</div>  
	</div>
	<div class="row-fluid">
		<div class="span10 offset1 pagination-centered">
			<div class="well">
			<h1>Edit Source</h1>
			<p>Please enter the source information below.</p>
			<div id="infoMessage"><strong><?php echo $message; ?></strong></div>
			<?php
				$hidden = array('source_id' => $source_id);
				echo form_open("curate/edit_source", '', $hidden);
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
				Source URI: <br />
				<?php echo form_input($uri); ?>
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
				Edit Groups (control click to select multiple): <br />
				<?php 
//				echo form_multiselect('groups[]', $groups, set_multiselect( 'groups', $selected_groups ) );
				?>
				<select name="groups[]"  multiple="multiple">
					<?php foreach ( $groups as $group_id => $group_description ): ?>
					<option value="<?php echo $group_id; ?>" <?php if (array_key_exists($group_id, $selected_groups)) { echo 'selected="selected"'; } ?>><?php echo $group_description; ?></option>
					<?php endforeach; ?>
				</select>						   
			</p>
			
			<p><button type="submit" name="submit" class="btn btn-primary"><i class="icon-file"></i>  Save Source</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo base_url() . "curate/sources";?>" class="btn" ><i class="icon-step-backward"></i> Go back</a></p>
		
			</div>
		</div>
	</div>
</div>
<?php echo form_close(); ?>