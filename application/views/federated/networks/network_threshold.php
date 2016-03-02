<div class="container">
    <!--<div class="container-fluid">-->
    <div class="row">  
        <div class="span6">  
            <ul class="breadcrumb">  
                <li>  
                    <a href="<?php echo base_url() . "admin";?>">Dashboard Home</a> <span class="divider">></span>  
                </li>
                <li>
                    <a href="<?php echo base_url() . "networks";?>">Networks</a> <span class="divider">></span>
                </li>
                <li class="active">Edit Network Threshold</li>
            </ul>  
        </div>  
    </div>
    <div class="row-fluid">
        <div class="span6 offset3 pagination-centered" style="padding-top: 50px;">
            <div class="well" style="padding-top: 50px;">
                <b>Set network threshold value: </b> <input class="form-control" type="text" placeholder="" id="threshold" style="text-align: left;" value=<?php echo $network_threshold; ?>> 
                <p style="padding-top: 25px; padding-left: 105px;"><button type="button" id="btn_save_threshold" class="btn btn-primary"><i class="icon-lock"></i>  Save</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="<?php echo base_url() . "networks"; ?>" class="btn" ><i class="icon-step-backward"></i> Go back</a></p>
            </div>
        </div>
    </div>
</div>

<input type="hidden" name="" value="<?php echo $network_key; ?>" id="threshold_network_key">