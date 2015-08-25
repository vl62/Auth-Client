<div class="container" style="margin-bottom: 200px;">
    <div class="row-fluid" id="genotype_phenotype">
        <div class="span12 pagination-centered">
            <h2>Query Builder</h2><hr>
            <div class="" id="genotypeContainer" style="">
                <div class="row-fluid">
                    <div class="span12 pagination-centered" style="">
                        <button class="btn btn-large input-block-level btn-info btn-collapse" data-collapseStatus="false" style="text-align: left">
                            DNA
                            <i class="icon-chevron-left" style="float: right"></i>
                        </button>
                    </div>
                </div>
                
                <div class="collapse" id="dnaContainer" data-type='dna'>
                </div> <!-- end DNA -->
                
                <div class="btn-group btn-toggle logic" id="logic_dna_gene" style="margin:20px 0">
                        <a class="btn btn-medium btn-default disabled">AND</a>
                        <a class="btn btn-medium btn-primary active disabled">OR</a>
                </div>
                
                <div class="row-fluid">
                    <div class="span12 pagination-centered" style="">
                        <button class="btn btn-large input-block-level btn-info btn-collapse" data-collapseStatus="false" style="text-align: left">
                            Gene Symbol
                            <i class="icon-chevron-left" style="float: right"></i>
                        </button>
                    </div>
                </div>

                <div class="collapse" id="geneSymbolContainer" data-type='geneSymbol'>
                </div> 
                <!-- end Gene Symbol -->
                
                <div class="btn-group btn-toggle logic" id="logic_gene_hgvs" style="margin:20px 0">
                        <a class="btn btn-medium btn-default disabled">AND</a>
                        <a class="btn btn-medium btn-primary active disabled">OR</a>
                </div>
                
                <div class="row-fluid">
                    <div class="span12 pagination-centered" style="">
                        <button class="btn btn-large input-block-level btn-info btn-collapse" data-collapseStatus="false" style="text-align: left">
                            Hgvs
                            <i class="icon-chevron-left" style="float: right"></i>
                        </button>
                    </div>
                </div>

                <div class="collapse" id="hgvsContainer" data-type='hgvs'>
                </div> 
                <!-- end Hgvs -->
            </div> <!-- end Genotype -->
            
            <div class="btn-group btn-toggle logic" id="logic_genotype_phenotype" style="margin:20px 0">
                        <a class="btn btn-medium btn-default disabled">AND</a>
                        <a class="btn btn-medium btn-primary active disabled">OR</a>
            </div>
            
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
                
            </div> <!-- end Phenotype -->
            <br>
            
            <div class="row-fluid" id="reset_buildQuery">
                <div class="pagination-centered">
                    <a class="span2 offset4 btn btn-large clear_all_textbox"><i class="icon-trash"></i> Reset</a>
                    <a class="span2 btn btn-large btn-primary" id="buildQuery"><i class="icon-search"></i> Build Query</a>
                </div>
            </div> <!-- end Build Query -->
            
        </div> <!-- end span12 pagination-centered -->
    </div> <!-- end row-fluid -->
    
    <div class="modal hide fade in" style="display: none; height: 655px;" id="modalInfo" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close closeModal" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Information about operators</h4>
                </div>
                <div class="modal-body">
                    <h5>EXACT</h5>
                    <h6 class="form-control">Data feature must have same start and stop values as the query</h6>
                    
                    <h5>BEGIN_AT_START</h5>
                    <h6 class="form-control">Data feature must have same start as the query</h6>
                    
                    <h5>BEGIN_BETWEEN</h5>
                    <h6 class="form-control">Data feature must have a start value that is greater than or equal to the start in the query and also is less than or equal to the stop in the query</h6>
                    
                    <h5>ONLY_BEGIN_BETWEEN</h5>
                    <h6 class="form-control">Data feature must have a start value that is greater than or equal to the start in the query and also is less than or equal to the stop in the query, and a stop value that is greater than the stop in the query</h6>
                    
                    <h5>END_AT_STOP</h5>
                    <h6 class="form-control">Data feature must have same stop as the query</h6>
                    
                    <h5>END_BETWEEN</h5>
                    <h6 class="form-control">Data feature must have a stop value that is greater than or equal to the start in the query and also is less than or equal to the stop in the query</h6>
                    
                    <h5>ONLY_END_BETWEEN</h5>
                    <h6 class="form-control">Data feature must have a stop value that is greater than or equal to the start in the query and also is less than or equal to the stop in the query, and a start value that is less than the start in the query</h6>
                    
                    <h5>BEGIN_AND_END_BETWEEN</h5>
                    <h6 class="form-control">Both the start and the stop values of the Data feature must be greater than or equal to the start in the query and also be less than or equal to the stop in the query</h6>
                    
                    <h5>EXCEED</h5>
                    <h6 class="form-control">Data feature must have a lower start value and a higher stop value than the query.</h6>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default closeModal" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <div id="waiting" style="display: none; text-align: center;">
            <br />Please wait...<br />
            <img src="<?php echo base_url("resources/images/cafevariome/ajax-loader.gif");?>" title="Loader" alt="Loader" />
    </div>
    <div id="query_result"></div>
    <input type="hidden" value="<?php echo $network_key; ?>" id="network_key"/>
    
</div> <!-- end container -->

<div id="loader"></div>

