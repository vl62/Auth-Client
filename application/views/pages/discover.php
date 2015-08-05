<?php 
if (sizeof($ontologiesused) > 0) {
	// jstree javascript generation in discover controller function _generate_jstree
	echo $jstree;
}

?>


<div class="container">
	<div class="row-fluid">
		<div class="span12 pagination-centered">
			<div class="well">
				<h3>Record Discovery</h3>
				<hr>
				<?php echo form_open('discover', array( 'id' => 'discover')); ?>

				<?php
				ksort($sources_options);
				$sources_options = array('all' => 'All') + $sources_options;
//				print_r($sources_options);
				?>
				<!--<br />-->

				<p>
					<h4>Search term:</h4></label><a href="<?php echo base_url('discover/stats'); ?>" target="_blank" rel="popover" data-content="Enter a search term - HGNC gene symbol, RefSeq Accession/HGVS nomenclature or chromosomal region." data-original-title="Search Information"><i class="icon-question-sign"></i></a>
					<!--<h4>Search term:</h4></label><a href="#searchHelpModal" data-toggle="modal" data-backdrop="false" rel="popover" data-content="Enter a search term - HGNC gene symbol, RefSeq Accession/HGVS nomenclature or chromosomal region.<br /><br /> Click to open a page in a new window with further information about data content and search queries." data-original-title="Search Information"><i class="icon-question-sign"></i></a>-->
					<?php
						$search_data = array('name' => 'term', 'id' => 'term', 'class'=>"input-xxlarge search-query", 'placeholder' => "Start typing a search term..." ); 
						echo form_input($search_data);
					?>
					<a href="#" onclick="clearSearchBox();" rel="popover" data-content="Click to clear the current term in the search box." data-title="Clear Search Box" ><i class="icon-remove-sign"></i></a>
<!--					<a rel="popover" href="#searchPrefsModal" data-toggle="modal" data-backdrop="false" data-content="Edit additional search preferences, such as searching only for variants that are validated by Mutalyzer." data-original-title="Additional Search Preferences"><i class="icon-tasks"></i></a>-->
					<?php if ($this->ion_auth->logged_in()): ?><a href="<?php echo base_url('/discover/search_history'); ?>" target="_blank" rel="popover" data-content="Click to view your search history. Only searches performed when you are logged in will be recorded." data-title="Search History"><i class="icon-time"></i></a><?php endif; ?>
					<?php if ( sizeof($ontologiesused) > 0 ): ?>
					<a rel="popover" href="#phenotypeTreeModal" data-toggle="modal" data-backdrop="false" data-content="Search by browsing a tree of phenotype annotation terms." data-original-title="Phenotype Tree Search"><img src="<?php echo base_url("resources/images/cafevariome/tree.png");?>" width="20" height="20" /></a>
					<?php endif; ?>
<!--					<span class="help-block">Enter a HGNC gene symbol (or LRG), RefSeq Accession/HGVS nomenclature or chromosomal region<br />(e.g. <a href="#" class="termselect">BRCA1</a> (<a href="#" class="termselect">LRG_292</a>) | <a href="#" class="termselect">NM_007294.3:c.5561T>C</a> | <a href="#" class="termselect">NM_007294.3</a> | <a href="#" class="termselect">chr17:41196312..41277500</a>)</span>-->
					<br /><small><span class="help-block">Wildcards can be used for any search term e.g. <a href="#" class="termselect">BRCA*</a><br />View a list of <a href="#" id="openGenesModal" >genes</a> or <a href="#" id="openReferenceModal" >reference sequences</a> that have the highest record counts<br />Search by chromosomal region e.g. <a href="#" class="termselect">chr17:41196312..41277500</a>,<br />or a specific HGVS description in a gene e.g. <a href="#" class="termselect">BRCA2 c.8167G>C</a></small></span>
				</p>

<!--				<br />-->
				<button type="submit" class="btn" id="search"><i class="icon-search"></i> Discover Records</button>
				<p>
					<br />
					<a href="<?php echo base_url('discover/proceed_to_query/query_builder'); ?>" >Use the query builder to perform an advanced search</a>
				</p>
				<?php echo form_close(); ?>
              </div>                                      
		</div>
	</div>    
	<div id="waiting" style="display: none; text-align: center;">
		Please wait...<br />
		<img src="<?php echo base_url("resources/images/cafevariome/ajax-loader.gif");?>" title="Loader" alt="Loader" />
	</div>
	<!-- Div for the source counts table -->
	<div id="mutationDisplay"></div>
</div><!--/.container-->

<!--<hr>-->
<div id="sourcesModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">Sources Advertised by Cafe Variome</h3>
	</div>
	<div class="modal-body">
		<p>The following data sources are currently available:</p><br>
		<ul>
		<?php
			ksort($source_counts);
			foreach ( $source_counts as $key => $value ) {
				print "<li><b>$key</b>: $value records</li>";
			}
		?>
		</ul>
		<p>See the <a href="<?php echo base_url('variants/stats'); ?>">following page</a> for full source descriptions.</p>
		<p>Please note that Cafe Variome never edits or curates data and makes no warranty, express or implied, as to its accuracy of that the information is fit for a particular purpose, and Cafe Variome will not be held responsible for any consequences arising out of any inaccuracies or omissions.</p>

	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
	</div>
</div>

<div id="searchPrefsModal" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">Additional Search Preferences</h3>
	</div>
	<div class="modal-body">
		<div class="well">
			<h4>Mutalyzer</h4><hr>
			<p>
				<?php echo form_checkbox(array("name" => "mutalyzer_check", "id" => "mutalyzer_check", "style" => "float: left"), FALSE); ?>
				&nbsp;&nbsp;Only search for records validated by <a href="https://mutalyzer.nl/" target="_blank">Mutalyzer</a>
			</p>
		</div>
		<!--<div class="well">-->
			<!--<h4>Search Results Grouping</h4><hr>-->
			<!--<p>Group discovered variants by :&nbsp;-->
				<?php
//				$options = array(
//					'by_source' => 'Source',
//					'by_variant' => 'Variant Name (HGVS)'
//				);
//				echo form_dropdown('grouping_type', $options, 'by_source', 'id="grouping_type"');
				?>
<!--				<p><a href="#" class="tooltip-searchprefs" title="Tooltip">This link</a> and <a href="#" class="tooltip-searchprefs" title="Tooltip">that link</a> should have tooltips on hover.</p>-->

				<!-- <a href="#" rel="popover" data-content="Enter a search term - HGNC gene symbol, RefSeq Accession/HGVS nomenclature or chromosomal region." data-original-title="Search Information"><i class="icon-question-sign"></i></a> -->
			<!--</p>-->
		<!--</div>-->
	</div>
	<div class="modal-footer">
		<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Apply</button>
	</div>
</div>

<div id="genesModal" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h4 id="myModalLabel">Gene Content (top 20 occurences)</h4>
	</div>
	<div class="modal-body">
		<div id="geneContent">
				
		</div>
		<div class="pagination-centered"><p>Select a gene and the term will be populated into the search box</p></div>
		<div id="geneWaiting" class="span2 offset1 pagination-centered" style="display: none;">
			<!--Regenerating autocomplete...<br />-->
			<img src="<?php echo base_url("resources/images/cafevariome/ajax-loader-alt.gif");?>" title="Loader" alt="Loader" />
		</div>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
	</div>
</div>

<div id="referenceModal" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h4 id="myModalLabel">Reference Content (top 20 occurences)</h4>
	</div>
	<div class="modal-body">
		<div id="referenceContent">
				
		</div>
		<div class="pagination-centered"><p>Select a reference and the term will be populated into the search box</p></div>
		<div id="refWaiting" class="span2 offset1 pagination-centered" style="display: none;">
			<!--Regenerating autocomplete...<br />-->
			<img src="<?php echo base_url("resources/images/cafevariome/ajax-loader-alt.gif");?>" title="Loader" alt="Loader" />
		</div>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
	</div>
</div>

<div id="searchHelpModal" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">Additional Search Preferences</h3>
	</div>
	<div class="modal-body">

	</div>
	<div class="modal-footer">
		<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Apply</button>
	</div>
</div>
				
 <?php 
/////////////////
//
//  Render ontologies within tabs
//
////////////////
  
$sizeofarray=sizeof($ontologiesused);
if ($sizeofarray > 0) {
?>
<div id="phenotypeTreeModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">Phenotype Tree Search</h3>
	</div>
	<div class="modal-body">



		<label><h4>Select ontology and a term:</h4></label> <br /> 
		<div class="tabbable"> <ul class="nav nav-pills nav-stacked">
	<?php
    // get first ontology key and make the tab menu active
    reset($ontologiesused);
    $first_key = key($ontologiesused);
    echo '<li class="active"><a href="#' . $first_key . '_panel" data-toggle="tab">' . $first_key . '</a></li> ';

    // if more than one ontology, then add tabs
    if ($sizeofarray > 1) {
        unset($ontologiesused[$first_key]);
        foreach ($ontologiesused as $key => $val) {
            echo '<li><a href="#' . $key . '_panel" data-toggle="tab">' . $key . '</a></li> ';
        }
    }
    echo'</ul>  <div class="tab-content">';

    // get first ontology key and make the tab active
    echo '<div id="' . $first_key . '_panel" class="tab-pane active"><div class="pagination-left"><div id="' . $first_key . '_tree" class="onttree" align="left" style="height:200px;"></div></div></div>';

    // if more than one ontology, then add tab content
    if ($sizeofarray > 1) {
        foreach ($ontologiesused as $key => $val) {
            echo '<div id="' . $key . '_panel" class="tab-pane"><div class="pagination-left"><div id="' . $key . '_tree" class="onttree" align="left" style="height:200px;"></div></div></div>';
        }
    }
    echo' </div><!-- /.tab-content -->
</div><!-- /.tabbable -->
<br />';
?>
<!--	<button type="submit" class="btn" id="search" data-dismiss="modal"><i class="icon-search"></i> Discover Variants</button>-->
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
	</div>
<?php

}
?>

</div>