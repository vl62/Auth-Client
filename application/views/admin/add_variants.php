<div class="container">
	<div class="row">  
		<div class="span6">  
			<ul class="breadcrumb">
				<?php if ($this->session->userdata('admin_or_curate') == "curate"): ?>
				<li>  
					<a href="<?php echo base_url() . "curate";?>">Curators Dashboard</a> <span class="divider">></span>  
				</li>
				<li>
					<a href="<?php echo base_url() . "curate/variants";?>">Records</a> <span class="divider">></span>
				</li>				
				<?php else: ?>
				<li>  
					<a href="<?php echo base_url() . "admin";?>">Dashboard Home</a> <span class="divider">></span>  
				</li>
				<li>
					<a href="<?php echo base_url() . "admin/variants";?>">Records</a> <span class="divider">></span>
				</li>
				<?php endif; ?>
				<li class="active">Bulk Import</li>
			</ul>  
		</div>  
	</div>
	<div class="row-fluid">
		<?php if ( ! is_writable(FCPATH.'upload/')) { echo '<div class="alert alert-error">WARNING: Your upload directory is currently not writable by the webserver. In order to import records you must make this directory writable. Please change the permissions of the following directory:<br /><br />' . FCPATH .'upload/' . "<br /><br />Please contact admin@cafevariome.org if you require help.</div><hr>"; }?>
		<div id="main_container">
			<h3>Import records for <?php echo $source; ?></h3>
			<p>Accepted file formats are tab delimited & Excel. Additional formats can be added on request, contact <a href="mailto:admin@cafevariome.org">admin@cafevariome.org</a> to discuss.</p> <!-- -->
			<p>You can generate Excel and tab-delimited templates in the "Import Templates" tab on the <a href="<?php echo base_url() . "admin/settings?activeTab=templates";?>"> following page</a>.</p>
			<!--<p>N.B. Headers in your import template MUST match up to a corresponding (case sensitive) field in the <?php // echo $this->config->item('feature_table_name'); ?> table.</p><br />-->
			<form action="<?php echo base_url("variants/do_upload/" . $source); ?>" method="post" enctype="multipart/form-data">
				<input type="file" name="userfile" class="fileUpload" multiple>
				<button id="px-submit" type="submit">Upload</button>
				<button id="px-clear" type="reset">Clear</button>
				<br />
				<p>Specify the file format</p>
				<?php
				$options = array(
//					'epad' => 'EPAD',
//					'epad_new' => 'EPAD_new',
//					'excel' => 'Excel Full (.xls)',
					'excel_core' => 'Excel Template',					
//					'tab' => 'Tab Delimited Full',
					'tab_core' => 'Tab Delimited Template',
//					'dmudb' => 'DMuDB tab delimited (.txt)',
//					'vcf' => 'VCF',
//					'lovd2' => 'LOVD2',
//					'nl' => 'NL',
//					'alamut' => 'Alamut',
                );
				echo form_dropdown('fileformat', $options, 'excel');
				?>

				<p>Default sharing policy (if none specified in input file):</p>
				<?php
				$options = array(
                  'openAccess' => 'openAccess',
				  'linkedAccess' => 'linkedAccess',
                  'restrictedAccess' => 'restrictedAccess'
                );
				echo form_dropdown('sharing_policy', $options, 'openAccess');
				?>
				<!--<br /><p>Check variants with Mutalyzer and fetch genomic coordinates:</p>-->
				<?php
//				$options = array(
//                  'no' => 'no',
//                  'yes' => 'yes'
//                );
//				echo form_dropdown('mutalyzer_check', $options, 'no');
				?>
			</form>
		</div>
		<br />
	</div>
	<hr>
	<a href="<?php if ($this->session->userdata('admin_or_curate') == "curate") { echo base_url() . "curate/variants"; } else { echo base_url() . "admin/variants"; }?>" class="btn" ><i class="icon-step-backward"></i> Go back</a>
</div>
<?php echo form_close(); ?>
