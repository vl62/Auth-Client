<div class="container">
	<div class="row">  
		<div class="span6">  
			<ul class="breadcrumb">  
				<li>  
					<a href="<?php echo base_url() . "admin";?>">Dashboard Home</a> <span class="divider">></span>  
				</li>
				<li>  
					<a href="<?php echo base_url() . "admin/settings";?>">Settings</a> <span class="divider">></span>  
				</li>
				<li class="active">Beacon Settings</li>
			</ul>  
		</div>  
	</div>
	<div class="page-header pagination-centered">
		<h2>Beacon Settings</h2>
	</div>
	<div class="row">
		<div class="span6 offset3">
			<div class="well" >
				<div class="slider beacon_slider" >
					<h4>openAccess beacon:</h4><br />
					<p>
						<?php if ( $sharing_policies_statuses['openAccess'] ): ?>
							<input type="checkbox" name="openAccess_beacon" id="openAccess" class="beaconenabled-beacondisabled" checked/>
						<?php else: ?>
							<input type="checkbox" name="openAccess_beacon" id="openAccess" class="beaconenabled-beacondisabled" />
						<?php endif; ?>
					</p>
					<hr>
					<h4>linkedAccess beacon:</h4><br />
					<p>
						<?php if ( $sharing_policies_statuses['linkedAccess'] ): ?>
							<input type="checkbox" name="linkedAccess_beacon" id="linkedAccess" class="beaconenabled-beacondisabled" checked/>
						<?php else: ?>
							<input type="checkbox" name="linkedAccess_beacon" id="linkedAccess" class="beaconenabled-beacondisabled" />
						<?php endif; ?>
					</p>
					<hr>
					<h4>restrictedAccess beacon:</h4><br />
					<p>
						<?php if ( $sharing_policies_statuses['restrictedAccess'] ): ?>
							<input type="checkbox" name="restrictedAccess_beacon" id="restrictedAccess" class="beaconenabled-beacondisabled" checked/>
						<?php else: ?>
							<input type="checkbox" name="restrictedAccess_beacon" id="restrictedAccess" class="beaconenabled-beacondisabled" />
						<?php endif; ?>
					</p>
					<hr>
					<p>The settings above enable or disable querying of your data by the Global Alliance for Genomics and Health (GA4GH) Beacon project. If you have enabled any of the options above your data will be queried for a single genomic position and a specific allele by searches run from the GA4GH Beacon of Beacons (only a yes/no answer will be returned).</p>
					<p><a href="http://ga4gh.org/#/beacon" target="_blank">Read more about the Beacon project</a></p>
					
				</div>
			</div>
		</div>
	</div>
</div><!--/.fluid-container-->