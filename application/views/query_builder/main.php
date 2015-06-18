<script>
	$(document).ready( function() {
		
		$('#querydiv').queryBuilder({
			type: ['grouped'],
			fields_grouped: {
				'Genotype': {
//					'allele' : {
//						'field' : 'allele',
//						'sub_fields' : [{"human_readable_field" : "Source", "field" : "source", "placeholder" : "RefSeq", "value" : "GRCBUILD", "input_size" : "medium"}, {"human_readable_field" : "Reference", "field" : "reference", "placeholder" : "NC_000002.11", "value" : "10.GRCh37", "input_size" : "medium"}, {"human_readable_field" : "Start", "field" : "start", "placeholder" : "2000001", "value" : "75871734", "input_size" : "medium"}, {"human_readable_field" : "End", "field" : "end", "placeholder" : "2000002", "value" : "75871737", "input_size" : "medium"}, {"human_readable_field" : "Allele Sequence", "field" : 'allele_sequence', "placeholder" : "A,T,G,C", "value" : "G", "input_size" : "medium"}],
//						'human_readable_field' : "Allele Sequence",
//						'placeholder' : "E.g. chr2:24593030-24593090",
//						'information' : "Allele Sequence",
//						'conditions' : ["IS", "BETWEEN"],
//						'logic' : ['OR'],
//						'number_blocks_for_field' : 2
////						'validation_url' : "<?php // echo base_url() . "admin/validate_coordinates";?>"
//					}
//					,
					'geneSymbol' : {
						'field' : 'geneSymbol',
						'human_readable_field' : "Gene",
						'placeholder' : "Enter a gene symbol",
						'information' : "You should enter a valid gene symbol, however any term may be used even if it is not a valid gene symbol (for example when performing an 'is like' search)",
						'conditions' : ["IS", "IS LIKE"],
						'logic' : ['OR'],
						'number_blocks_for_field' : 10,
						'validation_url' : "<?php echo base_url() . "admin/validate_gene";?>",
						'autocomplete_url' : "<?php echo base_url() . "admin/autocomplete_query_builder/gene";?>"
					}
//					,
//					'coordinate' : {
//						'field' : 'coordinate',
//						'sub_fields' : [{"human_readable_field" : "Source", "field" : "source", "placeholder" : "RefSeq", "value" : "GRCBUILD", "input_size" : "medium"}, {"human_readable_field" : "Reference", "field" : "reference", "placeholder" : "NC_000002.11", "value" : "10.GRCh37", "input_size" : "medium"}, {"human_readable_field" : "Start", "field" : "start", "placeholder" : "2000001", "value" : "75871734", "input_size" : "medium"}, {"human_readable_field" : "End", "field" : "end", "placeholder" : "2000002", "value" : "75871737", "input_size" : "medium"}],
//						'human_readable_field' : "Coordinate",
//						'placeholder' : "E.g. chr2:24593030-24593090",
//						'information' : "Coordinate",
//						'conditions' : ["IS", "BETWEEN"],
//						'logic' : ['OR'],
//						'number_blocks_for_field' : 2
////						'validation_url' : "<?php // echo base_url() . "admin/validate_coordinates";?>"
//					}
//					,
//					'coordinates' : {
//						'field' : 'coordinates',
//						'sub_fields' : [{"human_readable_field" : "Chr", "field" : "chr", "placeholder" : "chr1", "value" : "chr1", "input_size" : "small"}, {"human_readable_field" : "Start", "field" : "start", "placeholder" : "2000001", "value" : "2000001", "input_size" : "medium"}, {"human_readable_field" : "Stop", "field" : "stop", "placeholder" : "3000001", "value" : "3000001", "input_size" : "medium"}],
//						'human_readable_field' : "Coordinates",
//						'placeholder' : "E.g. chr2:24593030-24593090",
//						'information' : "You should enter valid genomic coordinates.",
//						'conditions' : ["IS"],
//						'logic' : ['OR'],
//						'number_blocks_for_field' : 10,
//						'validation_url' : "<?php // echo base_url() . "admin/validate_coordinates";?>"
//					},
//					'hgvs' : {
//						'field' : 'hgvs',
//						'human_readable_field' : "HGVS",
//						'placeholder' : "Enter a HGVS description",
//						'information' : "You should enter valid HGVS nomenclature",
//						'conditions' : ["IS"],
//						'logic' : ['OR'],
//						'number_blocks_for_field' : 10,
//						'validation_url' : "<?php // echo base_url() . "admin/validate_hgvs";?>"
//					},
				}
//				,
//				'Phenotype': {
//					'phenotype_term' : {
//						'field' : 'phenotype_term',
//						'human_readable_field' : "Term",
//						'placeholder' : "Enter a phenotype term",
//						'information' : "You should enter either a freetext term or use a phenotype term from an ontology",
//						'conditions' : ["IS","IS LIKE"],
//						'logic' : ['AND', 'OR'],
//						'number_blocks_for_field' : 3,
//						'validation_url' : "<?php // echo base_url() . "admin/validate_phenotype";?>",
//						'autocomplete_url' : "<?php // echo base_url() . "admin/autocomplete_bioportal/phenotype";?>"
//					},
//					'phenotype_epad' : {
//						'field' : 'phenotype_epad',
//						'human_readable_field' : "Term",
//						'placeholder' : "Enter a value",
//						'information' : "You should enter either a freetext term or use a phenotype term from an ontology",
//						'conditions' : ["=","&ne;",">","<",">=","<=","---------","IS","IS LIKE","IS NOT","IS NOT LIKE"],
//						'logic' : ['AND', 'OR'],
//						'number_blocks_for_field' : 100,
//						'validation_url' : "<?php // echo base_url() . "admin/validate_phenotype";?>",
//						'autocomplete_url' : "<?php // echo base_url() . "admin/autocomplete_epad";?>"
//					}						
//				}
			},
			logic: ['AND', 'OR'], //'NOT'
			syntax : ['QueryAPI', 'ElasticSearch', 'mySQL', 'SPARQL'],
			style: ['bootstrap'],
			autocomplete: ['on'],
			use_jquery_growl: ['yes'],
			number_blocks_per_field : 2,
			store_query_uri : '<?php echo base_url() . "admin/store_query_builder_query"; ?>',
			result_div_id : 'queryresultdiv',
			use_datatables : ['yes'],
			table_output_type: ['hits'],
			hits_display_uri: '<?php echo base_url() . "discover/query_builder_results_display"; ?>',
//			endpoints : [{'name' : 'MOLGENIS105', 'url' : 'http://molgenis105.gcc.rug.nl/api/v1/getAggregate', 'type' : 'molgenis'}, {'name' : 'MOLGENIS106', 'url' : 'http://molgenis106.gcc.rug.nl/api/v1/getAggregate', 'type' : 'molgenis'}],
//			endpoints : [{'name' : 'MOLGENIS105', 'url' : 'http://molgenis105.gcc.rug.nl/api/v1/getAggregate'}],
//			endpoints : [{'name' : 'local1', 'url' : 'http://localhost/cafevariome/discover/q1'}, {'name' : 'local2', 'url' : 'http://localhost/cafevariome/discover/q2'}],
//			endpoints : [{'name' : 'client1', 'url' : '<?php // echo base_url() . 'discover/query'; ?>', 'type' : 'local'},{'name' : 'client2', 'url' : 'http://127.0.0.1/cafevariome_client_2/discover/query', 'type' : 'local'}],
			endpoints : [{'name' : 'client1', 'url' : '<?php echo base_url() . 'discover/query'; ?>', 'type' : 'local'},{'name' : 'client2', 'url' : 'http://127.0.0.1/cafevariome_client_2/discover/query', 'type' : 'local'}],
			complete : function() {
//				alert( 'Done!' );
			}
		});
	});
</script>

<div class="container">
	<!--<div class="well">-->
		<div class="row-fluid">
			<div class="span12 pagination-centered">
				<!--<h2>Query Builder Prototype</h2>-->
				<!--<hr>-->
				<div class="row">
					<!--<button class="btn btn-success btn-large" href="#metadataModal" data-toggle="modal" data-backdrop="false" rel="popover" data-content="Add/edit metadata for the query." data-original-title="Metadata">Set query metadata</button>-->
				</div>
				<h2>Query Builder</h2><hr>
				<div id="querydiv"></div>
				<div id="queryresultdiv"></div>
				<div id="ajax-loader" style="display: none; text-align: center;">
					<!--Please wait...<br />-->
					<img src="<?php echo base_url("resources/images/cafevariome/gears.svg");?>" title="Loader" alt="Loader" />
				</div>
			</div>
		</div>
	<!--</div>-->
</div><!--/.container-->

<div id="metadataModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">Query metadata</h3>
	</div>
	<div class="modal-body">
		<p>
			Query endpoint:
			<input class="endpoint input-xlarge" id="endpoint" type="text" placeholder="http://yourdomain/query_api_endpoint" value="http://localhost/cafevariome/discover/query" >
		</p>
		<p>
			Query type:
			<select id="queryType">
				<option value="once">Once</option>
				<option value="periodic">Periodic</option>
			</select>
		</p>
		<p>
			Query label:
			<input class="input-xlarge" id="queryLabel" type="text" placeholder="Query label" value="Query builder query" >
		</p>
		<p>
			Result format: 
			<select id="queryResultFormat">
				<option value="JSON">JSON</option>
				<option value="tab">Tab</option>
				<option value="HTML">Webpage</option>
			</select>
		</p>
		<p>
			Submitter ID: 
			<input class="input-xlarge" id="submitterID" type="text" placeholder="Query ID" value="uol_owen" >
		</p>
		<p>
			Submitter name: 
			<input class="input-xlarge" id="submitterName" type="text" placeholder="Submitter name" value="Owen" >
		</p>
		<p>
			Submitter email: 
			<input class="input-xlarge" id="submitterEmail" type="text" placeholder="Submitter email" value="ol8@le.ac.uk" >
		</p>
		<p>
			Submitter institute
			<input class="input-xlarge" id="submitterInstitute" type="text" placeholder="Submitter institute" value="University of Leicester" >
		</p>
		<small><p>N.B. Most metadata will be prefilled from user details and some will transparent to the user (endpoint, query format etc) just adding them in here for development.</p></small>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn" data-dismiss="modal">Close</a>  
		<!--<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Apply</button>--> 
	</div>
</div>
