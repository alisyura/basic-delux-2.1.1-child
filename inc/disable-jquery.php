<?php
if (!defined('ABSPATH')) {
    exit;
}

// убрать вызов скрипта jquery
if ( !is_admin() ) { 
	wp_deregister_script('jquery'); 
}
