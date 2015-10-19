<div class="container" style="margin-bottom: 200px;">
    <div class="row-fluid" id="networkSelectForSearch" data-networkCount="<?php echo count($networks)?>">
        <div class="span12 pagination-centered">
            <h2>Select which network you would like to search</h2><hr>
            <form method="post">
                <div class="modal-body pagination-centered">
                    <select class="input-xlarge" name="selectNetwork" id="selectNetwork" style="margin-bottom:10px">
                        <option></option>
                        <?php foreach ($networks as $network_name => $network_key) : ?>
                            <option value="<?php echo $network_key; ?>"><?php echo $network_name; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <br/>
                    <button class="btn btn-primary" id="network_select" type="button">Submit</button>
                </div>
                <input type="hidden" value="<?php echo $type;?>" id="type" />
            </form>
        </div> <!-- end span12 pagination-centered -->
    </div> <!-- end row-fluid -->    
    
    
    <!-- Modal -->
    <div id="noNetworkModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">This installation is not part of any networks</h4>
            </div>
            <div class="modal-body pagination-centered">
                <a type="button" class="btn btn-primary" href="<?php echo base_url();?>">Go Back</a>
            </div>
            
          </div>

        </div>
    </div>
    
</div> <!-- end container -->

<script>
    $(document).ready(function() {
        $("#selectNetwork").select2({placeholder: "--Select a network--"});
        if($("#networkSelectForSearch").attr('data-networkCount') == 0) {
            $('#noNetworkModal').modal({backdrop: 'static'});
            $('#noNetworkModal').modal('show');
        }
        
        $("#selectNetwork").change(function() {
        });
        
        $("#network_select").click(function() {
            if($("#selectNetwork").val()) 
                window.location = baseurl + "discover/query_builder/" + $("#selectNetwork").val();
            else 
                alert("Select a network to search");
        });
        
    });
</script>