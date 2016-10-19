<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the "Database Connection"
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the "default" group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

// The following values will probably need to be changed.
$db['default']['username'] = "lampuser";
$db['default']['password'] = "changeme";
$db['default']['database'] = "cv_client";

// The following values can probably stay the same.
$db['default']['hostname'] = "127.0.0.1";
$db['default']['dbdriver'] = "mysql";
$db['default']['dbprefix'] = "";
$db['default']['pconnect'] = FALSE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = "";
$db['default']['char_set'] = "utf8";
$db['default']['dbcollat'] = "utf8_general_ci";

$active_group = "default";
$active_record = TRUE;

// Only used if statistics are enabled in application/config/cafevariome.php config file (N.B. statistics database structure must be present in mysql)
//$db['stats']['username'] = "lampuser"; // Add your mysql username
//$db['stats']['password'] = "changeme"; // Add your mysql password
//$db['stats']['database'] = "%STATSDATABASE%";
//$db['stats']['hostname'] = "127.0.0.1";
//$db['stats']['dbdriver'] = "mysql";
//$db['stats']['dbprefix'] = "";
//$db['stats']['pconnect'] = FALSE;
//$db['stats']['db_debug'] = TRUE;
//$db['stats']['cache_on'] = FALSE;
//$db['stats']['cachedir'] = "";
//$db['stats']['char_set'] = "utf8";
//$db['stats']['dbcollat'] = "utf8_general_ci";

/* End of file database.php */
/* Location: ./application/config/database.php */