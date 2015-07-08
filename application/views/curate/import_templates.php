<div class="container">
	<div class="row">  
		<div class="span6">  
			<ul class="breadcrumb">  
				<li>  
					<a href="<?php echo base_url() . "curate";?>">Curators Dashboard</a> <span class="divider">></span>  
				</li>
				<li class="active">Import Templates</li>
			</ul>  
		</div>  
	</div>
	<div class="page-header">
		<h3>Import Templates</h3>
	</div>

	<div class="row">
		<div class="span6">
			<div class="pagination-centered" >
				<h4>Generate Import Templates</h4>
			</div>
			<div class="row-fluid">
				<p>Use the buttons below to generate import templates that can be populated with your data and then imported using the bulk variant import interface.</p>
				<!--<p>"Full" templates represent your current database structure and contains all available fields.</p>-->
				<p>Import templates contain core fields from the database that the user wishes to populate with their data. </p>
				<p>Core fields present in the import templates can be changed below by clicking on the "Set Core Fields" button. N.B. the column names in the template files should NOT be changed.</p>
				<hr>
				<div class="pagination-centered">
					<a href="<?php echo base_url() . "admin/create_excel_sheet_core";?>" class="btn" rel="popover" data-content="Generates a slimmed down Excel document with only core fields. DO NOT edit these column names in the Excel sheet or alter the format of the Excel document (.xls)" data-original-title="Excel Sheet Core"><i class="icon-list-alt"></i>  Excel Sheet (Core)</a>
					<?php echo nbs(5); ?>
					<a href="<?php echo base_url() . "admin/create_tab_delimited_core";?>" class="btn" rel="popover" data-content="Generates a slimmed down tab delimited file with only core fields. DO NOT edit these column names." data-original-title="Tab Delimited Core"><i class="icon-list-alt"></i>  Tab Delimited (Core)</a>
				</div>
				<br />
			</div>
		</div>
	</div>
</div><!--/.fluid-container-->