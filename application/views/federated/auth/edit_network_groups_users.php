<div class="container">
    <!--<div class="container-fluid">-->
    <div class="row">  
        <div class="span6">  
            <ul class="breadcrumb">  
                <li>  
                    <a href="<?php echo base_url() . "admin";?>">Dashboard Home</a> <span class="divider">></span>  
                </li>
                <li>
                    <a href="<?php echo base_url() . "groups";?>">Groups</a> <span class="divider">></span>
                </li>
                <li class="active">Edit User Network Groups</li>
            </ul>  
        </div>  
    </div>
    <div class="row-fluid">
        <div class="span10 offset1 pagination-centered">
            <div class="well">
                <?php if($group_type == 'master'): ?>
                    <h2>Edit Users for Network: <?php echo $name; ?></h2>
                <?php else: ?>
                    <h2>Edit Users for Group: <?php echo $name; ?></h2>
                <?php endif; ?>

                <?php echo form_open("auth_federated/edit_user_network_groups", array('name' => 'editUser')); ?>
    
                <?php echo form_hidden(array('installation_key' => $this->config->item('installation_key'))); ?>    

                    <?php if(isset($users) || isset($group_users)): ?>

                        <h3 style="padding-top: 10px;">Users</h3>    
                        <div class="row-fluid" >
                            <div class="span5 pagination-centered">
                                <select size="10" multiple id="mng_left" style="padding-top: 10px;">
                                </select>
                            </div>
                            <div class="span2 pagination-centered" style="margin-top: 25px;">
                                <br><input type="button" class="form-control btn btn-success btn-lg btn-block" style="color: black; padding-left: 10px; padding-right: 10px;" value="Add &gt;&gt;"/><br>
                                <input type="button" class="form-control btn btn-danger btn-lg btn-block" style="color: black; padding-left: 10px; padding-right: 10px;" value="&lt;&lt; Remove"/>
                            </div>
                            <div class="span5 pagination-centered">
                                <select size="10" multiple id="mng_right" name="groups[]" class="groupsSelected" style="padding-top: 10px;">
                                </select>
                            </div>
                        </div>

                        <?php if(isset($users)) : foreach ($users as $user): ?>
                                <script type="text/javascript">
                                    $("#mng_left").append($("<option></option>")
                                    .attr("value",'<?php echo $user['id']; ?>')
                                    .text('<?php echo $user['username']; ?>')); 
                                </script>
                        <?php endforeach; endif; ?>

                        <?php if(isset($group_users)) : foreach ($group_users as $group_user): ?>
                                <script type="text/javascript">
                                    $("#mng_right").append($("<option></option>")
                                    .attr("value",'<?php echo $group_user['id']; ?>')
                                    .text('<?php echo $group_user['username']; ?>')); 
                                </script>
                        <?php endforeach; endif;?>

                    <?php else: ?>
                        <p><span class="label label-important">There are no users present in this group.</span></p>
                    <?php endif; ?>

                    <?php if(isset($sources_left) || isset($sources_right)): ?>

                        <h3 style="padding-top: 10px;">Sources</h3>    
                        <div class="row-fluid" >
                            <div class="span5 pagination-centered">
                                <select size="10" multiple id="sources_left" style="padding-top: 10px;"></select>
                            </div>
                            <div class="span2 pagination-centered" style="margin-top: 25px;">
                                <br><input type="button" class="form-control btn btn-success btn-lg btn-block" style="color: black; padding-left: 10px; padding-right: 10px;" value="Add &gt;&gt;"/><br>
                                <input type="button" class="form-control btn btn-danger btn-lg btn-block" style="color: black; padding-left: 10px; padding-right: 10px;" value="&lt;&lt; Remove"/>
                            </div>
                            <div class="span5 pagination-centered">
                                <select size="10" multiple id="sources_right" name="sources[]" class="sourcesSelected" style="padding-top: 10px;"></select>
                            </div>
                        </div>

                        <?php if(isset($sources_left)) : foreach ($sources_left as $key => $value): ?>
                                <script type="text/javascript">
                                    $("#sources_left").append($("<option></option>")
                                    .attr("value",'<?php echo $key ?>')
                                    .text('<?php echo $value ?>')); 
                                </script>
                        <?php endforeach; endif; ?>

                        <?php if(isset($sources_right)) : foreach ($sources_right as $key => $value): ?>
                                <script type="text/javascript">
                                    $("#sources_right").append($("<option></option>")
                                    .attr("value",'<?php echo $key ?>')
                                    .text('<?php echo $value ?>')); 
                                </script>
                        <?php endforeach; endif;?>

                    <?php else: ?>
                         <?php if($group_type != 'master'): ?>
                            <p><span class="label label-important">There are no sources present in this installation.</span></p>
                        <?php endif; ?>
                    <?php endif; ?>

                    
            
                    <br /><br /><br />

                <?php echo form_hidden('isMaster', $isMaster); ?>                
                <?php echo form_hidden('id', $user_id); ?>
                <?php echo form_hidden($csrf); ?>
                <p><button type="submit" onclick="edit_user_network_groups_sources();" name="submit" class="btn btn-primary"><i class="icon-user"></i>  Save</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                
                <?php if($group_type == 'master'): ?>
                    <a href="<?php echo base_url() . "networks"; ?>" class="btn" ><i class="icon-step-backward"></i> Go back</a></p>
                <?php else: ?>
                    <a href="<?php echo base_url() . "groups"; ?>" class="btn" ><i class="icon-step-backward"></i> Go back</a></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>



<?php echo form_close(); ?>