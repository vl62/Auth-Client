<div class="container">
	<div class="row">  
		<div class="span6">  
			<ul class="breadcrumb">  
				<li>  
					<a href="<?php echo base_url() . "admin";?>">Dashboard Home</a> <span class="divider">></span>  
				</li>
				<li>
					<a href="<?php echo base_url() . "admin/settings";?>">Settings</a> <span class="divider">></span>
				</li>
				<li class="active">Edit Field</li>
			</ul>  
		</div>  
	</div>
	<div class="row-fluid">
		<div class="span10 offset1 pagination-centered">
			<div class="well">
			<h1>Edit Field</h1>
			<p>Please enter the source information below.</p>
			<div id="infoMessage"><strong><?php echo $message; ?></strong></div>
			<?php
				$hidden = array('name' => $field_name);
				echo form_open("admin/edit_source", '', $hidden);
			?>
			<p>
				Field Name: <br />
				<div class="input-prepend">
					<span class="add-on"><a data-toggle="modal" data-backdrop="false" rel="popover" data-content="Specify the name of the database field (must not contain spaces)" data-original-title="Field Name" ><i class="icon-question-sign"></i></a></span>
					<?php echo form_input($name); ?>
				</div>
			</p>
			<p>
				Field Type: <br />
				<div class="input-prepend">
					<span class="add-on"><a data-toggle="modal" data-backdrop="false" rel="popover" data-content="Select the database field type from the dropdown (you must also specify a length if it's a type that requires this parameter e.g. INT)" data-original-title="Field Type" ><i class="icon-question-sign"></i></a></span>
					<?php
					$options = array(
						'VARCHAR' => 'VARCHAR',
						'INT' => 'INT',
						'TEXT' => 'TEXT',
						'TINYINT' => 'TINYINT',
						'SMALLINT' => 'SMALLINT',
						'MEDIUMINT' => 'MEDIUMINT',
						'BIGINT' => 'BIGINT',
						'FLOAT' => 'FLOAT',
						'DOUBLE' => 'DOUBLE',
						'DECIMAL' => 'DECIMAL',
						'ENUM' => 'ENUM',
						'BOOL' => 'BOOL'
					);
					echo form_dropdown('type', $options, $field_type);
					?>
				</div>
				
			</p>
			<p>
				Length/Value: <br />
				<div class="input-prepend">
					<span class="add-on"><a data-toggle="modal" data-backdrop="false" rel="popover" data-content="Specify the field length if it's a type that requires this information (e.g. INT, VARCHAR etc)" data-original-title="Field Length" ><i class="icon-question-sign"></i></a></span>
					<?php echo form_input($length); ?>
				</div>
			</p>
			<!--<p>-->
				<!--Is Displayed: <br />-->
				<?php
//				$options = array(
//					'yes' => 'Yes',
//					'no' => 'No',
//				);
//				echo form_dropdown('is_displayed', $options, $is_displayed_val);
				?>
			<!--</p>-->			
			<p><button type="submit" name="submit" class="btn btn-primary"><i class="icon-file"></i>  Save Field</button><?php echo nbs(6); ?><a href="<?php echo base_url() . "admin/settings";?>" class="btn" ><i class="icon-step-backward"></i> Go back</a></p>
		
			</div>
		</div>
	</div>
</div>
<?php echo form_close(); ?>