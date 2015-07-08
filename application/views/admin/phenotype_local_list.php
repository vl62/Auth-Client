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
				<li class="active">Phenotype Local List</li>
			</ul>  
		</div>  
	</div>
	
	<div class="row-fluid">
		<h2>Phenotype Local List</h2>
		<hr>
                <p>Add phenotype terms that are used locally and do not appear in standard Biomedical Ontologies.</p>
                <p>These terms will autocomplete when adding or editing phenotype data.</p>
		<table class="table table-bordered table-striped table-hover" id="phenotypelocallisttable">
			<thead>
				<tr>					
                                        <th>Term Name</th>
                                        <th>Qualifier</th>
                                        <th>Term Identifier</th>
                                        <th>Term Definition</th>
                                        <th>Action</th>
					</tr>
			</thead>
			<tbody>
				<?php $c = 0; ?>
				<?php foreach ($phenotype_local_list as $term): ?>
				<?php $c++; ?>
				<tr id="<?php echo $term['id']; ?>">                               
                                        <td><?php echo $term['termName']; ?></td>
                                        <td><?php echo $term['qualifier']; ?></td>
					<td><?php echo $term['termId']; ?></td>                                  
                                        <td><?php if (empty($term['termDefinition'])){ echo "--";} else{ echo $term['termDefinition'];} ?></td>
                                        <td><?php if ($term['sourceId'] == null){echo "<a onclick=\"deleteTerm('".$term['termId']."')\"><i class=\"icon-trash\"></i></a>";} else { echo "<a rel=\"popover\" data-content=\"This term is used in the description of phenotypes, so it can not be removed\" data-original-title=\"Locked Term\"><i class=\"icon-lock\"></i></a>"; } ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<br />
		<div class="span12 pagination-centered"><a class="btn btn-primary btn-medium" href="#addPhenotypeTermModal" data-toggle="modal" data-backdrop="false" rel="popover" data-content="Click open a popup that allows you to manually enter a phenotype term that will become part of your local phenotype list." data-original-title="Add Phenotype Local Term"><i class="icon-plus icon-white"></i>  Paste Phenotype Terms</a></div>
		<?php if ( ! is_writable(FCPATH. 'upload')): ?>
			<?php echo '<br /<br /><br /><br /><div class="alert alert-error">WARNING: ' . FCPATH. 'upload' . " is not writable by the webserver, phenotype lists cannot be uploaded</div>"; ?>
		<?php else: ?>
			<?php echo br(2); ?>
			<div class="span12 pagination-centered"><a class="btn btn-primary btn-medium" href="#uploadPhenotypeListModal" data-toggle="modal" data-backdrop="false" rel="popover" data-content="Upload a list of phenotype terms from a file (file format must be a text file consisting of single column consisting of phenotypes." data-original-title="Upload Phenotype Terms"><i class="icon-file icon-white"></i>  Upload Phenotype Terms</a></div>
			<?php echo br(2); ?>
		<?php endif; ?>

		<?php echo br(5); ?>
	</div>
</div>

<div id="addPhenotypeTermModal" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 align="center" id="myModalLabel">Add new phenotype terms</h4>
	</div>
	<div class="modal-body">
		<div class="well">
			<form method="post" action="<?php echo base_url("admin/add_phenotype_term"); ?>" enctype="multipart/form-data" >
				Phenotype Term(s): 
				<br /><textarea name="phenotype_term" rows="10" cols="70" style="width:100%;"></textarea><br /><p>Type or paste terms into the box above (one term per line). An optional qualifier can be added by including it in square brackets after the term e.g. [cm]. An optional definition can also be added to a term.  To do this, separate the term and the definition with a vertical bar (|) symbol.</p>
				<div class="pagination-centered"><br /><button type="submit" class="btn btn-primary" id="upload_submit"><i class="icon-plus icon-white"></i>  Add Term(s)</button></div>
			</form>
		</div>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn" data-dismiss="modal">Close</a>  
	</div>
</div>

<div id="uploadPhenotypeListModal" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 align="center" id="myModalLabel">Upload Phenotype List</h4>
	</div>
	<div class="modal-body">
		<div class="well">
			<form method="post" action="<?php echo base_url("admin/upload_phenotype_list"); ?>" enctype="multipart/form-data" >
				<label for="userfile"><h4>Select your phenotype list file:</h4></label>
				<input type="file" name="userfile" id="userfile" size="20" /><br /><br />
				<p>The format of the list file must be a single column with one term per row.  Optionally, a term can have a definition.  The term and the definition should be on the same row and separated with a vertical bar (|) symbol.</p>
				<!--<label for="title">Title</label>-->
				<button type="submit" class="btn btn-primary" id="upload_submit"><i class="icon-plus icon-white"></i>  Upload Phenotype List</button>
			</form>
		</div>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn" data-dismiss="modal">Close</a>  
	</div>
</div>