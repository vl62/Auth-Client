<div class="container">
	<div class="row">  
		<div class="span6">  
			<ul class="breadcrumb">  
				<li class="active">Curator Dashboard</li>  
			</ul>  
		</div>  
	</div>  
	<div class="row">
		<!--<h4>Curate: </h4>-->
		<div class="offset2 span3 pagination-centered"><a href="<?php echo base_url() . "curate/sources";?>" class="btn btn-info btn-large" rel="popover" data-content="Curate variant sources" data-original-title="Variant Sources"><img width="75" height="75" src="<?php echo base_url();?>resources/images/cafevariome/icon-sources.png" /></a><br />Sources</div>
		<div class="span3 pagination-centered"><a href="<?php echo base_url() . "curate/records";?>" class="btn btn-info btn-large" rel="popover" data-content="Curate records" data-original-title="Records"><img width="75" height="75" src="<?php echo base_url();?>resources/images/cafevariome/icon-records.png" /></a><br />Records</div>
		<!--<div class="span3 pagination-centered"><a href="<?php // echo base_url() . "curate/phenotypes";?>" class="btn btn-info btn-large" rel="popover" data-content="Phenotype and phenotype ontology settings" data-original-title="Phenotypes"><img width="75" height="75" src="<?php // echo base_url();?>resources/images/cafevariome/icon-phenotypes.png" /></a><br />Phenotypes</div>-->
	</div>
	<?php echo br(5); ?>
	<div class="row">
		<div class="offset2 span3 pagination-centered"><a href="<?php echo base_url() . "curate/import_templates";?>" class="btn btn-info btn-large" rel="popover" data-content="Generate import templates that can be populated with data and subsequently directly imported into the installation" data-original-title="Import Templates"><img width="75" height="75" src="<?php echo base_url();?>resources/images/cafevariome/icon-templates.png" /></a><br />Import Templates</div>
		<div class="span3 pagination-centered"><a href="<?php echo base_url() . "curate/data_requests/" . $user_id;?>" class="btn btn-info btn-large" rel="popover" data-content="Approve or refuse any requests for data from users." data-original-title="Curate data requests"><img width="75" height="75" src="<?php echo base_url();?>resources/images/cafevariome/icon-data-requests.png" /></a><br />Data Requests</div>
	</div>
	<hr>
	<br />
	<br />
</div>