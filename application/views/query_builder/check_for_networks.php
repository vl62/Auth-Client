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
            if($("#type").val() === "query_builder") {
				// Get the selected
//				alert("val -> " + $("#selectNetwork").val());
				window.location = baseurl + "discover/query_builder/" + $("#selectNetwork").val();
//                $('form').attr('action', baseurl + "discover/query_builder").submit();
            } else if($("#type").val() === "standard_search") {
                window.location = baseurl + "discover/index/" + $("#selectNetwork").val();
            } else {
                
                // error;
            }
        });
    });
</script>