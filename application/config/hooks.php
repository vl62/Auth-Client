<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/

// Hook to check that install has taken place
//$hook['pre_controller'][] = array(
//                                'class'    => 'Install',
//                                'function' => 'check',
//                                'filename' => 'install.php',
//                                'filepath' => 'hooks',
//                                'params'   => array()
//                                );

// Hook to get current page and store in session (used for redirecting back to original page after logging in
//$hook['post_controller'][] = array(
//                                'class'    => 'Current',
//                                'function' => 'set_current_page',
//                                'filename' => 'current.php',
//                                'filepath' => 'hooks',
//                                'params'   => array()
//                                );

// Hook to get check whether the user is logged in // post_controller_constructor display_override
$hook['post_controller_constructor'][] = array(
                                'class'    => 'Authcheck',
                                'function' => 'is_logged_in',
                                'filename' => 'authcheck.php',
                                'filepath' => 'hooks',
                                'params'   => array()
                                );

//$hook['post_controller_constructor'][] = array(
$hook['pre_controller'][] = array(
                                'class'    => 'Configs',
                                'function' => 'get_configs',
                                'filename' => 'configs.php',
                                'filepath' => 'hooks',
                                'params'   => array()
                                );

/* End of file hooks.php */
/* Location: ./application/config/hooks.php */