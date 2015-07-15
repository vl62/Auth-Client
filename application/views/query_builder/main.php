<div class="container" style="margin-bottom: 200px;">
    
    <div class="row-fluid" id="genotype_phenotype">
        <div class="span12 pagination-centered">
            <h2>Query Builder</h2><hr>
            <div class="boxed" id="genotypeContainer" style="">
                <div class="row-fluid">
                    <div class="span2 pagination-left"><h3>Genotype</h3></div>
                </div>
                
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
            
            <div class="boxed" id="phenotypeBox">
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
	<div id="waiting" style="display: none; text-align: center;">
		Please wait...<br />
		<img src="<?php echo base_url("resources/images/cafevariome/ajax-loader.gif");?>" title="Loader" alt="Loader" />
	</div>
    <div id="query_result"></div>
    <input type="hidden" value="<?php echo $network_key; ?>" id="network_key"/>
    
</div> <!-- end container -->