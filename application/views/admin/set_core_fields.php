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
				<li class="active">Set Core Fields</li>
			</ul>  
		</div>  
	</div>

				<div class="span6">
					<div class="pagination-centered" >
						<h3>Set Core Fields</h3>
						<!--<p>Settings are automatically updated when adjusted.</p>-->
						<p>This page allows you to specify what core fields should be present in the import templates when they are generated.</p>
						<?php if ( isset($success_message) ) { echo "<div id='success-alert' class='alert alert-info'><button type='button' class='close' data-dismiss='alert'>&times;</button><h4>Settings were successfully updated!</h4></div>"; } ?>
						<strong><?php echo validation_errors(); ?></strong>
					</div>
					<div class="row-fluid">
						<?php $attributes = array('id' => 'set_core_fields_form'); echo form_open('admin/set_core_fields', $attributes); ?>
						<div class="pagination-centered" ><button type="submit" name="submit" class="btn btn-primary"><i class="icon-edit icon-white"></i>  Set Core Fields</button></div><hr>
						<table class="table table-bordered table-striped table-hover" id="settingstable">
							<thead>
								<tr>
									<th>Field Name</th>
									<th>Setting</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ( $all_fields as $field ): ?>
								<?php if ( in_array($field, $this->config->item('protected_fields') ) ) { continue; } //Skip fields that are protected (defined in cafevariome config file ?>
								<tr>
									<td><?php echo $field; ?></td>
									<td>
										<?php if ( array_key_exists($field, $core_fields) ): ?>
											<div class="slider core_fields_slider" >
												<input id="<?php echo $field; ?>" name="<?php echo $field; ?>" type="checkbox" checked>
											</div>
										<?php else: ?>
											<div class="slider core_fields_slider" >												
												<input id="<?php echo $field; ?>" name="<?php echo $field; ?>" type="checkbox" unchecked>
											</div>
										<?php endif; ?>
									</td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
						<div class="pagination-centered" >
							<br />
							<button type="submit" name="submit" class="btn btn-primary"><i class="icon-edit icon-white"></i>  Set Core Fields</button>
							<!--<button id="settings_default" class="btn btn-small btn-info"><i class="icon-refresh"></i>  Reset to default</button>-->
						</div>
						</form>
                    </div>
				</div>


		</div>
	</div>
</div><!--/.fluid-container-->