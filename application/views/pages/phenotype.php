<div class="container">
	<div class="row-fluid">
		<div class="span12 pagination-centered" id="table_container">
			<div class="well">
				<h3>Local Phenotype Term</h3>
				<table class="table table-striped table-bordered table-hover" >
					<tr>
						<th align="center" class="title">Term name</th>
						<td><?php echo $phenotype['0']['termName']; ?></td>
					</tr>
                                        
					<tr>
						<th align="center" class="title">Term ID</th>
						<td><?php echo $phenotype['0']['termId']; ?></td>
					</tr>
					
					<tr>
                                                <th align="center" class="title">Definition</th>
                                                <td><?php if (empty($phenotype['0']['termDefinition'])){ echo "-";} else{ echo $phenotype['0']['termDefinition']; }?></td>
						
					</tr>
                                        <tr>
                                                <th align="center" class="title">Mapped to</th>
                                                <td><?php echo "-"; ?></td>
						
					</tr>
				</table>

			</div>
		</div>
	</div>
</div><!--/.container-->
