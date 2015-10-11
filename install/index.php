<?php
//error_reporting(E_NONE); //Setting this to E_ALL showed that that cause of not redirecting were few blank lines added in some php files.
session_start();

// Load the classes and create the new objects
require_once('includes/core_class.php');
require_once('includes/database_class.php');

$core = new Core();
$database = new Database();

if ($_POST):
//	error_log(print_r($_POST, 1));
    if ($core->validate_post($_POST) == true) {
//		for($i=0;$i<=2;$i++) {
//			error_log("at -> " . $i);
//			sleep(1);
//		}
        // Generate installation key
        $mdstring = md5(uniqid(rand(), true));
//		error_log("installation_key -> $mdstring");

        $errors = array();
        // First create the database, then check strict mode is off, then create tables, then create admin user, then write config file - if anything fails at each stage it will not proceed and report the error message
        error_log("0%");
        if ($database->create_database($_POST) == false) {
            $message = $database->getErrorMessage();
            $errors['create_db'] = $message;
        } else {
            error_log("20%");
            if ($database->check_for_strict_mode($_POST) == false) {
                $message = $database->error_message;
                $errors['strict_mode'] = $message;
            } else {
                error_log("40%");
                if ($database->create_tables($_POST) == false) {
                    $message = $database->error_message;
                    $errors['create_db_tables'] = $message;
                } else {
                    error_log("60%");
                    if ($database->create_admin_user($_POST, $mdstring) == false) {
                        $message = $database->getErrorMessage();
                        $errors['create_admin_user'] = $message;
                    } else {
                        error_log("80%");
                        if ($core->write_config($_POST) == false) {
                            $message = $core->getErrorMessage();
                            $errors['write_db_config'] = $message;
                        } else {
                            error_log("90%");
                            if (!empty($_POST['sources'])) {
//								error_log("sources -> " . print_r($_POST['sources'], 1));
                                if ($database->insert_ontology_sources($_POST) == false) {
                                    $message = $core->getErrorMessage();
                                    $errors['ontology_sources'] = $message;
                                }
                            }

                            if ($database->insert_settings($_POST) == false) {
                                $message = $core->getErrorMessage();
                                $errors['insert_settings'] = $message;
                            }
                        }
                    }
                }
            }
        }
        error_log("100%");


        // Insert installation_key into local install settings table
//		$_POST['installation_key'] = $mdstring;
        if ($database->insert_installation_key($_POST, $mdstring) == false) {
            $message = $core->getErrorMessage();
            $errors['insert_settings'] = $message;
        }

        // Send installation_key to Cafe Variome Central
        $api_url = "https://auth.cafevariome.org/api/auth_general/add_installation/format/json";
//		$api_url = "http://143.210.153.155/cafevariome_server/api/auth_general/add_installation/format/json";
        error_log("api_url -> $api_url");
        $data = array('installation_key' => $mdstring, 'installation_name' => $_POST['sitetitle'], 'installation_base_url' => $_POST['externalurl']);
        $opts = array('http' =>
            array(
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query($data)
            )
        );
        $context = stream_context_create($opts);
        $result = file_get_contents($api_url, false, $context);

//        $file = file_get_contents('http://ip6.me/');
//        // Trim IP based on HTML formatting
//        $pos = strpos($file, '+3') + 3;
//        $external_ip = substr($file, $pos, strlen($file));
//        // Trim IP based on HTML formatting
//        $pos = strpos($external_ip, '</');
//        $external_ip = substr($external_ip, 0, $pos);

        $external_ip = file_get_contents('http://api.ipify.org');

        if ($_POST['adminstats'] == "yes") { // User agreed to sending install stats to CV Central so use the CV Central webservice and post the data
            $api_url = "http://www.cafevariome.org/api/central/newinstall";
            $base_url = $_SERVER['HTTP_HOST'];
            $admin_email = $_POST['adminemail'];
//			$host = gethostname(); // Only available in php 5.3.0 or greater - leave out for now until find a more generic way of getting hostname
//			$host = "null";
            $host = php_uname('n'); // Works with php before 5.3
            $ip = gethostbyname($host);
//			$external_content = file_get_contents('http://checkip.dyndns.com/'); // Use dyndns service to get true external IP
//			preg_match('/Current IP Address: ([\[\]:.[0-9a-fA-F]+)</', $external_content, $m);
//			$external_ip = $m[1];
            $data = array('base_url' => $base_url, 'host' => $host, 'ip' => $ip, 'external_ip' => $external_ip, 'admin_email' => $admin_email);
            if (empty($errors)) {
                $opts = array('http' =>
                    array(
                        'method' => 'POST',
                        'header' => 'Content-type: application/x-www-form-urlencoded',
                        'content' => http_build_query($data)
                    )
                );
                $context = stream_context_create($opts);
                $result = file_get_contents($api_url, false, $context);
            }

            // Update Cafe Variome Central prefix table with the prefix for this install (only if user said yes to admin stats)
            $api_url = "http://www.cafevariome.org/api/central/insertprefix";
            $prefix = $_POST["prefix"];
            $data = array('prefix' => $prefix, 'ip' => $external_ip);
            if (empty($errors)) {
                $opts = array('http' =>
                    array(
                        'method' => 'POST',
                        'header' => 'Content-type: application/x-www-form-urlencoded',
                        'content' => http_build_query($data)
                    )
                );
                $context = stream_context_create($opts);
                $result = file_get_contents($api_url, false, $context);
            }
        }


        // Echo any errors (json array) to the jquery ajax post function
        echo json_encode($errors);
    } else {
        $message = '<div class="alert alert-error">.htaccess cannot be read by the webserver. Please make sure that "AllowOverride All" is set in your apache config and that mod_rewrite is enabled in apache (e.g. sudo a2enmod rewrite and then restart apache).</div>';
        error_log("error -> " . $message);
//		$message = $core->show_message('error', 'Not all fields have been filled in correctly. The host, username, password, and database name are required.');
    }
    ?>
<?php else: ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" lang="en">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Install | Cafe Variome</title>
            <link href="./assets/img/favicon.ico" rel="shortcut icon"/>
            <link href="./assets/css/bootstrap.css" rel="stylesheet" media="screen">
                <link href="./assets/css/install.css" rel="stylesheet" media="screen">
                    <link href="./assets/css/bootstrap-responsive.css" rel="stylesheet" media="screen">
                        <link href="./assets/css/jquery-ui-1.10.3.custom.css" rel="stylesheet" media="screen">
                            <link href="./assets/css/bootstrap-select.css" rel="stylesheet" type="text/css" />
                            <link href="./assets/css/select2.css" rel="stylesheet" type="text/css" />
                            <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
                            <!--[if lt IE 9]>
                                    <script src="../assets/js/html5shiv.js"></script>
                            <![endif]-->

                            <script src="./assets/js/jquery-1.10.2.js"></script>
                            <script src="./assets/js/jquery.form.js"></script>
                            <script src="./assets/js/jquery-ui-1.10.3.custom.js"></script>
                            <script src="./assets/js/jquery.validate.js"></script>
                            <script src="./assets/js/jquery.ajax-progress.js"></script>
                            <script src="./assets/js/bootstrap.js"></script>
                            <script src="./assets/js/install.js"></script>
                            <script src="./assets/js/json3.js"></script>
                            <script src="./assets/js/select2.js"></script>
                            <script src="./assets/js/jquery.blockUI.js"></script>

                            <script type="text/javascript">
                                $(document).ready(function () {
                                    $("#messaging-user-input").tokenInput("./lookup_ontology_sources.php", {
                                        hintText: "Type a username",
                                        theme: "facebook"
                                    });
                                });
                            </script>

                            <script type="text/javascript">
    <?php
    if (isset($_SERVER['HTTP_HOST'])) {
        $base_url = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on' ? 'https' : 'http';
        $base_url .= '://' . $_SERVER['HTTP_HOST'];
        $base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
    }
    ?>
                                var baseurl = "<?php echo $base_url; ?>";
                            </script>
                            </head>
                            <body>
                                <div class="container">
                                    <div class="row-fluid">  
                                        <div id='main' class="span8 pagination-centered">
                                            <h2 class="muted"><img src="../resources/images/cafevariome/cafevariome-logo-full.png" /><br />Installation</h2>
                                            <div class="well">	
    <?php
    $check_array = $core->initial_checks();
    if (empty($check_array)): // All initial checks were successful so proceed to installation steps
        if (isset($message)): // Print any errors return after the form was submitted
            echo '<p class="error">' . $message . '</p>';
        endif;
        ?>
                                                    <div class="tab-content">
                                                        <div class="tab-pane active" id="tabs-basic">
                                                            <div class="tabbable">
                                                                <ul id="tab" class="nav nav-tabs">
                                                                    <li class="active"><a href="#db" data-toggle="tab"><span class="badge">1</span> Database</a></li>
                                                                    <li class=""><a href="#admin" data-toggle="tab"><span class="badge">2</span> Admin</a></li>
                                                                    <li class=""><a href="#site_details" data-toggle="tab"><span class="badge">3</span> Site Details</a></li>
                                                                    <li class=""><a href="#settings" data-toggle="tab"><span class="badge">4</span> Settings</a></li>
                                                                    <li class=""><a href="#finalise" data-toggle="tab"><span class="badge">5</span> Finalise</a></li>
                                                                </ul>

                                                                <a href="#" class="btn btnPrev btn-primary btn-large" id="btnPrev"><i class="icon-arrow-left icon-white"></i> Prev</a><a href="#" class="btn btnNext btn-primary btn-large" id="btnNext">Next <i class="icon-arrow-right icon-white"></i></a>
                                                                <div class="tab-content">
                                                                    <div class="tab-pane active" id="db">
                                                                        <!--<form id="install_form" method="post" >-->
                                                                        <!--<form id="install_form" method="post" action="<?php // echo $_SERVER['PHP_SELF'];     ?>">-->
                                                                        <br /><h3>Database Settings</h3><hr>
                                                                            <label for="hostname">MySQL Hostname</label><div class="input-prepend"><span class="add-on"><i class="icon-question-sign" rel="popover" data-content="MySQL hostname (usually localhost or 127.0.0.1, to use an alternative port to 3306 use the format 127.0.0.1:8889)" ></i></span><input type="text" id="hostname" value="127.0.0.1" class="input_text" name="hostname" /></div>
                                                                            <!--<label for="hostname">MySQL Port</label><div class="input-prepend"><span class="add-on"><i class="icon-question-sign" rel="popover" data-content="MySQL port (usually 3306)" ></i></span><input type="text" id="port" value="3306" class="input_text" name="port" /></div>-->
                                                                            <label for="username">MySQL Username</label><div class="input-prepend"><span class="add-on"><i class="icon-question-sign" rel="popover" data-content="MySQL username with sufficient privileges to create databases and tables"></i></span><input type="text" id="username" class="input_text" name="username" /></div>
                                                                            <label for="password">MySQL Password</label><div class="input-prepend"><span class="add-on"><i class="icon-question-sign" rel="popover" data-content="MySQL password for username specified above" ></i></span><input type="password" id="password" class="input_text" name="password" /></div>
                                                                            <label for="database">MySQL Database Name</label><div class="input-prepend"><span class="add-on"><i class="icon-question-sign" rel="popover" data-content="Name of MySQL database for installation. Database will be created if it doesn't exist. If the database is already exists then existing data will be deleted." ></i></span><input type="text" id="database" value="cafevariome" class="input_text" name="database" /></div>
                                                                            <!--<br /><br /><label class="checkbox inline"><input id="sampledata" type="checkbox" checked> Include sample data?</label><span class="help-block">Installs a mock sample dataset to show <br />how an installation can be configured.<br />(Recommended for first time users)</span><button class="btn" rel="popover" data-content="The sample data set contains BRCA1 and BRCA2 variants from 3 mock diagnostic laboratories"><i class="icon-question-sign"></i> Read more...</button>-->
                                                                            <br /><br />
                                                                            <label for="database">Include sample data and configuration?</label>
                                                                            <div class="input-prepend"><span class="add-on"><i class="icon-question-sign" rel="popover" data-content="Select whether to include sample data in the installation. Sample data includes mock variants, sources and groups." ></i></span>
                                                                                <select id="sampledata">
                                                                                    <option value="none" selected>None</option>
                                                                                    <option value="sample">General</option>
                                                                                    <!--<option value="sample_dmudb">DMuDB</option>-->
                                                                                </select>
                                                                            </div>
                                                                    </div>
                                                                    <div class="tab-pane" id="admin">
                                                                        <br /><h3>Admin Account</h3><hr>
                                                                            <label for="adminusername">Admin Username</label><div class="input-prepend"><span class="add-on"><i class="icon-question-sign" rel="popover" data-content="Username for the main installation administrator account. Additional administrators can be created through the main site admin interface. N.B. Spaces will be automatically removed from the supplied username."></i></span><input type="text" id="adminusername" value="" class="input_text" name="adminusername" /></div>
                                                                            <label for="adminfirstname">Admin First Name</label><div class="input-prepend"><span class="add-on"><i class="icon-question-sign" rel="popover" data-content="First name for the main installation administrator account. "></i></span><input type="text" id="adminfirstname" value="" class="input_text" name="adminfirstname" /></div>
                                                                            <label for="adminlastname">Admin Last Name</label><div class="input-prepend"><span class="add-on"><i class="icon-question-sign" rel="popover" data-content="Last name for the main installation administrator account. "></i></span><input type="text" id="adminlastname" value="" class="input_text" name="adminlastname" /></div>
                                                                            <label for="adminemail">Admin Email</label><div class="input-prepend"><span class="add-on"><i class="icon-question-sign" rel="popover" data-content="Specify a valid email address e.g. example@gmail.com (syntax will be checked in the finalise stage, invalid emails will not be accepted)."></i></span><input type="text" id="adminemail" class="input_text" name="adminemail" /></div>
                                                                            <label for="adminpassword">Admin Password</label><div class="input-prepend"><span class="add-on"><i class="icon-question-sign" rel="popover" data-content="Password for administrator account."></i></span><input type="password" id="adminpassword" class="input_text" name="adminpassword" /></div>
                                                                            <input type="hidden" id="base_url" name="base_url" value="<?php echo $base_url; ?>">
                                                                    </div>
                                                                    <div class="tab-pane" id="site_details">
                                                                        <br /><h3>Site Details</h3><hr>
                                                                            <label for="adminusername">External base URL</label><div class="input-prepend"><span class="add-on"><i class="icon-question-sign" rel="popover" data-content="You must provide the external base URL of this installation that will be used as the entry point for querying. This URL can be changed later in the administrator interface but you will not be able to proceed with installation unless this URL is validated."></i></span><input type="text" id="externalurl" value="" class="input_text" name="externalurl" /></div>
                                                                            <br /><a href="#" id="external_url_button" class="btn btn-small btn-success" rel="popover" data-content="Click to validate that the external URL is contactable by other installations." data-original-title="Validate Prefix ID"><i class="icon-check icon-white"></i> Validate base URL</a>
                                                                            <div id="externalurlvalidateresult"></div>
                                                                            <br />
                                                                            <label for="adminusername">Site Title</label><div class="input-prepend"><span class="add-on"><i class="icon-question-sign" rel="popover" data-content="Main title for the site that will be shown in metadata."></i></span><input type="text" id="sitetitle" value="Cafe Variome" class="input_text" name="sitetitle" /></div>
                                                                            <label for="adminemail">Site Description</label><div class="input-prepend"><span class="add-on"><i class="icon-question-sign" rel="popover" data-content="Brief description of the site that will be shown in metadata."></i></span><input type="text" id="sitedescription" class="input_text" name="sitedescription" value="Cafe Variome Client" /></div>
                                                                            <label for="adminpassword">Site Author</label><div class="input-prepend"><span class="add-on"><i class="icon-question-sign" rel="popover" data-content="Name of site author that will be shown in metadata."></i></span><input type="text" id="siteauthor" class="input_text" name="siteauthor" value="Administrator" /></div>
                                                                            <label for="adminpassword">Site Keywords</label><div class="input-prepend"><span class="add-on"><i class="icon-question-sign" rel="popover" data-content="Site keywords metadata to help with search engine optimisation and traffic. Keywords should be separated by a comma."></i></span><input type="text" id="sitekeywords" class="input_text" name="sitekeywords" value="mutation, diagnostics, database"/></div>
                                                                    </div>
                                                                    <div class="tab-pane" id="settings">
                                                                        <br /><h3>Other Settings</h3><hr>
                                                                            <h4>Variant ID Prefix</h4><br />
                                                                            <div class="input-prepend"><span class="add-on"><a href="#"><i class="icon-question-sign" rel="popover" data-content="Enter a prefix that will be prepended to all IDs in your Cafe Variome installation. E.g. Cafe Variome Central uses the vx prefix. N.B. The prefix must be unique across all Cafe Variome installations. However, if you do not want to choose a unique ID prefix then uncheck the notify button in the finalise section and this validation step will be ignored."></i></a></span><input class="input-small" type="text" id="prefix" value="vx" class="input_text" name="prefix" /></div>
                                                                            <br />
                                                                            <a href="#" id="prefix_validate_button" class="btn btn-small btn-success" rel="popover" data-content="Click to validate that this prefix ID is unique across all Cafe Variome installs." data-original-title="Validate Prefix ID"><i class="icon-check icon-white"></i> Validate Prefix</a>
                                                                            <div id="prefixvalidateresult"></div>
                                                                            <hr>
                                                                                <h4>Phenotypes</h4><br />
                                                                                <label for="bioportalkey">BioPortal API key</label><div class="input-prepend"><span class="add-on"><a href="http://bioportal.bioontology.org/accounts/new" target="_blank" ><i class="icon-question-sign" rel="popover" data-content="To use phenotype ontologies you must sign up for a BioPortal account and supply your API key here. If this is left blank you will only be able to use free text for phenotypes, however, you may still enter your API key after installation. Click this button to be redirected to the BioPortal signup page."></i></a></span><input style="width:300px;" type="text" id="bioportalkey" value="96d81e01-96d0-4a78-8245-7c20e7e4169f" class="input_text" name="bioportalkey" /></div>
                                                                                <br />
                                                                                <a href="#" id="validate_button" class="btn btn-small btn-success" rel="popover" data-content="Click to validate your BioPortal API key and access available ontologies." data-original-title="Validate API Key"><i class="icon-check icon-white"></i> Validate Key</a>
                                                                                <div id="waiting" class="pagination-centered" style="display: none;">
                                                                                    <br />Fetching ontologies, please wait...<br /><br />
                                                                                    <img src="assets/img/ajax-loader-alt.gif" title="Loader" alt="Loader" />
                                                                                </div>
                                                                                <!--<div id="apivalidateresult"><select name="sources_select" id="sources_select" data-placeholder="Choose ontologies..." class="chosen-select" multiple style="width:500px;" tabindex="4"></select></div>-->
                                                                                <div id="apivalidateresult"></div>
                                                                    </div>
                                                                    <div class="tab-pane" id="finalise">
                                                                        <!--<br /><h3>Finalise</h3><hr>-->
                                                                        <br /><label class="checkbox inline"><input id="adminstats" type="checkbox" checked> Notify Cafe Variome Central about this installation and the ID prefix being used?</label><span class="help-block">Please leave this checked where possible to help us track usage</span>
                                                                        <br />
                                                                        <div id="complete" ></div>
                                                                        <div id="finalise-settings" >
                                                                            <div class="row-fluid">
                                                                                <div class="span6">
                                                                                    <!-- content for this div is populated with the validation table in the install.js file -->
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                    </div>

                                                                                            <!--</form>-->
                                                                                            </div><!-- /.tab-content -->
                                                                                            </div><!-- /.tabbable -->
                                                                                            </div><!-- .tabs-basic -->
                                                                                            </div><!-- .tabs-content -->
                                                                                            <br /><br />
    <?php else: ?>
                                                                                            <h4>Before you can proceed please correct the following problems:</h4>
                                                                                            <p><a href="#" class="btn btn-success btn-large" onclick="location.reload();"><i class="icon-refresh icon-white"></i> Re-check</a></p>
                                                                                            <?php
                                                                                            foreach ($check_array as $check) {
                                                                                                echo $check;
                                                                                            }
                                                                                            ?>
                                                                                        <?php endif; ?>
                                                                                        </div>
                                                                                        </div>
                                                                                        </div>
                                                                                        </div>
                                                                                        <div id="dialog" class="modal hide fade">
                                                                                            <div class="modal-header">
                                                                                                <!--<a href="#" class="close">&times;</a>-->
                                                                                                <!--<button id="close_header" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>-->
                                                                                                <h4>Cafe Variome Installation</h4>
                                                                                            </div>
                                                                                            <div class="modal-body">
                                                                                                <div id="status_info" ><p>Please wait...</div><br />
                                                                                                <div id="progressbar"><div class="progress-label"><!--Installing Cafe Variome--></div></div>
                                                                                            </div>
                                                                                            <div class="modal-footer">
                                                                                                <a href="#" id="close_footer" data-dismiss="modal" data-target="#myModal" class="btn primary">Close</a>
                                                                                            </div>
                                                                                        </div>
                                                                                        </body>
                                                                                        </html>
<?php endif; ?>