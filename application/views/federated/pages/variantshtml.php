<div class="container">
	<div class="row-fluid">
		<div class="span12 pagination-centered" id="table_container">
			<div class="well">
				<?php if(empty($variants)): ?>
				<h4>Sorry, there are no records available for this search</h4>
				<?php else: ?>
				<h3>
					<?php 
					$escaped_term = urldecode($term);
					$escaped_term = str_replace('\]', ']', $escaped_term); // Escape square brackets as these are reserved in ElasticSearch
					$escaped_term = str_replace('\[', '[', $escaped_term); // Escape square brackets as these are reserved in ElasticSearch
					$escaped_term = str_replace('_', ' ', $escaped_term);
					echo $escaped_term; ?> records
				</h3> 
<!--			<table width="70%" cellpadding="0" cellspacing="0" border="0" class="display" id="example">-->
<!--				<table class="table table-hover table-bordered table-striped">-->
				<!--<a href="#exportModal" data-toggle="modal" data-backdrop="false" rel="popover" data-content="Export to Excel, visualise data in genome browsers." data-original-title="Export and Visualisation" class="btn" ><i class="icon-picture"></i> Export and Visualisation</a>-->
				<!-- Table is created using datatables - initialised in DT_bootstrap.js file in resources/js -->
				<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="variantspaginationfederated" width="100%">
				<thead>
					<thead>
					</thead>
					<tbody></tbody>
					<tfoot></tfoot>
				</table>
				

				<?php endif; ?>
			</div>
		</div>
	</div>
</div><!--/.container-->

<input type="hidden" id="sharing_policy" name="sharing_policy" value="<?php echo $sharing_policy; ?>">
<input type="hidden" id="term" name="term" value="<?php echo urlencode($term); ?>">
<input type="hidden" id="source" name="source" value="<?php echo $source; ?>">

<div id="exportModal" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h4 align="center" id="myModalLabel">Export and Visualisation</h4>
	</div>
	<div class="modal-body">
		<div class="well">
			<h4>Visualisation:</h4>
			<p align="center"><br /><a class="btn btn-medium" href="<?php echo base_url() . "discover/view_variants_in_ucsc/" . $term . "/" . $source . "/" . $sharing_policy;?>" target="_blank" ><i class="icon-picture"></i> View as track in UCSC</a><br /><br /><small>Generate a BED track of the current search and open a page to the UCSC genome browser linking to that track.</small></p>
			<hr>
			<h4>Export:</h4>
			<p align="center"><br /><a class="btn btn-medium" href="<?php echo base_url() . "discover/variants/" . $term . "/" . $source . "/" . $sharing_policy . "/excel";?>" target="_blank" ><i class="icon-file"></i> Export to Excel</a><br /><br /><small>Generate an Excel spreadsheet containing the query data.</small></p>
		</div>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn" data-dismiss="modal">Close</a>  
	</div>
</div>