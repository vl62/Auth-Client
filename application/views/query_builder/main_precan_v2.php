<div class="container" style="margin-bottom: 200px;">
    <div class="row-fluid" id="genotype_phenotype">
        <div class="span12 pagination-centered">
            <h2>Query Builder</h2><hr>

            <ul class="nav nav-tabs">
                <?php if($qb_basic): ?>
                    <li style="width: 33%;"><a href="#tab-phenotype" data-toggle="tab">Basic Query</a></li>
                <?php endif; ?>
                <?php if($qb_precan): ?>
                    <li style="width: 33%;"><a href="#tab-precanned" data-toggle="tab">Precanned Query</a></li>
                <?php endif; ?>
                <?php if($qb_advanced): ?>
                    <li style="width: 33%;"><a href="#tab-advanced" data-toggle="tab">Advanced Query</a></li>
                <?php endif; ?>
            </ul>

            <div class="tab-content">
                <div id="tab-phenotype" class="tab-pane">
                    <div class="" id="phenotypeBox">
                        <div class="row-fluid">
                            <div class="span12 pagination-centered">
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

                <div id="tab-precanned" class="tab-pane">
                    <div class="" id="genotypeContainer">
                        
                        <div class="row-fluid">
                            <div class="span12 pagination-centered">
                                <button class="btn btn-large input-block-level btn-info btn-collapse" id="collapsePrecanned" data-collapseStatus="false" style="text-align: left">
                                    Precanned Queries
                                    <i class="icon-chevron-left" style="float: right"></i>
                                </button>
                            </div>
                        </div>

                        <div class="collapse" id="precannedContainer" data-type='precanned'>
                            <div class="row-fluid">
                                <div class="span3 offset2">
                                    <select name="source" class="input-large">
                                        <option value="-1">Select a source</option> 
                                        <?php if(isset($precanned_queries)): ?>
                                            <?php foreach ($precanned_queries as $key => $value): ?>
                                                <option value="<?php echo $value; ?>"><?php echo $value; ?></option>    
                                            <?php endforeach;?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="span3">
                                    <select name="case_control" class="input-large">
                                        <option value="-1">Select case/control</option>
                                        <option value="case">case</option>
                                        <option value="control">control</option>
                                    </select>
                                </div>
                                <div class="span2">
                                    <label class="checkbox inline">
                                        <input type="checkbox" name="show_all" value="show_all"> Show All
                                    </label>
                                </div>
                            </div>
                            <hr>

                            <div class="row-fluid">
                                <div class="span4 offset8">
                                    <div class="input-prepend">
                                        <span class="add-on"><a href="#">Filter</a></span>
                                        <input id="filter" type="text">
                                    </div>
                                </div>
                                <hr>
                                <div class="span12" style="overflow: scroll; width: 95%; margin-left: 20px;">
                                    
                                    <table class="table-striped" id="precan_table" style="table-layout:fixed; width: 900px;">
                                        <thead>
                                            <tr>
                                                <th style="width: 60px; text-align: center;"></th>
                                                <th style="width: 400px; text-align: center;">Query</th>
                                                <th style="width: 150px; text-align: center;">User</th>
                                                <th style="width: 100px; text-align: center;">Date &amp; Time</th>
                                                <th style="width: 100px; text-align: center;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="searchable">
                                            <tr></tr>
                                            <?php if(isset($precanned_queries) && isset($precan_active)): ?>
                                                <?php foreach ($precan_active as $val): ?>
                                                    <tr class="pre_active">
                                                        <td><input type="radio" name="precannedQueries" value=" <?php echo $val['api'] ?> ">
                                                            <a style="margin-left: 10px;" class="btn btn-small btn-info precan_info" href="#" rel="popover" data-content="<?php echo $val['notes'] == "" ? "No notes available" : $val['notes'] ?>" data-original-title=""><i class="icon-info-sign"></i></a>
                                                        </td>
                                                        <td><div><?php echo $val['queryString'] ?></div></td>
                                                        <td><div><?php echo $val['user_email'] ?></div></td>
                                                        <td><div><?php echo $val['date_time'] ?></div></td>
                                                        <td>
                                                            <a class="btn btn-small btn-warning precan_deactivate" href="#"><i class="icon-remove"></i></a>
                                                            <a class="btn btn-small btn-danger precan_delete" href="#"><i class="icon-trash"></i></a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>

                                            <?php if(isset($precanned_queries) && isset($precan_active)): ?>
                                                <?php foreach ($precan_inactive as $val): ?>
                                                    <tr class="pre_inactive hide">
                                                        <td><input type="radio" disabled name="precannedQueries" value=" <?php echo $val['api'] ?> ">
                                                            <a style="margin-left: 10px;" class="btn btn-small btn-info precan_info" href="#" rel="popover" data-content="<?php $val['notes'] == "" ? "No notes available" : $val['notes'] ?>" data-original-title=""><i class="icon-info-sign"></i></a>
                                                        </td>
                                                        <td><div><?php echo $val['queryString'] ?></div></td>
                                                        <td><div><?php echo $val['user_email'] ?></div></td>
                                                        <td><div><?php echo $val['date_time'] ?></div></td>
                                                        <td>
                                                            <a class="btn btn-small btn-success precan_activate" href="#"><i class="icon-ok"></i></a>
                                                            <a class="btn btn-small btn-danger precan_delete" href="#"><i class="icon-trash"></i></a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div> 
                        
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

                <div id="tab-advanced" class="tab-pane">
                   <div class="" id="advancedBox">
                        <div class="row-fluid">
                            <div class="span12 pagination-centered">
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
                </div> <!-- end Advanced Queries -->
            </div>
        </div> <!-- end span12 pagination-centered -->
    </div> <!-- end row-fluid -->

    <input type="hidden" value="<?php echo $network_key;    ?>" id="network_key"/>

</div> <!-- end container -->

<div id="loader"></div>

<div class="modal hide fade in" style="display: none;" id="modal_add_query" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close closeModal" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Save Precan Query</h4>
            </div>
            <div class="modal-body" id="email_list">
                <div class="row-fluid">
                    <div class="span9 offset3">
                        <p class="hide" style="color: red;" id="err_source">Required</p>
                        <select name="save_source" class="input-large">
                            <option value="-1">Select a source</option>
                        </select> <br> <br>
                        <p class="hide" style="color: red;" id="err_case_control">Required</p>
                        <select name="save_case_control" class="input-large">
                            <option value="-1">Select case/control</option>
                            <option value="case">case</option>
                            <option value="control">control</option>
                        </select> <br> <br>

                        Notes:
                        <textarea name="notes" rows="7" cols="10"></textarea> <br> <br>
                        <button type="button" class="center btn btn-primary" id="save_query" style="margin-left:50px;">Save</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default closeModal" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<input type="hidden" name="create_precan_query" value="<?php echo $create_precan_query; ?>">
