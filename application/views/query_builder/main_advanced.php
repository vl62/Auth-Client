<div class="container" style="margin-bottom: 200px;">
    <div class="row-fluid" id="genotype_phenotype">
        <div class="span12 pagination-centered">
            <h2>Query Builder</h2><hr>
            
            <div class="" id="phenotypeBox">
                <div class="row-fluid">
                    <div class="span12 pagination-centered" style="">
                        <button class="btn btn-large input-block-level btn-info btn-collapse" id="isPhenotype" data-collapseStatus="false" style="text-align: left">
                            Phenotype
                            <i class="icon-chevron-left" style="float: right"></i>
                        </button>
                    </div>
                </div>

                <div class="collapse" id="phenotypeContainer" data-type='phenotype'>
                </div>
            </div> <!-- end Phenotype Section -->
            
            <div class="row-fluid" style="margin: 50px;">
                <div class="span4 offset2 pagination-centered">
                    <input class="input-xxlarge" type="text" placeholder="Enter your query string" id="queryString" style="text-align: center;">
                </div>
            </div>
            

            <br>
            <div class="row-fluid">
                <div class="pagination-centered">
                    <a class="span2 offset4 btn btn-large" id="reset_phenotype"><i class="icon-trash"></i> Reset</a>
                    <a class="span2 btn btn-large btn-primary" id="buildQuery_phenotype"><i class="icon-search"></i> Build Query</a>
                </div>
            </div> <!-- end Build Query -->

            <div id="waiting_phenotype" style="display: none; text-align: center;">
                <br />Please wait...<br />
                <img src="<?php echo base_url("resources/images/cafevariome/ajax-loader.gif");   ?>" title="Loader" alt="Loader" />
            </div><br><br>
            <div id="query_result_phenotype"></div>
        </div> <!-- end span12 pagination-centered -->
    </div> <!-- end row-fluid -->

    <input type="hidden" value="<?php echo $network_key;    ?>" id="network_key"/>

</div> <!-- end container -->

<div id="loader"></div>

