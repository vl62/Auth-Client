<?php if ( isset($variants_js_data) ) { echo "<script>$variants_js_data</script>\n"; } ?>
<?php if ( isset($terms_js_data) ) { echo "<script>$terms_js_data</script>\n"; } ?>
<?php if ( isset($ips_js_data) ) { echo "<script>$ips_js_data</script>\n"; } ?>
<?php if ( isset($variant_js_data) ) { echo "<script>$variant_js_data</script>\n"; } ?>

<div class="container">
	<div class="row">  
		<div class="span6">  
			<ul class="breadcrumb">  
				<li>  
					<a href="<?php echo base_url() . "admin";?>">Dashboard Home</a> <span class="divider">></span>  
				</li>
				<li class="active">Statistics</li>
			</ul>  
		</div>  
	</div>
	<div class="row">
		<div class="span12 pagination-centered">
			<!--<div class="well">-->
				<button type="button" class="btn" id="reset_stats"><i class="icon-trash"></i> Reset Statistics</button>
			<!--</div>-->
		</div>
	</div>
	<div class="row">
		<div class="span6 pagination-centered">
			<div class="page-header">
				<h3>Total Variants By Source</h3>
			</div>
			<div class="well">
				<div id="variantschart"></div>
			</div>
			<a href="<?php echo base_url() . "admin/stats_download/total_records";?>" type="button" class="btn"><i class="icon-download-alt"></i> Download Raw Log</a>
		</div>
		<div class="span6 pagination-centered">
			<div class="page-header">
				<h3>Top Search Terms</h3>
			</div>
			<div class="well">
				<div id="termschart"></div>
			</div>
			<a href="<?php echo base_url() . "admin/stats_download/search_terms";?>" type="button" class="btn"><i class="icon-download-alt"></i> Download Raw Log</a>
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="span6 pagination-centered">
			<div class="page-header">
				<h3>Top Visitor Locations</h3>
			</div>
			<div class="well">
				<div id="ipschart"></div>
			</div>
			<a href="<?php echo base_url() . "admin/stats_download/visitor_locations";?>" type="button" class="btn"><i class="icon-download-alt"></i> Download Raw Log</a>
		</div>
		<div class="span6 pagination-centered">
			<div class="page-header">
				<h3>Top Accessed Variants</h3>
			</div>
			<div class="well">
				<div id="variantchart"></div>
			</div>
			<a href="<?php echo base_url() . "admin/stats_download/accessed_records";?>" type="button" class="btn"><i class="icon-download-alt"></i> Download Raw Log</a>
		</div>
	</div>
	<br />
</div>