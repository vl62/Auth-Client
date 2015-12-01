<div class="container">
    <div class="row">  
        <div class="span6">  
            <ul class="breadcrumb">
                <?php if ($this->session->userdata('admin_or_curate') == "curate"): ?>
                    <li>  
                        <a href="<?php echo base_url() . "curate"; ?>">Curators Dashboard</a> <span class="divider">></span>  
                    </li>
                    <li>
                        <a href="<?php echo base_url() . "curate/variants"; ?>">Records</a> <span class="divider">></span>
                    </li>				
                <?php else: ?>
                    <li>  
                        <a href="<?php echo base_url() . "admin"; ?>">Dashboard Home</a> <span class="divider">></span>  
                    </li>
                    <li>
                        <a href="<?php echo base_url() . "admin/variants"; ?>">Records</a> <span class="divider">></span>
                    </li>
                <?php endif; ?>
                <li class="active">Bulk Import</li>
            </ul>  
        </div>  
    </div>
    <div class="row-fluid">
        <?php
        if (!is_writable(FCPATH . 'upload/')) {
            echo '<div class="alert alert-error">WARNING: Your upload directory is currently not writable by the webserver. In order to import records you must make this directory writable. Please change the permissions of the following directory:<br /><br />' . FCPATH . 'upload/' . "<br /><br />Please contact admin@cafevariome.org if you require help.</div><hr>";
        }
        ?>
        <div id="main_container">
            <h3>Import records for <?php echo $source; ?></h3>
            <p>Accepted file formats are tab delimited & Excel. Additional formats can be added on request, contact <a href="mailto:admin@cafevariome.org">admin@cafevariome.org</a> to discuss.</p> <!-- -->
            <p>You can generate Excel and tab-delimited templates in the "Import Templates" tab on the <a href="<?php echo base_url() . "admin/settings?activeTab=templates"; ?>"> following page</a>.</p>
            <!--<p>N.B. Headers in your import template MUST match up to a corresponding (case sensitive) field in the <?php // echo $this->config->item('feature_table_name');   ?> table.</p><br />-->
            <form method="post" name="variant_file_upload" id="variant_file_upload" enctype="multipart/form-data">
            <?php // echo form_open_multipart('variants/do_upload_new/');?>
                <p>Specify the file format</p>
                <?php
                $options = array(
//					'epad_new' => 'EPAD_new',
//					'excel' => 'Excel Full (.xls)',
                    'excel_core' => 'Excel Template',
//					'tab' => 'Tab Delimited Full',
                    'tab_core' => 'Tab Delimited Template',
                    // 'epad' => 'EPAD',
//                    'phenotype_test' => 'Phenotype Test',
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
                echo form_dropdown('sharing_policy', $options, 'restrictedAccess');
                ?><br/>
                
                <span class="btn btn-default btn-file" style="padding-top: 10px;">
                    Browse <input type="file" name="userfile" />
                </span>
                <br/><br/><br/>
                <div class="pagination-centered">
                    <button class="span2 btn btn-large btn-primary offset1" type="submit" id="buildQuery">Upload File</button>
                </div>
                
                <br/><br/>
                <div id="uploadStatus" class="hide">Uploading...</div>
                <input type="hidden" value="<?php echo $source ?>" name="source">
            </form>
        </div>
        <br />
    </div>
    <hr>
    <a href="<?php
    if ($this->session->userdata('admin_or_curate') == "curate") {
        echo base_url() . "curate/variants";
    } else {
        echo base_url() . "admin/variants";
    }
    ?>" class="btn" ><i class="icon-step-backward"></i> Go back</a>
</div>
<?php echo form_close(); ?>
