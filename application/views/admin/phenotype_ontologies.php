<script>
$(document).ready(function() {
	$("#ontology_list_test").select2({
		placeholder: "Click here to view and search through available ontologies"
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
				<li>  
					<a href="<?php echo base_url() . "admin/phenotypes";?>">Phenotypes</a> <span class="divider">></span>  
				</li>
				<li class="active">Phenotype Ontologies</li>
			</ul>  
		</div>  
	</div>
	
	<div class="row-fluid">
		<h2>Phenotype Ontologies</h2>
		<hr>
		<?php if ( $bioportal_api_key ): ?>
		<table class="table table-bordered table-striped table-hover" id="phenotypeontologytable">
			<thead>
				<tr>
					<th>Ontology Name</th>
					<th>Ontology Abbreviation</th>
                                        <th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php $c = 0; $currentOntologies=array();?>
				<?php foreach ($phenotype_ontologies as $ontology): ?>
				<?php $c++; ?>
				<tr id="<?php echo $ontology['name']; array_push($currentOntologies, $ontology['name']); ?>">	
					<td><a href="http://bioportal.bioontology.org/ontologies/<?php echo $ontology['abbreviation']; ?>" target="_blank" ><?php echo $ontology['name']; ?></a></td>
					<td><?php echo $ontology['abbreviation']; ?></td>
                                        <td><?php if ($ontology['attribute_sourceId'] == null){echo "<a onclick=\"deleteOntology('".$ontology['abbreviation']."')\"><i class=\"icon-trash\"></i></a>";} else { echo "<a rel=\"popover\" data-content=\"This ontology is ACTIVE and has been used in the description of phenotypes, so it can not be removed\" data-original-title=\"Locked Ontology\"><i class=\"icon-lock\"></i></a>"; } ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<br />
		<div class="span12 pagination-centered"><a class="btn btn-primary btn-medium" href="#addOntologyModal" data-toggle="modal" data-backdrop="false" rel="popover" data-content="Add a phenotype ontology from BioPortal." data-original-title="Add Phenotype Ontology"><i class="icon-file icon-white"></i>  Add Ontology</a><?php echo nbs(6); ?><a href="<?php echo base_url() . "admin";?>" class="btn" ><i class="icon-home"></i> Admin Dashboard</a></div>
		<?php else: ?>
		<div class="span12 pagination-centered"><a href="<?php echo base_url() . "admin";?>" class="btn" ><i class="icon-home"></i> Admin Dashboard</a></div>
		<?php endif; ?>
		<?php echo br(5); ?>
	</div>
</div>

<div id="addOntologyModal" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 align="center" id="myModalLabel">Add a new BioPortal ontology</h4>
	</div>
	<div class="modal-body">
		<div class="well pagination-centered">
                                               
                        <select name="ontology_list" id="ontology_list" size="15" multiple>
                            
                           <?php 
				    		$counter = 0;
                                                foreach($ontologies as $identifier => $label) {
                                            //       list($versionid, $virtialid) = explode("|", $key);
                                            //        list($name, $abbreviation) = explode("|", $value);
                                                    if (!in_array($label, $currentOntologies, true)){
                                                                                                       
				    	?>
				    	<option value="<?php echo $identifier ?>|<?php echo $label ?>"><?php echo $label ?> (<?php echo $identifier?>)</option>
				    	<?php 
				    			$counter++;
				    			
				    							    			
				    		}
                                                }
				    	?>
                            
                            
                        </select>
                                            
			<br /><a class="btn btn-primary btn-medium" onclick="addNewOntologies()"><i class="icon-file icon-white"></i>  Add Ontologies</a>
                </div>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn" data-dismiss="modal">Close</a>  
	</div>
</div>