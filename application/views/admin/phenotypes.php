<div class="container">
	<div class="row">
		<div class="span6">  
			<ul class="breadcrumb">  
				<li>  
					<a href="<?php echo base_url() . "admin";?>">Dashboard Home</a> <span class="divider">></span>  
				</li>
				<li class="active">Phenotypes</li>
			</ul>  
		</div>  
	</div>  
	<div class="row">
		<div class="span4">
			<h4>Phenotype:</h4>
		</div>
	</div>
	<div class="row">
		<div class="span6 pagination-centered"><a href="<?php echo base_url() . "admin/phenotype_local_list";?>" class="btn btn-info btn-large" rel="popover" data-content="Create/modify/delete the current list of phenotypes" data-original-title="Phenotype List"><img width="100" height="100" src="<?php echo base_url();?>resources/images/cafevariome/icon-phenotype-list.png" /></a><br />Local List</div>
		<div class="span6 pagination-centered"><a href="<?php echo base_url() . "admin/phenotype_ontologies";?>" class="btn btn-info btn-large" rel="popover" data-content="Add phenotype ontologies" data-original-title="Variants"><img width="100" height="100" src="<?php echo base_url();?>resources/images/cafevariome/icon-phenotype-ontologies.png" /></a><br />Ontologies</div>
	</div>
	<hr>

	<br />
	<br />
</div>