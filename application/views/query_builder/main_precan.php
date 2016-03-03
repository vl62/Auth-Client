<div class="container" style="margin-bottom: 200px;">
    <div class="row-fluid" id="genotype_phenotype">
        <div class="span12 pagination-centered">
            <h2>Query Builder</h2><hr>

            <ul class="nav nav-tabs">
                <li class="active" style="width: 33%;"><a href="#tab-phenotype" data-toggle="tab">Phenotype Query</a></li>
                <li style="width: 33%;"><a href="#tab-precanned" data-toggle="tab">Precanned Query</a></li>
                <li style="width: 33%;"><a href="#tab-advanced" data-toggle="tab">Advanced Query</a></li>
            </ul>

            <div class="tab-content">
                <div id="tab-phenotype" class="tab-pane active">
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
                </div>

                <div id="tab-advanced" class="tab-pane">
                   <div class="" id="advancedBox">
                        <div class="row-fluid">
                            <div class="span12 pagination-centered" style="">
                                <button class="btn btn-large input-block-level btn-info btn-collapse" id="isPhenotype" data-collapseStatus="false" style="text-align: left">
                                    Advanced Phenotype Queries
                                    <i class="icon-chevron-left" style="float: right"></i>
                                </button>
                            </div>
                        </div>

                        <div class="collapse" id="advancedContainer" data-type='advanced'>
                        </div>
                   </div> <!-- end Advanced Section -->
                    
                    <div class="row-fluid" style="margin: 50px;">
                        <div class="span4 offset2 pagination-centered">
                            <input class="input-xxlarge" type="text" placeholder="Enter your query string" id="queryString" style="text-align: center; text-transform: uppercase;">
                        </div>
                    </div>

                    <br>
                    <div class="row-fluid">
                        <div class="pagination-centered">
                            <a class="span2 offset4 btn btn-large" id="reset_advanced"><i class="icon-trash"></i> Reset</a>
                            <a class="span2 btn btn-large btn-primary" id="buildQuery_advanced"><i class="icon-search"></i> Build Query</a>
                        </div>
                    </div> <!-- end Build Query -->

                    <div id="waiting_advanced" style="display: none; text-align: center;">
                        <br />Please wait...<br />
                        <img src="<?php echo base_url("resources/images/cafevariome/ajax-loader.gif");   ?>" title="Loader" alt="Loader" />
                    </div><br><br>
                    <div id="query_result_advanced"></div>
                </div>

                <div id="tab-precanned" class="tab-pane">
                    <div class="" id="genotypeContainer" style="">
                        <?php if(isset($precanned_queries)): ?>
                        <div class="row-fluid">
                            <div class="span12 pagination-centered" style="">
                                <button class="btn btn-large input-block-level btn-info btn-collapse" id="collapsePrecanned" data-collapseStatus="false" style="text-align: left">
                                    Precanned Queries
                                    <i class="icon-chevron-left" style="float: right"></i>
                                </button>
                            </div>
                        </div>

                        <div class="collapse" id="precannedContainer" data-type='precanned'>
                            <?php 
                                foreach ($precanned_queries as $key => $value) { ?>
                                    <div class="row-fluid type_sample">
                                        <div class="span7 offset2 pagination-centered">
                                        <label class="radio">
                                            <input type="radio" name="precannedQueries" value="<?php echo htmlspecialchars(json_encode($value)); ?>">
                                                <?php echo $value['queryString']; ?>
                                            </label>
                                        </div>
                                    </div>
                                <?php }
                             ?>
                        </div> 
                        <?php endif; ?>

                        <br>
                        <div class="row-fluid">
                            <div class="pagination-centered">
                                <a class="span2 offset4 btn btn-large" id="reset_precanned"><i class="icon-trash"></i> Reset</a>
                                <a class="span2 btn btn-large btn-primary" id="buildQuery_precanned"><i class="icon-search"></i> Build Query</a>
                            </div>
                        </div> <!-- end Build Query -->
                    </div> <!-- end Precanned Queries -->

                    <div id="waiting_precanned" style="display: none; text-align: center;">
                        <br />Please wait...<br />
                        <img src="<?php echo base_url("resources/images/cafevariome/ajax-loader.gif");   ?>" title="Loader" alt="Loader" />
                    </div><br><br>
                    <div id="query_result_precanned"></div>
                </div>
            </div>
        </div> <!-- end span12 pagination-centered -->
    </div> <!-- end row-fluid -->

    <input type="hidden" value="<?php echo $network_key;    ?>" id="network_key"/>

</div> <!-- end container -->

<div id="loader"></div>

