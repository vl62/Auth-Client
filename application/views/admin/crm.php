<script>
$(document).ready(function() {
//	N.B. Editable class for x-editable is now initialized in DT_bootstrap.js as a workaround for the fields in pages not being editable
//	$(".editable").editable({
//		mode: "inline"
//	});	
		
	tinymce.init({
//		selector: "textarea",
		mode : "specific_textareas",
		editor_selector : "email_selector",
		width:      '100%',
		height:     270,
		plugins:    [ "anchor link code preview" ],
		statusbar:  false,
		menubar:    false,
//		toolbar:    false,
		toolbar:    "code link hr preview",
		rel_list:   [ { title: 'Lightbox', value: 'lightbox' } ]
	});
	
});	
</script>	

<div class="container">
	<div class="row">
		<div class="span6">  
			<ul class="breadcrumb">  
				<li>  
					<a href="<?php echo base_url() . "admin";?>">Dashboard Home</a> <span class="divider">></span>  
				</li>
				<li class="active">CRM</li>
			</ul>  
		</div>  
	</div>
	<div class="row-fluid">
		<h2>CRM - Leads</h2>
		<br />

		<div class="pagination-centered"><button class="btn btn-success" href="#createLeadsModal" data-toggle="modal" data-backdrop="false" rel="popover" data-content="Changes the sharing policy for ALL variants in this source (clicking brings up selection window). If you want to change the sharing policy for individual variants, click on the curate/edit action." data-original-title="Update Lead Status"><i class="icon-user icon-white"></i> Create Lead(s)</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" href="#emailModal" data-toggle="modal" data-backdrop="false" rel="popover" data-content="Changes the sharing policy for ALL variants in this source (clicking brings up selection window). If you want to change the sharing policy for individual variants, click on the curate/edit action." data-original-title="Update Lead Status"><i class="icon-pencil icon-white"></i> Email Leads</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" href="#statusModal" data-toggle="modal" data-backdrop="false" rel="popover" data-content="Changes the sharing policy for ALL variants in this source (clicking brings up selection window). If you want to change the sharing policy for individual variants, click on the curate/edit action." data-original-title="Update Lead Status"><i class="icon-refresh icon-white"></i> Update Status</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-danger btn-medium delete_leads_button" ><i class="icon-trash icon-white"></i> Delete Lead(s)</button></div>
		<hr>
		<table class="table table-bordered table-striped table-hover" id="crmtable">
			<thead>
				<tr>
					<th></th>
					<th>ID</th>
					<th>Name</th>
					<th>Institute/Lab/Company</th>
					<th>Email</th>
					<th>Comment</th>
					<th>Initial Contact</th>
					<th>Last Contact</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($crm as $lead): ?>
				<tr>
					<td><input class="lead_checkbox" type="checkbox" name="lead_checkbox" id="<?php echo $lead['id']; ?>_lead"></td>
					<td><?php echo $lead['id']; ?></td>
					<td><a href="#" class="editable" id="contact_name" data-type="text" data-pk="<?php echo $lead['id']; ?>" data-url="<?php echo base_url() . "admin/change_crm_lead_via_xeditable"; ?>" data-title="Enter Contact Name" title="Edit the contact name"><?php echo $lead['contact_name']; ?></a></td>
					<td><a href="#" class="editable" id="lab_name" data-type="text" data-pk="<?php echo $lead['id']; ?>" data-url="<?php echo base_url() . "admin/change_crm_lead_via_xeditable"; ?>" data-title="Enter Lab Name" title="Edit the lab name"><?php echo $lead['lab_name']; ?></a></td>
					<td><a href="#" class="editable" id="email" data-type="text" data-pk="<?php echo $lead['id']; ?>" data-url="<?php echo base_url() . "admin/change_crm_lead_via_xeditable"; ?>" data-title="Enter Email" title="Edit the email"><?php echo $lead['email']; ?></a></td>
					<td><a href="#" class="editable" id="comment" data-type="textarea" data-pk="<?php echo $lead['id']; ?>" data-url="<?php echo base_url() . "admin/change_crm_lead_via_xeditable"; ?>" data-title="Enter Comment" title="Edit the comment"><?php echo $lead['comment']; ?></a></td>
					<td><a href="#" class="editable" id="date_initial_contact" data-type="text" data-pk="<?php echo $lead['id']; ?>" data-url="<?php echo base_url() . "admin/change_crm_lead_via_xeditable"; ?>" data-title="Enter Contact Name" title="Edit the contact name"><?php echo $lead['date_initial_contact']; ?></a></td>
					<td><a href="#" class="editable" id="date_last_contact" data-type="text" data-pk="<?php echo $lead['id']; ?>" data-url="<?php echo base_url() . "admin/change_crm_lead_via_xeditable"; ?>" data-title="Enter Contact Name" title="Edit the contact name"><?php echo $lead['date_last_contact']; ?></a></td>
					<td><a class='editable' id="status" data-pk="<?php echo $lead['id']; ?>" data-value="<?php echo $lead['status']; ?>" data-url="<?php echo base_url() . "admin/change_crm_lead_via_xeditable"; ?>" data-type='select' data-source='[{value: "Attempted to contact", text: "Attempted to contact"},{value: "Collaborator", text: "Collaborator"},{value: "Contact in future", text: "Contact in future"},{value: "Contacted", text: "Contacted"},{value: "Junk lead", text: "Junk lead"},{value: "Lost lead", text: "Lost lead"},{value: "Not contacted", text: "Not contacted"},{value: "Not interested", text: "Not interested"},{value: "Piloting system", text: "Piloting system"},{value: "Shown interest", text: "Shown interest"}]'><?php echo $lead['status']; ?></a></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<br />
		<div class="span12 pagination-centered"><a href="<?php echo base_url() . "admin";?>" class="btn" ><i class="icon-home"></i> Admin Dashboard</a></div>
		<?php echo br(5); ?>
	</div>
</div>

<div id="statusModal" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">Change Lead Status</h3>
	</div>
	<div class="modal-body">
		<div class="well">
			<p>Choose the status you would like to set the selected leads to:</p><hr>
			<select id="change_status_select">
				<?php foreach ( $statuses as $status ): ?>
				<option value="<?php echo $status; ?>"><?php echo $status; ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
	<div class="modal-footer">
		<a id="change_status_confirm" href="#" class="btn btn-success">Confirm</a>  
		<a href="#" class="btn" data-dismiss="modal">Close</a>  
		<!-- <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Apply</button> -->
	</div>
</div>


<div id="createLeadsModal" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 align="center" id="myModalLabel">Create Leads</h3>
	</div>
	<div class="modal-body">
		<div class="well">
			<p align="center"><a href="<?php echo base_url() . "admin/create_lead/";?>" class="btn btn-small btn-primary"><i class="icon-plus"></i> Manually enter leads</a><br /></p>
			<p align="center"><a href="<?php echo base_url() . "admin/create_leads/"; ?>" class="btn btn-small btn-primary"><i class="icon-plus"></i> Bulk import leads</a><br /></p>
		</div>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn" data-dismiss="modal">Close</a>  
	</div>
</div>

<!-- Modal itself -->
<div id="emailModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">Email Leads</h3>
	</div>
	<div class="modal-body">
		<form class="form-inline">
			Select a template: 
			<select id='selected_template' class="form-control">
				<?php foreach ( $email_templates as $template ): ?>
				<option value="<?php echo $template['template_id']; ?>"><?php echo $template['template_name']; ?></option>
				<?php endforeach; ?>
			</select>
			<button type="button" class="btn btn-primary btn-small" id="select_template_button">Select</button>
		</form>
		<textarea class="email_selector" id="email_text"><?php echo $email_templates[min(array_keys($email_templates))]['template']; // Select the template with the lowest array key val?></textarea>
		<br />Use %name to dynamically insert the name of the lead<br />
		<hr>
		Add a comment for this lead:<br />
		<textarea id="email_comment"></textarea><br><br />
		<!--<input type="text" name="fname"><br><br />-->
		Change status to:<br />
		<select id="change_status_select_in_email">
			<option selected></option>
			<?php foreach ( $statuses as $status ): ?>
			<option value="<?php echo $status; ?>"><?php echo $status; ?></option>
			<?php endforeach; ?>
		</select>
	</div>

	<div class="modal-footer">
		<a id="email_leads_confirm" href="#" class="btn btn-success">Send</a>
		<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Close</button>
	</div>
</div>