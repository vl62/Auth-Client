<?php
// Get which sharing policy for display fields was clicked last from session and make the div for the table for this sharing policy visible
$selected_sharing_policy = $this->session->userdata('sharing_policy');
if ( $selected_sharing_policy ):
?>
<script>
$(document).ready(function() {
    $('.visiblenamevalue').editable();
	$("#<?php echo $selected_sharing_policy; ?>").show();
});	
</script>
<?php else: ?>
<script>
$(document).ready(function() {
    $('.visiblenamevalue').editable();
    $("#openAccess").show();
});	
</script>
<?php endif; ?>

<div class="container">
	<div class="row">  
		<div class="span6">  
			<ul class="breadcrumb">  
				<li>  
					<a href="<?php echo base_url() . "admin";?>">Dashboard Home</a> <span class="divider">></span>  
				</li>
				<li class="active">Settings</li>
			</ul>  
		</div>  
	</div>
	<div class="page-header">
		<h3>Settings</h3>
	</div>
	<div class="tabbable">
		<ul class="nav nav-tabs">
			<li <?php if ( $this->session->userdata('settings_tab') === "settings" ) { echo "class='active'"; } ?>><a href="#settings" data-toggle="tab">Core Settings</a></li>
			<li <?php if ( $this->session->userdata('settings_tab') === "templates" ) { echo "class='active'"; } ?>><a href="#templates" data-toggle="tab">Import Templates</a></li>
			<?php if ( $this->config->item('database_structure') ): ?>
			<li <?php if ( $this->session->userdata('settings_tab') === "database" ) { echo "class='active'"; } ?>><a href="#database" data-toggle="tab">Database Structure</a></li>
			<?php endif; ?>
			<!--<li><a href="#ids" data-toggle="tab">Variant IDs</a></li>-->
			<li <?php if ( $this->session->userdata('settings_tab') === "maintenance" ) { echo "class='active'"; } ?>><a href="#maintenance" data-toggle="tab">Maintenance</a></li>
			<?php if ( $this->config->item('federated') && $this->config->item('federated_head') ): ?>
			<li <?php if ( $this->session->userdata('settings_tab') === "fed" ) { echo "class='active'"; } ?>><a href="#fed" data-toggle="tab">Federated</a></li>
			<?php endif; ?>
			<li <?php if ( $this->session->userdata('settings_tab') === "fields" ) { echo "class='active'"; } ?>><a href="#fields" data-toggle="tab">Display Fields</a></li>
			<li <?php if ( $this->session->userdata('settings_tab') === "beacon" ) { echo "class='active'"; } ?>><a href="#beacon" data-toggle="tab">Beacon</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane<?php if ( $this->session->userdata('settings_tab') === "settings" ) { echo " active"; } ?>" id="settings">
				<div class="span6">
					<div class="pagination-centered" >
						<h4>Core Settings</h4>
						<!--<p>Settings are automatically updated when adjusted.</p>-->
						<?php if ( isset($success_message) ) { echo "<div id='success-alert' class='alert alert-info'><button type='button' class='close' data-dismiss='alert'>&times;</button><h4>Settings were successfully updated!</h4><br /><p><a href='#' onclick='location.reload(true); return false;'>Click here</a> to refresh the page and ensure all changes take effect.</p></div>"; } ?>
						<strong><?php echo validation_errors(); ?></strong>
					</div>
					<div class="row-fluid">
						<?php $attributes = array('id' => 'settings_form'); echo form_open('admin/settings', $attributes); ?>
						<div class="pagination-centered" ><button type="submit" name="submit" class="btn btn-primary"><i class="icon-list icon-white"></i>  Save settings</button></div><hr>
						<table class="table table-bordered table-striped table-hover" id="settingstable">
							<thead>
								<tr>
									<th>Info</th>
									<th>Name</th>
									<th>Setting</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ( $settings as $setting ): ?>
									<?php // if ( $setting->name == "cvid_prefix" ){ continue; } // Don't let the user change the prefix after it has been set during installation - needs to be unique across all installs ?>
								<tr>
									<td><button class="btn btn-small btn-info" rel="popover" data-content="<?php echo $setting->info; ?>" data-original-title="<?php echo $setting->name . " settings help"; ?>"><i class="icon-question-sign"></i></button></td>
									<td><?php echo $setting->name; ?></td>
									<td>
										<?php if ( $setting->value == "on"): ?>
											<div class="slider settings_slider" >
												<input id="<?php echo $setting->name; ?>" name="<?php echo $setting->name; ?>" type="checkbox" checked>
											</div>
										<?php elseif ( $setting->value == "off" ): ?>
											<div class="slider settings_slider" >
												
												<input id="<?php echo $setting->name; ?>" name="<?php echo $setting->name; ?>" type="checkbox" unchecked>
											</div>
										<?php else: ?>
											<input type="text" id="<?php echo $setting->name; ?>" name="<?php echo $setting->name; ?>" value="<?php echo $setting->value; ?>" style="width:300px;"/>
										<?php endif; ?>
									</td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
						<div class="pagination-centered" >
							<br />
							<button type="submit" name="submit" class="btn btn-primary"><i class="icon-list icon-white"></i>  Save settings</button><?php echo nbs(5); ?><!-- <button id="settings_default" class="btn btn-small btn-info"><i class="icon-refresh"></i>  Reset to default</button> -->
						</div>
						</form>
                    </div>
				</div>
			</div>
			<div class="tab-pane<?php if ( $this->session->userdata('settings_tab') === "templates" ) { echo " active"; } ?>" id="templates">
				<div class="span6">
					<div class="pagination-centered" >
						<h4>Generate Import Templates</h4>
					</div>
					<div class="row-fluid">
						<p>Use the buttons below to generate import templates that can be populated with your data and then imported using the bulk variant import interface.</p>
						<!--<p>"Full" templates represent your current database structure and contains all available fields.</p>-->
						<p>Import templates contain core fields from the database that the user wishes to populate with their data. </p>
						<p>Core fields present in the import templates can be changed below by clicking on the "Set Core Fields" button. N.B. the column names in the template files should NOT be changed.</p>
						<hr>
						<div class="pagination-centered"><a href="<?php echo base_url() . "admin/set_core_fields";?>" class="btn btn-primary" rel="popover" data-content="Set which fields will be present in your import templates." data-original-title="Set Core Fields"><i class="icon-edit icon-white"></i>  Set Core Fields</a></div>
						<hr>
						<div class="pagination-centered">
							<a href="<?php echo base_url() . "admin/create_excel_sheet_core";?>" class="btn" rel="popover" data-content="Generates a Excel document with the specified core fields. DO NOT edit the column names in the Excel sheet." data-original-title="Excel Template"><i class="icon-list-alt"></i>  Generate Excel</a>
							<?php echo nbs(5); ?>
							<a href="<?php echo base_url() . "admin/create_tab_delimited_core";?>" class="btn" rel="popover" data-content="Generates a tab-delimited text file with the specified core fields. DO NOT edit the column names." data-original-title="Tab Delimited Template"><i class="icon-list-alt"></i>  Generate Tab Delimited</a>
						</div>
						<?php echo br(2); ?>
					</div>
				</div><!--/row-->
				<br />
			</div>
			<?php if ( $this->config->item('database_structure') ): ?>
			<div class="tab-pane <?php if ( $this->session->userdata('settings_tab') === "database" ) { echo " active"; } ?>" id="database">
				<div class="row-fluid"></div><!--/row-->
				<div class="row-fluid">
					<div class="span8">
						<div class="pagination-centered" >
							<h4>Table Structure for <?php echo $this->config->item('feature_table_name'); ?></h4>
						</div>
						<p>It is NOT recommended to modify database fields unless you are absolutely sure you wish to do so. Deleting a database field will result in deletion of the corresponding data for ALL records in the table.</p>
						<div class="span7 offset2 pagination-centered">
							<a href="<?php echo base_url() . "admin/add_db_field"; ?>" class="btn btn-small btn-primary" ><i class="icon-plus icon-white"></i> Add Field</a>
						</div>
						<table class="table table-bordered table-striped table-hover" id="dbstructuretable">
							<thead>
								<tr>
									<th>Name</th>
									<th>Type</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php $c = 0; ?>
								<?php foreach ($table_structure as $fields): ?>
								<?php if ( in_array($fields['name'], $this->config->item('protected_fields') ) ) { continue; } //Skip fields that are protected (defined in cafevariome config file ?>
								<tr>
									<td><?php echo $fields['name']; ?></td>
									<td><?php echo $fields['type']; ?></td>
									<td><a href="<?php echo base_url('admin/edit_db_field') . "/" . $fields['name']; ?>" rel="popover" data-content="Edit the structure of field" data-original-title="Edit Structure"><i class="icon-edit"></i></a><?php echo nbs(3); ?><a href="<?php echo base_url('admin/delete_db_field') . "/" . $fields['name']; ?>" rel="popover" data-content="Delete this field." data-original-title="Delete Field"></i><i class="icon-trash"></i></a></td>
								</tr>
							<?php endforeach; ?>
							</tbody>
						</table>
						<br />
					</div>
				</div>
			</div>
			<?php endif; ?>
			<div class="tab-pane<?php if ( $this->session->userdata('settings_tab') === "maintenance" ) { echo " active"; } ?>" id="maintenance">
				<ul class="nav nav-tabs">
					<li <?php if ( $this->session->userdata('maintenance_tab') === "regenerate" ) { echo "class='active'"; } ?>><a href="#regenerate" data-toggle="tab">Manual</a></li>
					<li <?php if ( $this->session->userdata('maintenance_tab') === "cron" ) { echo "class='active'"; } ?>><a href="#cron" data-toggle="tab">Automated</a></li>
				</ul>
				<div class="tab-content">
					<div id="regenerate" class="tab-pane<?php if ( $this->session->userdata('maintenance_tab') === "regenerate" ) { echo " active"; } ?>">
						<div class="span4 pagination-centered">
							<h4>Manual Maintenance</h4>
						</div>
						<br />
						<br />
						<div class="span4 pagination-centered">
							<a onclick="regenerateAutocomplete();" class="btn" rel="popover" data-content="Click to regenerate a non-redundant list of autocomplete terms in your database (references, HGVS, genes, phenotypes). These terms will then be used for autocompletion in the main discovery search box. This should be done after importing new data." data-original-title="Regenerate autocomplete"><i class="icon-list-alt"></i>  Regenerate Autocomplete</a>
						</div>
                                                <?php if(false) { 
						echo '<br />
						<br />
						<br />
						<div class="span4 pagination-centered">
							<a onclick="regenerateFederatedPhenotypeAttributesAndValues();" class="btn" rel="popover" data-content="Click to regenerate a non-redundant list of phenotype attributes and top 50 phenotype values from installations in your network which can be used in the query builder interface." data-original-title="Regenerate Federated Phenotype List"><i class="icon-list-alt"></i>  Regenerate Federated Phenotype List</a>
						</div>';
                                                }?>
						<br />
						<br />
						<br />
						<div class="span4 pagination-centered"><a onclick="regenerateOntologyDAG();" class="btn" rel="popover" data-content="Click to regenerate the ontology tree for phenotypes that is used in the phenotype tree search in the main discovery interface. This should be done after importing new data." data-original-title="Regenerate Ontology Tree"><i class="icon-list-alt"></i>  Regenerate Ontology Tree</a></div>
						<br />
						<br />
						<br />
						<?php if (isset($is_elastic_search_running)): ?>
							<div class="span4 pagination-centered"><a onclick="regenerateElasticSearchIndex();" class="btn" rel="popover" data-content="Click to regenerate the ElasticSearch index for all variants. This should be done after importing new data." data-original-title="Regenerate ElasticSearch Index"><i class="icon-list-alt"></i>  Regenerate ElasticSearch Index</a></div>
						<?php else: ?>
							<div class="span4 pagination-centered"><button type="button" class="btn disabled">ElasticSearch is not running</button></div>
							<div class="span3 pagination-centered"><a onclick="startElasticSearch();" class="btn" rel="popover" data-content="Click to try starting the ElasticSearch server." data-original-title="Start ElasticSearch"><i class="icon-list-alt"></i>  Start ElasticSearch</a></div>
						<?php endif; ?>
						<br />
						<br />
						<div id="waiting" class="span2 offset1 pagination-centered" style="display: none;">
							<!--Regenerating autocomplete...<br />-->
							<img src="<?php echo base_url("resources/images/cafevariome/ajax-loader-alt.gif"); ?>" title="Loader" alt="Loader" />
							<br /><br />
						</div>
						<br />
						<div class="row"><div class="span4 pagination-centered"><p>N.B. These operations may take some time if your database is very large. You may automatically run these tasks daily as a background cron job by enabling the setting in the automated tab.</p></div></div>
						<br />
						<hr>
					</div>
					<div id="cron" class="tab-pane<?php if ( $this->session->userdata('maintenance_tab') === "cron" ) { echo " active"; } ?>">
						<div class="span4 pagination-centered">
							<h4>Automated Maintenance</h4>
							<p>This option allows you to schedule a daily automated cron job to run all manual maintenance actions in the background.</p>
							<p>N.B. The resources/cron directory must be writable by the webserver</p>
							<br />
							<div class="slider cron_maintenance_slider" >
								<p>
									<?php if (isset($is_maintenance_cron_enabled)): ?>
									<input type="checkbox" name="cron_maintenance" id="cron_maintenance_enabled" class="cronenabled-crondisabled" checked/>
									<?php else: ?>
									<input type="checkbox" name="cron_maintenance" id="cron_maintenance_disabled" class="cronenabled-crondisabled" />
									<?php endif; ?>
								</p>
								<p>&nbsp;</p>
								<p>&nbsp;</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php if ( $this->config->item('federated') && $this->config->item('federated_head') ): ?>
			<div class="tab-pane<?php if ( $this->session->userdata('settings_tab') === "fed" ) { echo " active"; } ?>" id="fed">
				<div class="row-fluid">
					<div class="span10">
						<div class="pagination-centered" >
							<h4>Federated Node Setup</h4>
							<a href="<?php echo base_url() . "admin/add_node"; ?>" class="btn btn-small btn-primary" rel="popover" data-content="Add a node to the federated list. N.B. The new node will be propagated to all other nodes in the list." data-original-title="Add Node" ><i class="icon-plus icon-white"></i> Add Node</a><?php if ( !empty($node_list) ): ?><?php echo nbs(6); ?><a href="<?php echo base_url() . "admin/refresh_node_list"; ?>" class="btn btn-small btn-primary" rel="popover" data-content="Re-propagate the node list across all nodes (not usually necessary)." data-original-title="Refresh Nodes" ><i class="icon-refresh icon-white"></i> Refresh Nodes</a><?php echo nbs(6); ?><a href="<?php echo base_url() . "admin/add_federated_source"; ?>" class="btn btn-small btn-primary" rel="popover" data-content="Add sources from federated nodes (also possible via Sources admin page." data-original-title="Add Federated Source" ><i class="icon-plus icon-white"></i> Add Federated Source</a><?php endif; ?>
							<hr>
							<table class="table table-bordered table-striped table-hover" id="federatedtable">
								<thead>
									<tr>
										<th>Name</th>
										<th>URI</th>
										<th>Key</th>
										<th>Status</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ( $node_list as $node ): ?>
									<tr>
										<td><?php echo $node['node_name']; ?></td>
										<td><?php echo $node['node_uri']; ?></td>
										<td><?php echo $node['node_key']; ?></td>
										<td><?php if ( $node_statuses[$node['node_name']] ): ?><a class="btn btn-success btn-small" href="#" rel="popover" data-content="The node was successfully pinged." data-original-title="Node Up" ><i class="icon-thumbs-up"></i></a><?php else: ?><a class="btn btn-danger btn-small" href="#" rel="popover" data-content="There was a problem when pinging this node." data-original-title="Node Down" ><i class="icon-thumbs-down icon-white"></i></a><?php endif; ?></td>
										<td><a href="<?php echo base_url('admin/delete_node') . "/" . $node['node_name']; ?>" rel="popover" data-content="Click to delete the node. N.B. the deletion will be propagated across all nodes)." data-original-title="Delete Node"></i><i class="icon-trash"></i></a></td>
									</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<?php endif; ?>
			<!--<div id="fieldsdiv">-->
			<div class="tab-pane<?php if ( $this->session->userdata('settings_tab') === "fields" ) { echo " active"; } ?>" id="fields">
				<ul class="nav nav-tabs">
					
					<li <?php if ( $this->session->userdata('fields_tab') === "search_result" ) { echo "class='active'"; } ?>><a href="#search_result" data-toggle="tab">Search Results</a></li>
					<li <?php if ( $this->session->userdata('fields_tab') === "individual_record" ) { echo "class='active'"; } ?>><a href="#individual_record" data-toggle="tab">Individual Record</a></li>
					<li <?php if ( $this->session->userdata('fields_tab') === "search_fields" ) { echo "class='active'"; } ?>><a href="#search_fields" data-toggle="tab">Searchable Fields</a></li>
					
<!--					<li class="active">
						<a href="#search_result" data-toggle="tab">Search Results</a>
					</li>
					<li>
						<a href="#individual_record" data-toggle="tab">Individual Record</a>
					</li>-->
				</ul>
				<div class="tab-content">
					<div id="search_result" class="tab-pane<?php if ( $this->session->userdata('fields_tab') === "search_result" ) { echo " active"; } ?>">
						<div class="span11">
							<div class="pagination-centered" >
								<h4>Search Results</h4>
							</div>
							<div class="row-fluid">
								<div class="pagination-centered">
									<p>This page allows you to edit the way in which search results are displayed (changes are auto-saved). This includes changing the order (by clicking and dragging a field in the "Change Order" column) and adding or hiding which fields are displayed. Visible names can be changed by clicking on the link in the "Display Name" column. Editing the display fields for linkedAccess is currently disabled since the only field visible for this sharing policy should be the link.</p>
								</div>
								<br />
								<div class="pagination-centered">
									<select id="sharing_policy" >
									<?php
									// Get the last selected sharing policy that is set in the session (this is set using a change listener that sets the codeigniter session to the sharing policy that was just changed to
									// Set the selected tab to this last selected sharing policy
									$selected_sharing_policy = $this->session->userdata('sharing_policy');
//									$sharing_policies = array('openAccess', 'restrictedAccess', 'linkedAccess');
									$sharing_policies = array('openAccess', 'restrictedAccess');
									foreach ( $sharing_policies as $sharing_policy ):
										if ( strtolower($sharing_policy) == strtolower($selected_sharing_policy)):
									?>
										<option value="<?php echo $sharing_policy; ?>" selected="selected"><?php echo $sharing_policy; ?></option>
									<?php else: ?>
										<option value="<?php echo $sharing_policy; ?>"><?php echo $sharing_policy; ?></option>

									<?php endif; ?>
									<?php endforeach; ?>
									</select>
									&nbsp;
									<select id="displayfields">
										<?php foreach ($table_structure as $fields): ?>
											<option value="<?php echo $fields['name']; ?>"><?php echo $fields['name']; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="pagination-centered"><button type="button" class="btn btn-small" onclick="addDisplayField();" rel="popover" data-content="Add the selected field to the displayed fields." data-original-title="Add Field"><i class="icon-plus"></i>  Add</button></div>
								<br />
								<?php
//								$sharing_policies = array('openAccess', 'restrictedAccess', 'linkedAccess');
								foreach ($sharing_policies as $sharing_policy):
									?>
									<div id="<?php echo $sharing_policy; ?>" class="sharing_policies_div" style="display: none;">
										<div class="pagination-centered"><h4><?php echo $sharing_policy; ?> Display Fields</h4></div>
										<table class="table table-bordered table-striped table-hover" class="displayfieldstable" id="<?php echo $sharing_policy; ?>table">
											<thead>
												<tr>
													<th>Change Order</th>
													<th>Order</th>
													<th>Name</th>
													<th>Display Name</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody>
												<?php if (array_key_exists($sharing_policy, $display_fields_grouped)): ?>
													<?php $c = 0; ?>
													<?php foreach ($display_fields_grouped[$sharing_policy] as $display_field): ?>
														<?php $c++; ?>
														<?php
//														if ( in_array($display_field['name'], $this->config->item('protected_fields') ) ) {
//															continue;
//														} //Skip fields that are protected (defined in cafevariome config file 
														?>
														<tr>
															<td><i class="icon-move"></i> </td>
															<td class="count"><?php echo $c; ?></td>
															<td class="fieldname"><?php echo $display_field['name']; ?></td>
															<td class="visiblename"><a href="#" class="visiblenamevalue" data-type="text" data-pk="<?php echo $display_field['display_field_id']; ?>" data-url="<?php echo base_url() . "admin/change_visible_display_name"; ?>" data-title="Enter Human Readable Name" title="Click to change the human readable display name for this field"><?php echo $display_field['visible_name']; ?></a></td>
															<td><a href="<?php echo base_url('admin/delete_display_field') . "/" . $display_field['sharing_policy'] . "/" . $display_field['display_field_id']; ?>" rel="popover" data-content="Hide this display field from the current display type. N.B. The field may be re-added to the display by using the form above." data-original-title="Hide Display Field"></i><button type="button" class="btn btn-small">HIDE</button></a></td>
														</tr>
													<?php endforeach; ?>
												<?php endif; ?>
											</tbody>
										</table>
										<br />							
									</div>
								<?php endforeach; ?>
								<!--<div class="pagination-centered"><button type="button" class="btn btn-primary" id="save_display_order" rel="popover" data-content="Saves the order and fields in the table." data-original-title="Save"><i class="icon-ok icon-white"></i>  Save</button></div>-->
								<br /><br />
							</div>
						</div>
					</div>
					<div id="individual_record" class="tab-pane<?php if ( $this->session->userdata('fields_tab') === "individual_record" ) { echo " active"; } ?>">
						<div class="span11">
							<div class="pagination-centered" >
								<h4>Individual Record</h4>
							</div>
							<div class="row-fluid">
								<div class="pagination-centered">
									<p>This page allows you to edit the way in which individual variant records are displayed (changes are auto-saved). This includes changing the order (by clicking and dragging a field in the "Change Order" column) and adding or hiding which fields are displayed. Visible names can be changed by clicking on the link in the "Display Name" column.</p>
								</div>
								<br />
								<div class="pagination-centered">
									<select id="individual_record_field">
										<?php foreach ($table_structure as $fields): ?>
											<option value="<?php echo $fields['name']; ?>"><?php echo $fields['name']; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="pagination-centered"><button type="button" class="btn btn-small" onclick="addIndividualRecord();" rel="popover" data-content="Add the selected field to the displayed fields." data-original-title="Add Field"><i class="icon-plus"></i>  Add</button></div>
								<br />
								<table class="table table-bordered table-striped table-hover" class="individualrecordstable" id="individualrecordstable">
									<thead>
										<tr>
											<th>Change Order</th>
											<th>Order</th>
											<th>Name</th>
											<th>Display Name</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										<?php $c = 0; ?>
										<?php foreach ($individual_record_display_fields as $individual_record): ?>
											<?php $c++; ?>
											<?php
//											if ( in_array($display_field['name'], $this->config->item('protected_fields') ) ) {
//												continue;
//											} //Skip fields that are protected (defined in cafevariome config file 
											?>
										<tr>
											<td><i class="icon-move"></i> </td>
											<td class="count"><?php echo $c; ?></td>
											<td class="fieldname"><?php echo $individual_record['name']; ?></td>
											<td class="visiblename"><a href="#" class="visiblenamevalue" data-type="text" data-pk="<?php echo $individual_record['display_field_id']; ?>" data-url="<?php echo base_url() . "admin/change_visible_display_name"; ?>" data-title="Enter Human Readable Name" title="Click to change the human readable display name for this field" ><?php echo $individual_record['visible_name']; ?></a></td>
											<td><a href="<?php echo base_url('admin/delete_individual_record') . "/" . $individual_record['display_field_id']; ?>" rel="popover" data-content="Hide this display field from the individual record output. N.B. The field may be re-added by using the form above." data-original-title="Hide Display Field"><button type="button" class="btn btn-small">HIDE</button></a></td>
										</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
								<br />							
								<!--<div class="pagination-centered"><button type="button" class="btn btn-primary" id="save_individual_record_display_order" rel="popover" data-content="Saves the order and fields in the table ." data-original-title="Save"><i class="icon-ok icon-white"></i>  Save</button></div>-->
								<br /><br />
							</div>
						</div>
					</div>
					<div id="search_fields" class="tab-pane<?php if ( $this->session->userdata('fields_tab') === "search_fields" ) { echo " active"; } ?>">
						<div class="span11">
							<div class="pagination-centered" >
								<h4>Searchable Fields</h4>
							</div>
							<div class="row-fluid">
								<div class="pagination-centered">
									<p>This page allows you to edit which fields in your database are searchable by the user from the discovery search interface. Search fields can be added or hidden. If no fields are specified then ALL fields will be searchable (default).</p>
								</div>
								<br />
								<div class="pagination-centered">
									<select id="search_fields">
										<?php foreach ($table_structure as $fields): ?>
											<option value="<?php echo $fields['name']; ?>"><?php echo $fields['name']; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="pagination-centered"><button type="button" class="btn btn-small" onclick="addSearchField();" rel="popover" data-content="Add the selected field to the displayed fields." data-original-title="Add Field"><i class="icon-plus"></i>  Add</button></div>
								<br />
								<table class="table table-bordered table-striped table-hover" id="searchfieldstable">
									<thead>
										<tr>
											<th>Name</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										<?php $c = 0; ?>
										<?php 
											$tmp = array();
											foreach ($search_fields as $key => $row) {
												$tmp[$key] = $row['field_name'];
											}
											array_multisort($tmp, SORT_ASC, $search_fields); 
										?>
										<?php foreach ($search_fields as $search_field): ?>
											<?php $c++; ?>
											<?php
//											if ( in_array($display_field['name'], $this->config->item('protected_fields') ) ) {
//												continue;
//											} //Skip fields that are protected (defined in cafevariome config file 
											?>
										<tr>
											<td class="fieldname"><?php echo $search_field['field_name']; ?></td>
											<td><a href="<?php echo base_url('admin/delete_search_field') . "/" . $search_field['search_field_id']; ?>" rel="popover" data-content="Hide search field from the search. N.B. The field may be re-added by using the form above." data-original-title="Hide Search Field"><button type="button" class="btn btn-small">HIDE</button></a></td>
										</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
								<br />							
								<!--<div class="pagination-centered"><button type="button" class="btn btn-primary" id="save_individual_record_display_order" rel="popover" data-content="Saves the order and fields in the table ." data-original-title="Save"><i class="icon-ok icon-white"></i>  Save</button></div>-->
								<br /><br />
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="tab-pane<?php if ( $this->session->userdata('settings_tab') === "beacon" ) { echo " active"; } ?>" id="beacon">
				<div class="row-fluid">
					<div class="span10">
						<div class="pagination-centered" >
							<h4>Beacon Setup</h4><br />
							<p><a class="btn btn-info" href="<?php echo base_url('beacon/settings'); ?>" rel="popover" data-content="Enable or disable Beacon access." data-original-title="Beacon">Access beacon settings page</a></p> <!--<img src="<?php // echo base_url();?>resources/images/cafevariome/beacon.png" />-->
							<hr>
							<p><a href="http://ga4gh.org/#/beacon" target="_blank">Read more about the Beacon project</a></p>
						</div>
					</div>
				</div>
			</div>
			<!--</div>-->
		</div>
	</div>
</div><!--/.fluid-container-->