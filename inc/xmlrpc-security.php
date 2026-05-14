<?php
if (!defined('ABSPATH')) {
    exit;
}


// Полное отключение XML-RPC
add_filter( 'xmlrpc_enabled', '__return_false' );
